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
		'morph_id'       => 'required',
		'morph_type'     => 'required',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'quote' => array('belongsTo',	'Video_Quote',	'foreignKey' => 'video_quote_id'),
		'morph' => array('morphTo'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}