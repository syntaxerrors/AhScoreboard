<?php

class Actor_Type extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'actor_types';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'actor_id' => 'required|exists:actors,uniqueId',
		'type_id'  => 'required|exists:types,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'actor' => array('belongsTo', 'Actor', 'foreignKey' => 'actor_id'),
		'type' => array('belongsTo', 'Type',   'foreignKey' => 'type_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}