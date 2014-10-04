<?php

class Actor extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'actors';
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
		'firstName' => 'required|max:200',
		'keyName'   => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'teams'      => array('belongsToMany',	'Team',			'table' => 'team_actors'),
		'videos'     => array('belongsToMany',	'Video',		'table' => 'video_actors', 'foreignKey' => 'morph_id', 'orderBy' => array('date', 'desc')),
		'rounds'     => array('belongsToMany',	'Round',		'table' => 'round_actors', 'foreignKey' => 'morph_id'),
		'types'      => array('belongsToMany',	'Type',			'table' => 'actor_types',  'foreignKey' => 'actor_id'),
		'characters' => array('hasMany',		'Character',	'foreignKey' => 'actor_id'),
		'wins'       => array('morphMany',		'Video_Winner',	'name'  => 'morph'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getFullNameAttribute()
	{
		return $this->firstName .' '. $this->lastName;
	}
	public function getNameAttribute()
	{
		return $this->firstName .' '. $this->lastName;
	}
	public function getLinkAttribute()
	{
		return HTML::link('/actors/view/'. $this->id, $this->fullName);
	}
	public function getFirstNameLinkAttribute()
	{
		return HTML::link('/actors/view/'. $this->id, $this->firstName);
	}

	public function getLatestVideoAttribute()
	{
		$lastVideoAsActor = $this->videos()->orderBy('date', 'desc')->orderBy('uniqueId', 'asc')->first();
		if ($this->characters->count() > 0) {
			$lastVideoAsCharacter = $this->characters->videos->first();

			if ($lastVideoAsCharacter!= null && ($lastVideoAsActor == null || $lastVideoAsCharacter->date > $lastVideoAsActor->date)) {
				return $lastVideoAsCharacter->linkTo .' as '. $lastVideoAsCharacter->characters->where('actor_id', $this->id)->first()->link;
			}
		}

		if ($lastVideoAsActor != null) {
			return $lastVideoAsActor->linkTo;
		}

		return null;
	}

	public function getVideosCountAttribute()
	{
		return $this->videos->count() + $this->characters->videos->count();
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	public function scopeOrderByNameAsc($query)
	{
		return $query->orderBy('firstName', 'asc')->orderBy('lastName', 'asc');
	}
}