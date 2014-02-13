<?php

class Team extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'teams';
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
		'name'    => 'required|max:200',
		'keyName' => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'actors' => array('belongsToMany', 'Actor',		'table' => 'team_actors', 'orderBy' => array('firstName', 'asc')),
		'videos' => array('belongsToMany', 'Video',		'table' => 'video_actors'),
		'rounds' => array('belongsToMany', 'Round',		'table' => 'round_actors'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getLinkAttribute()
	{
		return HTML::link('/team/view/'. $this->id, $this->name);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}