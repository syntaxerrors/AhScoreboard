<?php

class ActorPresenter extends Syntax\Core\CorePresenter {

	public function classes()
	{
		$class = null;
		if ($this->resource->checkType('AH_ACTOR')) {
			$class = 'unread';
		}

		return $class;
	}

	// public function latestVideo()
	// {
	// 	if ($this->resource->videos->count() > 0) {
	// 		return $this->resource->videos()->orderBy('date', 'desc')->orderBy('uniqueId', 'asc')->first()->linkTo;
	// 	}

	// 	return null;
	// }

	public function totalAppearances($seriesKeyName)
	{
		return $this->resource->videos->filter(function ($video) use ($seriesKeyName) {
			if ($video->series->keyName == $seriesKeyName) return true;
		})->count();
	}

	public function totalWins($seriesKeyName)
	{
		return $this->resource->wins->video->filter(function ($video) use ($seriesKeyName) {
			if ($video->series->keyName == $seriesKeyName) return true;
		})->count();
	}

	public function totalAppearancesByType($typeKeyName)
	{
		return $this->resource->videos->filter(function ($video) use ($typeKeyName) {
			if ($video->checkType($typeKeyName)) return true;
		})->count();
	}

	public function totalWinsByType($typeKeyName)
	{
		return $this->resource->wins->video->filter(function ($video) use ($typeKeyName) {
			if ($video->checkType($typeKeyName)) return true;
		})->count();
	}

	public function competedAgainst($seriesKeyName, $actorId)
	{
		$thisActorId = $this->resource->id;
		return Video::whereHas('actors', function ($query) use ($actorId) {
			$query->where('morph_type', 'Actor')->where('morph_id', $actorId);
		})->whereHas('actors', function ($query) use ($thisActorId) {
			$query->where('morph_type', 'Actor')->where('morph_id', $thisActorId);
		})->get()->filter(function ($video) use ($seriesKeyName) {
			if ($video->series->keyName == $seriesKeyName) return true;
		});
	}

	public function bestStreak($seriesKeyName, $full = false)
	{
		$thisActorId = $this->resource->id;
		$bestStreak = new Utility_Collection(array(0 => new Utility_Collection()));
		$lastKey = 0;
		$competedIn = $this->resource->videos->filter(function ($video) use ($seriesKeyName) {
			if ($video->series->keyName == $seriesKeyName) return true;
		})->reverse()->each(function ($video) use ($thisActorId, &$bestStreak, &$lastKey) {
			if ($video->winners->where('morph_type', 'Actor')->where('morph_id', $thisActorId)->count() > 0) {
				$bestStreak[$lastKey]->add($video);
			} else {
				$lastKey = $lastKey + 1;
				$bestStreak[$lastKey] = new Utility_Collection();
			}
		});

		$bestStreak->sortBy(function ($streak) {
			return $streak->count();
		});

		if (!$full) {
			return $bestStreak->last()->count();
		}

		return $bestStreak->last();
	}

	public function bestStreakByType($typeKeyName, $full = false)
	{
		$thisActorId = $this->resource->id;
		$bestStreak = new Utility_Collection(array(0 => new Utility_Collection()));
		$lastKey = 0;
		$competedIn = $this->resource->videos->filter(function ($video) use ($typeKeyName) {
			if ($video->checkType($typeKeyName) && $video->winners->where('morph_type', 'Actor')->count() > 0) return true;
		})->reverse()->each(function ($video) use ($thisActorId, &$bestStreak, &$lastKey) {
			if ($video->winners->where('morph_type', 'Actor')->where('morph_id', $thisActorId)->count() > 0) {
				$bestStreak[$lastKey]->add($video);
			} else {
				$lastKey = $lastKey + 1;
				$bestStreak[$lastKey] = new Utility_Collection();
			}
		});

		$bestStreak->sortBy(function ($streak) {
			return $streak->count();
		});

		if (!$full) {
			return $bestStreak->last()->count();
		}

		return $bestStreak->last();
	}
}