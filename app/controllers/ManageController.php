<?php

class ManageController extends BaseController {

	public function getReset()
	{
		$videos = Video::all()->each(function($video) {
			if ($video->checkType('CO_OP')) {
				$video->rounds->delete();
			}
		});
	}

	public function getIndex($series = null, $game = null)
	{

		// $videos = Video::all();

		// foreach ($videos as $video) {
		// 	$video->updateActors();
		// 	$video->updateGames();
		// }
		$paginator = true;

		if ($series != null && $game != null) {
			$seriesId = Series::where('keyName', $series)->first()->id;
			$videos   = Video::where('series_id', $seriesId)->orderBy('date', 'desc')->get();

			$videos = $videos->filter(function ($video) use ($game) {
				if (in_array($game, $video->games->game->keyName->toArray())) return true;
			});

			$paginator = false;
		} elseif ($series != null) {
			$seriesId = Series::where('keyName', $series)->first()->id;
			$videos = Video::where('series_id', $seriesId)->orderBy('date', 'desc')->paginate(20);
		} else {
			$videos = Video::orderBy('date', 'desc')->paginate(20);
		}

		$this->setViewData('videos', $videos);
		$this->setViewData('paginator', $paginator);
	}

	public function getDetail($videoId, $roundId = null)
	{
		$this->checkPermission('ADD_STATS');

		$this->activeUser->setWatched($videoId);

		$video  = Video::find($videoId);

		if ($video->series->checkType('CHARACTERS')) {
			$characters = Character::orderByNameAsc()->get()->toSelectArray('Select a character');

			$this->setViewData('video', $video);
			$this->setViewData('roundId', $roundId);
			$this->setViewData('characters', $characters);
		} else {
			$teams  = Team::orderByNameAsc();
			$actors = Actor::orderByNameAsc();

			if (!$video->checkType('ROUND_BASED_ACTORS')) {
				$actorsMorph = $video->actors->filter(function ($actor) {
					if ($actor->morph_type == 'Actor') {
						return true;
					}
				});
				$teamMorphs = $video->actors->filter(function ($actor) {
					if ($actor->morph_type == 'Team') {
						return true;
					}
				});

				if ($actorsMorph->count() > 0) {
					$actors->whereIn('uniqueId', $actorsMorph->morph_id->toArray());
				}
				if ($teamMorphs->count() > 0) {
					$teams->whereIn('uniqueId', $teamMorphs->morph_id->toArray());
				}
			}

			$actors = $actors->get();
			$teams  = $teams->get();

			$ahActors = clone $actors;

			$ahActors = $ahActors->filter(function ($actor) {
				return $actor->checkType('AH_ACTOR');
			});

			$actors = $actors->filter(function ($actor) {
				return ! $actor->checkType('AH_ACTOR');
			});

			$teamsArray  = $teams->toSelectArray('Select an team');
			$ahActorsArray = $ahActors->toSelectArray('Select an actor');
			$nonAhActorsArray = $actors->toSelectArray(false);

			$actorsArray = array_merge($ahActorsArray, $nonAhActorsArray);

			$multiActorsArray = $actorsArray;
			unset($multiActorsArray[0]);

			$multiTeamsArray = $teamsArray;
			unset($multiTeamsArray[0]);

			// $teamsArray   = $this->arrayToSelect($teams, 'id', 'name', 'Select a team');
			// $actorsArray = $this->arrayToSelect($finalActors, 'id', 'name', 'Select an actor');


			$roundActors = array();

			$this->setViewData('video', $video);
			$this->setViewData('roundId', $roundId);
			$this->setViewData('teams', $teams);
			$this->setViewData('actors', $actors);
			$this->setViewData('teamsArray', $teamsArray);
			$this->setViewData('actorsArray', $actorsArray);
			$this->setViewData('multiTeamsArray', $multiTeamsArray);
			$this->setViewData('multiActorsArray', $multiActorsArray);

			if ($roundId != null) {
				$round = Round::find($roundId);

				$roundActors = $round->actors->morph->id->toArray();

				$this->setViewData('round', $round);
			}

			if ($video->checkType('ROUND_BASED_GAMES')) {
				$games = Game::orderByNameAsc()->get()->toSelectArray('Select a game');
				$this->setViewData('games', $games);
			}

			$this->setViewData('roundActors', $roundActors);
		}
	}

	public function postAddQuote($videoId)
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			$newQuote            = new Video_Quote;
			$newQuote->video_id  = $videoId;
			$newQuote->title     = $input['title'];
			$newQuote->timeStart = $input['timeStart'];
			$newQuote->timeEnd   = $input['timeEnd'];
			$newQuote->quotes    = $input['quotes'];

			$this->save($newQuote);

			if (Input::has('actors') && count($input['actors']) > 0) {
				foreach ($input['actors'] as $actorId) {
					$newQuoteMember                 = new Video_Quote_Actor;
					$newQuoteMember->video_quote_id = $newQuote->id;
					$newQuoteMember->morph_id       = $actorId;
					$newQuoteMember->morph_type     = 'Actor';

					$this->save($newQuoteMember);
				}
			}

			if (Input::has('characters') && count($input['characters']) > 0) {
				foreach ($input['characters'] as $characterId) {
					$newQuoteMember                 = new Video_Quote_Actor;
					$newQuoteMember->video_quote_id = $newQuote->id;
					$newQuoteMember->morph_id       = $characterId;
					$newQuoteMember->morph_type     = 'Character';

					$this->save($newQuoteMember);
				}
			}

			// Handle errors
			if ($this->errorCount() > 0) {
				Ajax::addErrors($this->getErrors());
			} else {
				$newQuote->memberNames = implode(', ', Video_Quote::find($newQuote->id)->actors->morph->firstNameLink->toArray());
				Ajax::setStatus('success')->addData('resource', $newQuote->toArray());
			}

			// Send the response
			return Ajax::sendResponse();
		}
	}

	public function postAddCharacters($videoId)
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			$videoCharacter = new Video_Character;
			$videoCharacter->video_id = $videoId;
			$videoCharacter->character_id = $input['character_id'];

			$this->save($videoCharacter);

			// Send the response
			return $this->redirect('back', null);
		}
	}

	public function postAddWinners($videoId, $roundId = null)
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			$video = Video::find($videoId);

			if ($roundId != null) {
				$round = Round::find($roundId);
				$round->modify($input);
			} else {
				if ($input['type'] == 'nextRound' || $input['type'] == 'addRound' || $input['type'] == 'finish') {
					$video->addRound($input);
				}
			}
		}
		if ($input['type'] == 'nextRound') {
			if (isset($round)) {
				$nextRound = Round::where('video_id', $video->id)
								  ->where('roundNumber', '>', $round->roundNumber)
								  ->orderBy('roundNumber', 'asc')
								  ->first();

				if (isset($nextRound->id)) {
					$link = '/manage/detail/'. $video->id .'/'. $nextRound->id;
				} else {
					$link = '/manage/detail/'. $video->id;
				}
			} else {
				$link = '/manage/detail/'. $video->id;
			}
			return $this->redirect($link, 'Round saved.');
		} elseif ($input['type'] == 'addRound') {
			return $this->redirect('/manage/detail/'. $video->id, 'Round added.');
		} elseif ($input['type'] == 'finish') {
			if ($video->checkType('OVERALL_WINNER')) {
				return $this->redirect('/manage/overall-winner/'. $video->id, 'Winner added.');
			} else {
				return $this->redirect('/manage', 'Winner added.');
			}
		}
	}

	public function postAddWaves($videoId, $roundId = null)
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			$video = Video::find($videoId);

			if ($roundId != null) {
				$round = Round::find($roundId);
				$round->wave->modify($input);
			} else {
				if ($input['type'] == 'nextRound' || $input['type'] == 'addRound' || $input['type'] == 'finish') {
					$video->addRoundWave($input);
				}
			}
		}
		if ($input['type'] == 'nextRound') {
			if (isset($round)) {
				$nextRound = Round::where('video_id', $video->id)
								  ->where('roundNumber', '>', $round->roundNumber)
								  ->orderBy('roundNumber', 'asc')
								  ->first();

				if (isset($nextRound->id)) {
					$link = '/manage/detail/'. $video->id .'/'. $nextRound->id;
				} else {
					$link = '/manage/detail/'. $video->id;
				}
			} else {
				$link = '/manage/detail/'. $video->id;
			}
			return $this->redirect($link, 'Round saved.');
		} elseif ($input['type'] == 'addRound') {
			return $this->redirect('/manage/detail/'. $video->id, 'Round added.');
		} elseif ($input['type'] == 'finish') {
			return $this->redirect('/manage/overall-winner', 'Winner added.');
		}
	}

	public function postAddCoop($videoId, $roundId = null)
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			$video = Video::find($videoId);

			if ($roundId != null) {
				$round = Round::find($roundId);
				$round->coopStat->modify($input);
			} else {
				if ($input['type'] == 'nextRound' || $input['type'] == 'addRound' || $input['type'] == 'finish') {
					$video->addRoundCoop($input);
				}
			}
		}
		if ($input['type'] == 'nextRound') {
			if (isset($round)) {
				$nextRound = Round::where('video_id', $video->id)
								  ->where('roundNumber', '>', $round->roundNumber)
								  ->orderBy('roundNumber', 'asc')
								  ->first();

				if (isset($nextRound->id)) {
					$link = '/manage/detail/'. $video->id .'/'. $nextRound->id;
				} else {
					$link = '/manage/detail/'. $video->id;
				}
			} else {
				$link = '/manage/detail/'. $video->id;
			}
			return $this->redirect($link, 'Round saved.');
		} elseif ($input['type'] == 'addRound') {
			return $this->redirect('/manage/detail/'. $video->id, 'Round added.');
		} elseif ($input['type'] == 'finish') {
			return $this->redirect('/manage/overall-winner', 'Winner added.');
		}
	}

	public function getOverallWinner($videoId)
	{
		$video = Video::find($videoId);

		$teams  = Team::orderByNameAsc();
		$actors = Actor::orderByNameAsc();

		if (!$video->checkType('ROUND_BASED_ACTORS')) {
			$actorsMorph = $video->actors->filter(function ($actor) {
				if ($actor->morph_type == 'Actor') {
					return true;
				}
			});
			$teamMorphs = $video->actors->filter(function ($actor) {
				if ($actor->morph_type == 'Team') {
					return true;
				}
			});

			if ($actorsMorph->count() > 0) {
				$actors->whereIn('uniqueId', $actorsMorph->morph_id->toArray());
			}
			if ($teamMorphs->count() > 0) {
				$teams->whereIn('uniqueId', $teamMorphs->morph_id->toArray());
			}
		}

		$actors = $actors->get();
		$teams  = $teams->get();

		$teamsArray   = $this->arrayToSelect($teams, 'id', 'name', 'Select a team');
		$actorsArray = $this->arrayToSelect($actors, 'id', 'name', 'Select an actor');

		$this->setViewData('video', $video);
		$this->setViewData('teams', $teams);
		$this->setViewData('actors', $actors);
		$this->setViewData('teamsArray', $teamsArray);
		$this->setViewData('actorsArray', $actorsArray);
		$this->setViewData('roundId', null);
	}

	public function postOverallWinner($videoId)
	{
		$this->skipView();

		$input = e_array(Input::all());

		if ($input != null) {
			Video::find($videoId)->addOverallWinners($input);

			return $this->redirect('/manage', 'Overall winner added.');
		}
	}

	public function getDeleteRound($roundId)
	{
		$round = Round::find($roundId)->delete();

		$this->redirect('back', 'Round deleted.');
	}

	public function postNewgame()
	{
		$input = e_array(Input::all());

		if ($input != null) {
			$game                = new Game;
			$game->name          = $input['name'];
			$game->keyName       = $input['keyName'];
			$game->commonName    = $input['commonName'];

			// Attempt to save the object
			$this->save($game);

			if (Input::has('challengeFlag')) {
				$newType = new Game_Type;
				$newType->game_id = $game->id;
				$newType->type_id = Type::where('keyName', 'CHALLENGE')->first()->id;

				$this->save($newType);
			}

			// Handle errors
			if ($this->errorCount() > 0) {
				Ajax::addErrors($this->getErrors());
			} else {
				Ajax::setStatus('success')->addData('resource', $game->toArray());
			}

			// Send the response
			return Ajax::sendResponse();
		}
	}

}