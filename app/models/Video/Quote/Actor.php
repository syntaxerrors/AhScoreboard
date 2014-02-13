<?php

class Video_Quote_Actor extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'video_quote_actors';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'video_quote_id' => 'required|exists:video_quotes,uniqueId',
		'actor_id'       => 'required|exists:actors,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'quote'  => array('belongsTo',	'Video_Quote',	'foreignKey' => 'video_quote_id'),
		'actor' => array('belongsTo',	'Actor',		'foreignKey' => 'actor_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}