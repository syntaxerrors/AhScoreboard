<?php

class Game_Type extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'game_types';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'game_id' => 'required|exists:games,uniqueId',
		'type_id'  => 'required|exists:types,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'game' => array('belongsTo', 'Game', 'foreignKey' => 'game_id'),
		'type' => array('belongsTo', 'Type', 'foreignKey' => 'type_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}