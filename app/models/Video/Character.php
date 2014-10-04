<?php

class Video_Character extends BaseModel
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
		'video_id'     => 'required|exists:videos,uniqueId',
		'character_id' => 'required|exists:characters,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'video'     => array('belongsTo',	'Video',	'foreignKey'	=> 'video_id'),
		'character' => array('belongsTo', 	'Character','foreignKey'	=> 'character_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}