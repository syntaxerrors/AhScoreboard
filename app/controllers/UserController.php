<?php

class UserController extends Core_UserController {

	public function getFavorites()
	{
		LeftTab::
			addPanel()
				->setTitle('Favorites')
				->setBasePath('user')
				->addTab('Favorite Game Videos', 'favorite-game-videos', 'favorite-game-videos')
				->addTab('Favorite Non-Game Videos', 'favorite-videos', 'favorite-videos')
				->addTab('Favorite Quotes', 'favorite-quotes', 'favorite-quotes')
			->buildPanel()
		->setGlow(true)
		->make();
		$videoIds = $this->activeUser->favorites->filter(function($favorite) {
			if ($favorite->morph_type == 'Video') return true;
		})->morph->id->toArray();
		$videos   = Video::whereIn('uniqueId', $videoIds)->orderBy('date', 'desc')->get();

		$quoteIds = $this->activeUser->favorites->filter(function($favorite) {
			if ($favorite->morph_type == 'Video_Quote') return true;
		})->morph->video->id->toArray();
		if (count($quoteIds) > 0) {
			$quoteVideos   = Video::whereIn('uniqueId', $quoteIds)->orderBy('date', 'desc')->get();
		} else {
			$quoteVideos = array();
		}

		$gameVideos = clone $videos;
		// ppd($gameVideos);
		$gameVideos = $gameVideos->filter(function ($video) {
			if(!$video->series->checkType('NON_GAME')) return true;
		});

		$nonGameVideos = clone $videos;
		$nonGameVideos = $nonGameVideos->filter(function ($video) {
			if($video->series->checkType('NON_GAME')) return true;
		});

		$this->setViewData('gameVideos', $gameVideos);
		$this->setViewData('nonGameVideos', $nonGameVideos);
		$this->setViewData('quoteVideos', $quoteVideos);
	}

	public function getFavoriteVideos()
	{
		$videoIds = $this->activeUser->favorites->filter(function($favorite) {
			if ($favorite->morph_type == 'Video') return true;
		})->morph->id->toArray();
		$videos   = Video::whereIn('uniqueId', $videoIds)->orderBy('date', 'desc')->paginate(20);

		$videos = $videos->filter(function ($video) {
			if($video->series->checkType('NON_GAME')) return true;
		});

		$this->setViewData('videos', $videos);
	}

	public function getFavoriteGameVideos()
	{
		$videoIds = $this->activeUser->favorites->filter(function($favorite) {
			if ($favorite->morph_type == 'Video') return true;
		})->morph->id->toArray();
		$videos   = Video::whereIn('uniqueId', $videoIds)->orderBy('date', 'desc')->get();

		$videos = $videos->filter(function ($video) {
			if(!$video->series->checkType('NON_GAME')) return true;
		});

		$this->setViewData('videos', $videos);
	}

	public function getQuotes()
	{
		$episodeIds = $this->activeUser->favorites->filter(function($favorite) {
			if ($favorite->favorite_type == 'Episode_Quote') return true;
		})->favorite->episode->id->toArray();

		if (count($episodeIds) > 0) {
			$episodes   = Episode::with('quotes')->whereIn('uniqueId', $episodeIds)->orderBy('date', 'desc')->paginate(20);
		} else {
			$episodes = Paginator::make(array(), 0, 20);
		}

		ppd($episodes);

		$this->setViewData('episodes', $episodes);
	}

	public function getUnwatched()
	{
		$episodeIds = $this->activeUser->watched->episode->id->toArray();
		$episodes   = Episode::whereNotIn('uniqueId', $episodeIds)->orderBy('date', 'desc')->paginate(20);

		$this->setViewPath('episode.list');
		$this->setViewData('episodes', $episodes);
	}
}