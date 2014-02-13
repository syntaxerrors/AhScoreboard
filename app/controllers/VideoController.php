<?php

class VideoController extends BaseController {

	public function getIndex() {}

	public function getView($videoId)
	{
		$video = Video::find($videoId);

		$this->setViewData('video', $video);
	}

	public function getQuote($quoteId)
	{
		$quote = Video_Quote::find($quoteId);

		$this->setViewData('quote', $quote);
	}

	public function getRss()
	{
		$developer = $this->hasPermission('DEVELOPER');

		$ahUrl = 'http://gdata.youtube.com/feeds/api/videos?orderby=published&max-results=50&author=LetsPlay';
		$rtUrl = 'http://gdata.youtube.com/feeds/api/videos?orderby=published&max-results=50&author=RoosterTeeth';
		// ppd($rtUrl);
		// $urls = array(
		// 	// 'http://pipes.yahoo.com/pipes/pipe.run?_id=d0faef82517b29ea6472e4b58df7a923&_render=rss',
		// 	'http://gdata.youtube.com/feeds/api/videos?q='. urlencode('-"Behind the scenes"-"News:"-"Trials Files"') .'&orderby=published&max-results=50&author=RoosterTeeth',
		// 	'http://gdata.youtube.com/feeds/api/videos?orderby=published&max-results=10&author=LetsPlay',
		// );
		$ahFeed = new SimplePie();
		$ahFeed->set_feed_url($ahUrl);
		$ahFeed->set_cache_location(storage_path().'/cache');
		$ahFeed->set_cache_duration(100);
		$ahFeed->set_item_limit(50);
		$ahFeed->init();

		$rtFeed = new SimplePie();
		$rtFeed->set_feed_url($rtUrl);
		$rtFeed->set_cache_location(storage_path().'/cache');
		$rtFeed->set_cache_duration(100);
		$rtFeed->set_item_limit(50);
		$rtFeed->init();

		$this->setViewData('developer', $developer);
		$this->setViewData('ahFeed', $ahFeed);
		$this->setViewData('rtFeed', $rtFeed);
	}

	public function getAdd($title = null, $link = null, $date = null)
	{
		$this->checkPermission('ADD_EPISODES');

		$types = Type::where('videoFlag', 1)->orderByNameAsc()->get()->toSelectArray(false);

		// Try to get the details
		$title = urldecode($title);
		if ($title != null) {
			$series = Series::orderByNameAsc()->get()->filter(function ($singleSeries) use ($title) {
				if (stripos(e(strtolower($title)), e(strtolower($singleSeries->name))) !== false) {
					return true;
				}
			});
			$game = Game::orderByNameAsc()->get()->filter(function ($singleGame) use ($title) {
				$commonNames = explode(',', $singleGame->commonName);

				foreach ($commonNames as $commonName) {
					if (stripos(e(strtolower($title)), e(strtolower($commonName))) !== false) {
						return true;
					}
				}
			});
			$seriesId = (isset($series->id[0]) ? $series->id[0] : 0);
			$gameId   = (isset($game->id[0]) ? $game->id[0] : 0);
		} else {
			$seriesId = 0;
			$gameId   = 0;
		}

		preg_match('/[0-9]+/', $title, $matches);
		$seriesNumber = $matches[0];

		// Get all data
		$series = Series::orderByNameAsc()->get()->toSelectArray('Select a series');
		$games  = Game::orderByNameAsc()->get()->toSelectArray('Select a game');
		$videos = Video::orderBy('title', 'asc')->get()->toSelectArray('Select a video');
		$teams  = Team::orderByNameAsc()->get();
		$actors = Actor::orderByNameAsc()->get();

		$this->setViewData('types', $types);
		$this->setViewData('series', $series);
		$this->setViewData('seriesNumber', $seriesNumber);
		$this->setViewData('games', $games);
		$this->setViewData('videos', $videos);
		$this->setViewData('teams', $teams);
		$this->setViewData('actors', $actors);
		$this->setViewData('seriesId', $seriesId);
		$this->setViewData('gameId', $gameId);
		$this->setViewData('link', $link);
		$this->setViewData('date', $date);
		$this->setViewData('title', $title);
	}

	public function postAdd()
	{
		$this->checkPermission('ADD_EPISODES');

		$input = e_array(Input::all());

		if ($input != null) {
			$video                        = new Video;
			$video->series_id             = $input['series_id'];
			$video->seriesNumber          = $input['seriesNumber'];
			$video->title                 = $input['title'];
			$video->link                  = $this->getYoutubeId($input['link']);
			$video->date                  = date('Y-m-d', strtotime($input['date']));

			$this->checkErrorsSave($video);

			if (Input::has('types')) {
				foreach ($input['types'] as $typeId) {
					$newType           = new Video_Type;
					$newType->video_id = $video->id;
					$newType->type_id  = $typeId;

					$this->save($newType);
				}
			}

			if (!$video->checkType('ROUND_BASED_GAMES') && !$video->series->checkType('NON_GAME')) {
				$videoGame             = new Video_Game;
				$videoGame->game_id    = $input['game_id'];
				$videoGame->video_id   = $video->id;

				$this->save($videoGame);
			}

			if (!$video->checkType('ROUND_BASED_ACTORS')) {
				foreach ($input['actor'] as $actor => $value) {
					$bits = explode('::', $actor);

					$videoActor             = new Video_Actor;
					$videoActor->video_id   = $video->id;
					$videoActor->morph_id   = $bits[1];
					$videoActor->morph_type = $bits[0];

					$this->save($videoActor);
				}
			}

			if (isset($input['details'])) {
				$link = ($video->checkType('CO_OP') ? 'coopstats' : 'winners');

				return $this->redirect('/manage/'. $link .'/'. $video->id, $video->title .' has been submitted.');
			} else {
				return $this->redirect('/manage', $video->title .' has been submitted.');
			}
		}
	}

	public function getEdit($videoId)
	{
		$this->checkPermission('ADD_EPISODES');

		$types = Type::where('videoFlag', 1)->orderByNameAsc()->get()->toSelectArray(false);

		$video = Video::find($videoId);

		// Get all data
		$series = Series::orderByNameAsc()->get()->toSelectArray('Select a series');
		$games  = Game::orderByNameAsc()->get()->toSelectArray('Select a game');
		$videos = Video::orderBy('title', 'asc')->get()->toSelectArray('Select a video');
		$teams  = Team::orderByNameAsc()->get();
		$actors = Actor::orderByNameAsc()->get();

		$this->setViewData('types', $types);
		$this->setViewData('video', $video);
		$this->setViewData('series', $series);
		$this->setViewData('games', $games);
		$this->setViewData('videos', $videos);
		$this->setViewData('teams', $teams);
		$this->setViewData('actors', $actors);
	}

	public function postEdit($videoId)
	{
		$this->checkPermission('ADD_EPISODES');

		$input = e_array(Input::all());

		if ($input != null) {
			$video                        = Video::find($videoId);
			$video->series_id             = $input['series_id'];
			$video->seriesNumber          = $input['seriesNumber'];
			$video->title                 = $input['title'];
			$video->link                  = $this->getYoutubeId($input['link']);
			$video->date                  = date('Y-m-d', strtotime($input['date']));

			$this->checkErrorsSave($video);

			if (Input::has('types')) {
				foreach ($video->types as $type) {
					$type->delete();
				}
				foreach ($input['types'] as $typeId) {
					$newType           = new Video_Type;
					$newType->video_id = $video->id;
					$newType->type_id  = $typeId;

					$this->save($newType);
				}
			}

			if (!$video->checkType('ROUND_BASED_GAMES') && !$video->series->checkType('NON_GAME')) {
				$video->games->delete();

				$videoGame             = new Video_Game;
				$videoGame->game_id    = $input['game_id'];
				$videoGame->video_id   = $video->id;

				$this->save($videoGame);
			}

			foreach ($video->actors as $actor) {
				$actor->delete();
			}
			foreach ($input['actor'] as $actor => $value) {
				$bits = explode('::', $actor);

				$videoActor             = new Video_Actor;
				$videoActor->video_id   = $video->id;
				$videoActor->morph_id   = $bits[1];
				$videoActor->morph_type = $bits[0];

				$this->save($videoActor);
			}

			if (isset($input['details'])) {
				$link = ($video->checkType('CO_OP') ? 'coopstats' : 'winners');

				return $this->redirect('/manage/'. $link .'/'. $video->id, $video->title .' has been submitted.');
			} else {
				return $this->redirect('/manage', $video->title .' has been submitted.');
			}
		}
	}

	public function getFavorite($id, $type)
	{
		$this->activeUser->setFavorite($id, $type);
	}

	public function getUnfavorite($id, $type)
	{
		$this->activeUser->removeFavorite($id, $type);
	}

	public function getWatched($episodeId)
	{
		$this->activeUser->setWatched($episodeId);
	}

	public function getUnwatch($episodeId)
	{
		$this->activeUser->removeWatched($episodeId);
	}

}