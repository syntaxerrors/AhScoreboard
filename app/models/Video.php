<?php

class Video extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'videos';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/**
	 * Soft Delete users instead of completely removing them
	 *
	 * @var bool $softDelete Whether to delete or soft delete
	 */
	protected $softDelete = true;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'series_id' => 'required|exists:series,uniqueId',
		'title'     => 'required|max:200',
		'link'      => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'series'  => array('belongsTo', 'Series',		'foreignKey' => 'series_id'),
		'types'   => array('hasMany', 'Video_Type',		'foreignKey' => 'video_id'),
		'games'   => array('hasMany', 'Video_Game',		'foreignKey' => 'video_id'),
		'actors'  => array('hasMany', 'Video_Actor',	'foreignKey' => 'video_id'),
		'winners' => array('hasMany', 'Video_Winner',	'foreignKey' => 'video_id'),
		'quotes'  => array('hasMany', 'Video_Quote',	'foreignKey' => 'video_id'),
		'rounds'  => array('hasMany', 'Round',			'foreignKey' => 'video_id', 'orderBy' => array('roundNumber', 'asc')),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	public function previousRounds($roundId)
	{
		$round = Round::find($roundId);
		return Round::where('video_id', $this->id)->where('roundNumber', '<', $round->roundNumber)->orderBy('roundNumber', 'asc')->get();
	}

	public function updateActors()
	{
		if ($this->checkType('ROUND_BASED_ACTORS')) {
			foreach ($this->actors as $actor) {
				// $actor->delete();
			}

			foreach ($this->rounds as $round) {
				foreach ($round->actors as $actor) {
					// Check if the video has this actor already
					$existingActor = $this->actors->filter(function ($videoActor) use ($actor) {
						if ($videoActor->morph_type == $actor->morph_type && $videoActor->morph_id == $actor->morph_id) return true;
					});
					ppd($existingActor);

					if ($existingActor == null) {
						$newVideoActor             = new Video_Actor;
						$newVideoActor->video_id   = $this->id;
						$newVideoActor->morph_id   = $actor->morph_id;
						$newVideoActor->morph_type = $actor->morph_type;

						$newVideoActor->save();
					}
				}
			}
		}
	}

	public function updateGames()
	{
		if ($this->checkType('ROUND_BASED_GAMES')) {
			foreach ($this->games as $game) {
				// $actor->delete();
			}

			foreach ($this->rounds as $round) {
				// Check if the video has this game already
				$existingGame = $this->games->filter(function ($videoGame) use ($round) {
					if ($videoGame->game_id == $round->game->game_id) return true;
				});
				ppd($existingGame);

				if ($existingGame == null) {
					$newVideoGame           = new Video_Game;
					$newVideoGame->video_id = $this->id;
					$newVideoGame->game_id  = $round->game->game_id;

					$newVideoGame->save();
				}
			}
		}
	}

	public function addRound($input)
	{
		// Add the round
		$round              = new Round;
		$round->video_id    = $this->id;
		$round->roundNumber = $this->rounds->count() > 0 ?$this->rounds->last()->roundNumber + 1 : 1;

		$round->save();

		// Handle the actors
		if (isset($input['actors']) && count($input['actors']) > 0) {
			foreach ($input['actors'] as $type => $actors) {
				foreach ($actors as $actorId) {
					$newRoundActor             = new Round_Actor;
					$newRoundActor->round_id   = $round->id;
					$newRoundActor->morph_type = ucwords($type);
					$newRoundActor->morph_id   = $actorId;

					$newRoundActor->save();

					// Make sure the video knows about the actor
					$videoActors = $this->actors->filter(function ($actor) use($type, $actorId) {
						if ($actor->morph_type == ucwords($type) && $actor->morph_id == $actorId) return true;
					});

					if (count($videoActors) == 0) {
						$newVideoActor             = new Video_Actor;
						$newVideoActor->video_id   = $this->id;
						$newVideoActor->morph_id   = $actorId;
						$newVideoActor->morph_type = ucwords($type);

						$newVideoActor->save();
					}
				}
			}
		}

		// Handle the winners
		if (isset($input['winners']) && count($input['winners']) > 0) {
			foreach ($input['winners'] as $type => $winners) {
				if (is_array($winners)) {
					foreach ($winners as $actorId) {
						if ($actorId == '0') continue;

						$newRoundWinner             = new Round_Winner;
						$newRoundWinner->round_id   = $round->id;
						$newRoundWinner->morph_type = ucwords($type);
						$newRoundWinner->morph_id   = $actorId;

						$newRoundWinner->save();
					}
				} else {
					if ($winners == '0') continue;
					$newRoundWinner             = new Round_Winner;
					$newRoundWinner->round_id   = $round->id;
					$newRoundWinner->morph_type = ucwords($type);
					$newRoundWinner->morph_id   = $winners;

					$newRoundWinner->save();
				}
			}
		}

		// Handle the game
		if (isset($input['game_id'])) {
			$newGame           = new Round_Game;
			$newGame->round_id = $round->id;
			$newGame->game_id  = $input['game_id'];

			$newGame->save();

			// Make sure the video knows about the game
			$videoGames = $this->video->games->game_id->toArray();

			if (!in_array($input['game_id'], $videoGames)) {
				$newVideoGame           = new Video_Game;
				$newVideoGame->video_id = $this->video_id;
				$newVideoGame->game_id  = $input['game_id'];

				$newVideoGame->save();
			}
		}
	}
}