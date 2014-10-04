<?php

class Character extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'characters';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'     => 'required|max:200',
		'keyName'  => 'required|max:200',
		'actor_id' => 'required|exists:actors,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'videos'  => array('belongsToMany', 'Video',		'table'			=> 'video_characters', 'orderBy' => array('date', 'desc')),
		'actor'   => array('belongsTo', 	'Actor',		'foreignKey'	=> 'actor_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getActorNameAttribute()
	{
		return $this->actor->link;
	}

	public function getFirstNameLinkAttribute()
	{
		return HTML::link('/characters/view/'. $this->id, $this->name);
	}

	public function getLinkAttribute()
	{
		return HTML::link('/characters/view/'. $this->id, $this->name);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}