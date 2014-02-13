<?php

class Series extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'series';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/**
	 * Soft Delete users instead of completely removing them
	 *
	 * @var bool $softDelete Whether to delete or soft delete
	 */
	protected $softDelete = true;

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
		'videos' => array('hasMany', 'Video',		'foreignKey' => 'series_id', 'orderBy' => array('date', 'desc')),
		'types'  => array('hasMany', 'Series_Type',	'foreignKey' => 'series_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getLinkAttribute()
	{
		return HTML::link('/series/view/'. $this->id, $this->name);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}