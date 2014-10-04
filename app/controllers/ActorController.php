<?php

class ActorController extends BaseController {

	public function getIndex()
	{
		$actors = Actor::orderByNameAsc()->get();

		$ahActors = clone $actors;
		$ahActors = $ahActors->filter(function ($actor) {
			if ($actor->checkType('AH_ACTOR')) return true;
		});

		$actors = $actors->filter(function ($actor) {
			if (!$actor->checkType('AH_ACTOR')) return true;
		});

		$this->setViewData('actors', $actors);
		$this->setViewData('ahActors', $ahActors);
	}

	public function getView($actorId)
	{
		$actor  = Actor::find($actorId);
		$actors = Actor::where('uniqueId', '!=', $actorId)->orderByNameAsc()->get();

		$this->setViewData('actor', $actor);
		$this->setViewData('actors', $actors);
	}
}