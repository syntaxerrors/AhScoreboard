<?php

class Round_Game extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'round_games';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'round_id' => 'required|exists:rounds,uniqueId',
		'game_id'  => 'required|exists:games,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'round' => array('belongsTo', 'Round', 'foreignKey' => 'round_id'),
		'game'  => array('belongsTo', 'Game',  'foreignKey' => 'game_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}