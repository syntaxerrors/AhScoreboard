<?php

class Video_Season extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'video_characters';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'series_id'  => 'required|exists:series,uniqueId',
		'season_id' => 'required|exists:seasons,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'series' => array('belongsTo',	'Series',	'foreignKey'	=> 'series_id'),
		'season' => array('belongsTo', 	'Season',	'foreignKey'	=> 'season_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}