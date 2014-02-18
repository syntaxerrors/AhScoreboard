<?php

class Game extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'games';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

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
		'videos' => array('hasMany', 'Video_Game',		'foreignKey' => 'game_id'),
		'rounds' => array('hasMany', 'Round_Game',		'foreignKey' => 'game_id'),
		'types'  => array('hasMany', 'Game_Type',		'foreignKey' => 'game_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getLabelNameAttribute()
	{
		if ($this->checkType('CHALLENGE')) {
			return '<span class="label label-success">Challenge</span>&nbsp;'. $this->name;
		}

		return $this->name;
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}