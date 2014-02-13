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
		'teams'  => array('belongsToMany',	'Team',			'table' => 'team_actors'),
		'videos' => array('belongsToMany',	'Video',		'table' => 'video_actors'),
		'rounds' => array('belongsToMany',	'Round',		'table' => 'round_actors'),
		'types'  => array('hasMany',		'Actor_Type',	'foreignKey' => 'actor_id'),
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

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	public function scopeOrderByNameAsc($query)
	{
		return $query->orderBy('firstName', 'asc');
	}
}