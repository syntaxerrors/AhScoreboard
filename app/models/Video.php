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
		'series'     => array('belongsTo', 'Series',		'foreignKey' => 'series_id'),
		'parent'     => array('belongsTo', 'Video',			'foreignKey' => 'parentId'),
		'child'      => array('hasOne',  'Video',			'foreignKey' => 'parentId'),
		'games'      => array('hasMany', 'Video_Game',		'foreignKey' => 'video_id'),
		'actors'     => array('hasMany', 'Video_Actor',		'foreignKey' => 'video_id'),
		'winners'    => array('hasMany', 'Video_Winner',	'foreignKey' => 'video_id'),
		'quotes'     => array('hasMany', 'Video_Quote',		'foreignKey' => 'video_id', 'orderBy' => array('created_at', 'desc')),
		'rounds'     => array('hasMany', 'Round',			'foreignKey' => 'video_id', 'orderBy' => array('roundNumber', 'asc')),
		'characters' => array('belongsToMany', 'Character',	'table' => 'video_characters'),
		'types'      => array('belongsToMany',	'Type',			'table' => 'video_types',  'foreignKey' => 'video_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getCoopPercentageAttribute()
	{
		$winRounds = clone $this->rounds;
		$wins = $winRounds->coopStat->where('winFlag', 1)->count();
		return percent($wins, $this->rounds->count()) .'%';
	}

	public function getLinkToAttribute()
	{
		return HTML::link('/video/view/'. $this->id, $this->title);
	}

	public function getImageAttribute()
	{
		if (File::exists(public_path() .'/img/youtube/'. $this->id .'.jpg')) {
			return '/img/youtube/'. $this->id .'.jpg';
		}
	}

	public function getYoutubeLinkAttribute()
	{
		return 'http://www.youtube.com/watch?v='. $this->link;
	}

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
			$actors = clone $this->actors;
			foreach ($actors as $actor) {
				$actor->delete();
				$actors = new \Utility_Collection();
			}

			$newActors = array();
			$newTeams  = array();

			foreach ($this->rounds as $round) {
				foreach ($round->actors as $actor) {
					if ($actor->morph_type == 'Actor' && in_array($actor->morph_id, $newActors)) continue;
					if ($actor->morph_type == 'Team' && in_array($actor->morph_id, $newTeams)) continue;

					// Check if the video has this actor already
					$videoActors = clone $actors;
					$existingActor = $videoActors->filter(function ($videoActor) use ($actor) {
						if ($videoActor->morph_type == $actor->morph_type && $videoActor->morph_id == $actor->morph_id) return true;
					});

					if ($existingActor->count() == 0) {
						$newVideoActor             = new Video_Actor;
						$newVideoActor->video_id   = $this->id;
						$newVideoActor->morph_id   = $actor->morph_id;
						$newVideoActor->morph_type = $actor->morph_type;

						$newVideoActor->save();

						if ($actor->morph_type == 'Actor') {
							$newActors[] = $actor->morph_id;
						} else {
							$newTeams[] = $actor->morph_id;
						}
					}
				}
			}

			return true;
		}
	}

	public function updateGames()
	{
		if ($this->checkType('ROUND_BASED_GAMES')) {
			$games = clone $this->games;
			foreach ($games as $game) {
				$game->delete();
				$games = new \Utility_Collection();
			}

			$newGames = array();

			foreach ($this->rounds as $round) {
				if (in_array($round->game->game_id, $newGames)) continue;

				// Check if the video has this game already
				$existingGame = $games->filter(function ($videoGame) use ($round) {
					if ($videoGame->game_id == $round->game->game_id) return true;
				});

				if ($existingGame->count() == 0) {
					$newVideoGame           = new Video_Game;
					$newVideoGame->video_id = $this->id;
					$newVideoGame->game_id  = $round->game->game_id;

					$newVideoGame->save();

					$newGames[] = $round->game->game_id;
				}
			}
		}
	}

	public function addWinners($input)
	{
		if (!$this->checkType('OVERALL_WINNER')) {
			$this->createWinners($input);
		}
	}

	public function addOverallWinners($input)
	{
		$this->winners->delete();
		$this->createWinners($input);
	}

	protected function createWinners($input)
	{
		if (isset($input['winners'])) {
			foreach ($input['winners'] as $type => $winners) {
				if (is_array($winners)) {
					foreach ($winners as $actorId) {
						if ($actorId == '0') continue;

						$videoWinner             = new Video_Winner;
						$videoWinner->video_id   = $this->id;
						$videoWinner->morph_type = ucwords($type);
						$videoWinner->morph_id   = $actorId;

						$videoWinner->save();
					}
				} else {
					if ($winners == '0') continue;
					$videoWinner             = new Video_Winner;
					$videoWinner->video_id   = $this->id;
					$videoWinner->morph_type = ucwords($type);
					$videoWinner->morph_id   = $winners;

					$videoWinner->save();
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
			$videoGames = $this->games->game_id->toArray();

			if (!in_array($input['game_id'], $videoGames)) {
				$newVideoGame           = new Video_Game;
				$newVideoGame->video_id = $this->id;
				$newVideoGame->game_id  = $input['game_id'];

				$newVideoGame->save();
			}
		}

		$this->addWinners($input);

		return $round;
	}

	public function addRoundWave($input)
	{
		$round = $this->addRound($input);

		$round->addWave($input);
	}

	public function addRoundCoop($input)
	{
		$round = $this->addRound($input);

		$round->addCoop($input);
	}
}