<?php

class Round extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'rounds';
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
		'video_id' => 'required|exists:videos,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'video'     => array('belongsTo',	'Video',		'foreignKey' => 'video_id'),
		'game'      => array('hasOne',		'Round_Game',	'foreignKey' => 'round_id'),
		'actors'    => array('hasMany',		'Round_Actor',	'foreignKey' => 'round_id'),
		'winners'   => array('hasMany',		'Round_Winner',	'foreignKey' => 'round_id'),
		'coopStat'  => array('hasOne',		'Round_Coop',	'foreignKey' => 'round_id'),
		'wave'      => array('hasOne',		'Round_Wave',	'foreignKey' => 'round_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	public function modify($input)
	{
		// Handle the actors
		if (isset($input['actors']) && count($input['actors']) > 0) {
			foreach ($this->actors as $actor) {
				$actor->delete();
			}

			foreach ($input['actors'] as $type => $actors) {
				foreach ($actors as $actorId) {
					$newRoundActor             = new Round_Actor;
					$newRoundActor->round_id   = $this->id;
					$newRoundActor->morph_type = ucwords($type);
					$newRoundActor->morph_id   = $actorId;

					$newRoundActor->save();
				}
			}

			// Make sure the video has the correct list of actors
			$this->video->updateActors();
		}

		// Handle the winners
		if (isset($input['winners']) && count($input['winners']) > 0) {
			foreach ($this->winners as $winner) {
				$winner->delete();
			}

			foreach ($input['winners'] as $type => $winners) {
				if (is_array($winners)) {
					foreach ($winners as $actorId) {
						if ($actorId == '0') continue;

						$newRoundWinner             = new Round_Winner;
						$newRoundWinner->round_id   = $this->id;
						$newRoundWinner->morph_type = ucwords($type);
						$newRoundWinner->morph_id   = $actorId;

						$newRoundWinner->save();
					}
				} else {
					if ($winners == '0') continue;
					$newRoundWinner             = new Round_Winner;
					$newRoundWinner->round_id   = $this->id;
					$newRoundWinner->morph_type = ucwords($type);
					$newRoundWinner->morph_id   = $winners;

					$newRoundWinner->save();
				}
			}
		}

		// Handle the game
		if (isset($input['game_id'])) {
			$this->game->delete();

			$newGame           = new Round_Game;
			$newGame->round_id = $this->id;
			$newGame->game_id  = $input['game_id'];

			$newGame->save();

			// Make sure the video knows about the game
			$this->video->updateGames();
		}
	}

	public function addWave($input)
	{
		if (isset($input['highestWave'])) {
			$newWave              = new Round_Wave;
			$newWave->round_id    = $this->id;
			$newWave->highestWave = $input['highestWave'];

			$newWave->save();
		}
	}

	public function addCoop($input)
	{
		if (isset($input['status'])) {
			$newCoop              = new Round_Coop;
			$newCoop->round_id    = $this->id;
			if ($input['status'] == 'WON') {
				$newCoop->lossFlag = 0;
				$newCoop->winFlag  = 1;
			} else {
				$newCoop->lossFlag = 1;
				$newCoop->winFlag  = 0;
			}

			$newCoop->save();
		}
	}
}