<?php

class Type extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'types';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'name'    => 'required|max:200',
		'keyName' => 'required|max:200',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'series'  => array('belongsToMany', 'Series',		'table' => 'series_types'),
		'videos'  => array('belongsToMany', 'Video',		'table' => 'video_types'),
		'actors'  => array('belongsToMany', 'Actor',		'table' => 'member_types'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}