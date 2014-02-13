<?php

class Series_Type extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'series_types';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'series_id' => 'required|exists:series,uniqueId',
		'type_id'  => 'required|exists:types,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'series' => array('belongsTo', 'Series', 'foreignKey' => 'series_id'),
		'type'   => array('belongsTo', 'Type',   'foreignKey' => 'type_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}