<?php

class Team_Actor extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'team_actors';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'team_id'  => 'required|exists:teams,uniqueId',
		'actor_id' => 'required|exists:actors,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'team'  => array('belongsTo', 'Team',	'foreignKey' => 'team_id'),
		'actor' => array('belongsTo', 'Actor',	'foreignKey' => 'actor_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getTeamNameAttribute()
	{
		return $this->team->name;
	}

	public function getMemberNameAttribute()
	{
		return $this->member->name;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}