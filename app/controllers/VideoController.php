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

	public function getViewStreak($seriesKeyName, $actorId)
	{
		$actor  = Actor::find($actorId);
		$series = Series::where('keyName', $seriesKeyName)->first();

		$this->setViewData('actor', $actor);
		$this->setViewData('series', $series);
	}

	public function getViewStreakType($typeKeyName, $actorId)
	{
		$actor  = Actor::find($actorId);
		$type   = Type::where('keyName', $typeKeyName)->first();

		$this->setViewData('actor', $actor);
		$this->setViewData('type', $type);
	}

	public function getRss()
	{
		$developer = $this->hasPermission('DEVELOPER');

		$ahUrl = 'http://gdata.youtube.com/feeds/api/videos?orderby=published&max-results=20&author=LetsPlay';
		$rtUrl = 'http://gdata.youtube.com/feeds/api/videos?orderby=published&max-results=20&author=RoosterTeeth';
		$tkUrl = 'http://gdata.youtube.com/feeds/api/videos?orderby=published&max-results=20&author=know';

		$ahFeed = new SimplePie();
		$ahFeed->set_feed_url($ahUrl);
		$ahFeed->set_cache_location(storage_path().'/cache');
		$ahFeed->set_cache_duration(30);
		$ahFeed->set_item_limit(50);
		$ahFeed->init();

		$rtFeed = new SimplePie();
		$rtFeed->set_feed_url($rtUrl);
		$rtFeed->set_cache_location(storage_path().'/cache');
		$rtFeed->set_cache_duration(30);
		$rtFeed->set_item_limit(50);
		$rtFeed->init();

		$tkFeed = new SimplePie();
		$tkFeed->set_feed_url($tkUrl);
		$tkFeed->set_cache_location(storage_path().'/cache');
		$tkFeed->set_cache_duration(30);
		$tkFeed->set_item_limit(50);
		$tkFeed->init();

		$this->setViewData('developer', $developer);
		$this->setViewData('ahFeed', $ahFeed);
		$this->setViewData('rtFeed', $rtFeed);
		$this->setViewData('tkFeed', $tkFeed);
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

			preg_match('/[0-9]+/', $title, $matches);
			if (isset($matches[0])) {
				$seriesNumber = $matches[0];
			} else {
				$seriesNumber = 0;
			}
		} else {
			$seriesId     = 0;
			$gameId       = 0;
			$seriesNumber = 0;
		}

		// Get all data
		$series = Series::orderByNameAsc()->get()->toSelectArray('Select a series');
		$games  = Game::orderByNameAsc()->get()->toSelectArray('Select a game');
		$videos = Video::orderBy('title', 'asc')->get()->toSelectArray('Select a video');
		$teams  = Team::orderByNameAsc()->get();
		$actors = Actor::orderByNameAsc()->get();

		$ahActors = clone $actors;
		$ahActors = $ahActors->filter(function ($actor) {
			if ($actor->checkType('AH_ACTOR')) return true;
		});

		$podcastActors = clone $actors;
		$podcastActors = $podcastActors->filter(function ($actor) {
			if ($actor->checkType('PODCAST_CREW')) return true;
		});

		$newsActors = clone $actors;
		$newsActors = $newsActors->filter(function ($actor) {
			if ($actor->checkType('NEWS_REPORTER')) return true;
		});

		$voiceActors = clone $actors;
		$voiceActors = $voiceActors->filter(function ($actor) {
			if ($actor->checkType('VOICE_ONLY')) return true;
		});

		$actors = $actors->filter(function ($actor) {
			if ($actor->checkType('VOICE_ONLY')) return false;
			if (!$actor->checkType(array('AH_ACTOR', 'NEWS_REPORTER', 'PODCAST_CREW', 'VOICE_ONLY'))) return true;
		});

		$this->setViewData('types', $types);
		$this->setViewData('series', $series);
		$this->setViewData('seriesNumber', $seriesNumber);
		$this->setViewData('games', $games);
		$this->setViewData('videos', $videos);
		$this->setViewData('teams', $teams);
		$this->setViewData('ahActors', $ahActors);
		$this->setViewData('podcastActors', $podcastActors);
		$this->setViewData('newsActors', $newsActors);
		$this->setViewData('actors', $actors);
		$this->setViewData('voiceActors', $voiceActors);
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

			if (!$video->checkType('ROUND_BASED_ACTORS') && !$video->series->checkType('CHARACTERS')) {
				foreach ($input['actor'] as $actor => $value) {
					$bits = explode('::', $actor);

					$videoActor             = new Video_Actor;
					$videoActor->video_id   = $video->id;
					$videoActor->morph_id   = $bits[1];
					$videoActor->morph_type = $bits[0];

					$this->save($videoActor);
				}
			}

			// Get the image
			Image::make('http://img.youtube.com/vi/'. $video->link .'/hqdefault.jpg')->save(public_path() .'/img/youtube/'. $video->id .'.jpg');

			if (isset($input['details'])) {
				return $this->redirect('/manage/detail/'. $video->id, $video->title .' has been submitted.');
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

		$ahActors = clone $actors;
		$ahActors = $ahActors->filter(function ($actor) {
			if ($actor->checkType('AH_ACTOR')) return true;
		});

		$podcastActors = clone $actors;
		$podcastActors = $podcastActors->filter(function ($actor) {
			if ($actor->checkType('PODCAST_CREW')) return true;
		});

		$newsActors = clone $actors;
		$newsActors = $newsActors->filter(function ($actor) {
			if ($actor->checkType('NEWS_REPORTER')) return true;
		});

		$voiceActors = clone $actors;
		$voiceActors = $voiceActors->filter(function ($actor) {
			if ($actor->checkType('VOICE_ONLY')) return true;
		});

		$actors = $actors->filter(function ($actor) {
			if ($actor->checkType('VOICE_ONLY')) return false;
			if (!$actor->checkType(array('AH_ACTOR', 'NEWS_REPORTER', 'PODCAST_CREW', 'VOICE_ONLY'))) return true;
		});

		$this->setViewData('types', $types);
		$this->setViewData('video', $video);
		$this->setViewData('series', $series);
		$this->setViewData('games', $games);
		$this->setViewData('videos', $videos);
		$this->setViewData('teams', $teams);
		$this->setViewData('ahActors', $ahActors);
		$this->setViewData('podcastActors', $podcastActors);
		$this->setViewData('newsActors', $newsActors);
		$this->setViewData('actors', $actors);
		$this->setViewData('voiceActors', $voiceActors);
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
			$video->parentId              = $input['parentId'] != '0' ? $input['parentId'] : null;
			$video->link                  = $this->getYoutubeId($input['link']);
			$video->date                  = date('Y-m-d', strtotime($input['date']));

			$this->checkErrorsSave($video);

			$video->types()->detach();
			if (Input::has('types')) {
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
			if (!$video->checkType('ROUND_BASED_ACTORS') && !$video->series->checkType('CHARACTERS')) {
				if (isset($input['actor'])) {
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
				}
			}

			if (isset($input['details'])) {
				return $this->redirect('/manage/details/'. $video->id, $video->title .' has been submitted.');
			} else {
				return $this->redirect('/manage', $video->title .' has been submitted.');
			}
		}
	}

	public function getDelete($videoId)
	{
		$this->skipView();

		Video::find($videoId)->delete();

		return $this->redirect('back', 'Video deleted.');
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