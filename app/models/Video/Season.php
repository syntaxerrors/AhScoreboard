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
		'video_id'  => 'required|exists:videos,uniqueId',
		'season_id' => 'required|exists:seasons,id',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'video'  => array('belongsTo',	'Video',	'foreignKey'	=> 'video_id'),
		'season' => array('belongsTo', 	'Season',	'foreignKey'	=> 'season_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}