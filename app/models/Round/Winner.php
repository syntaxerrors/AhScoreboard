<?php

class Round_Winner extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'round_winners';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'round_id'   => 'required|exists:rounds,uniqueId',
		'morph_id'   => 'required',
		'morph_type' => 'required',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'round' => array('belongsTo', 'Round', 'foreignKey' => 'round_id'),
		'morph' => array('morphTo'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}