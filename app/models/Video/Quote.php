<?php

class Video_Quote extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'video_quotes';
	protected $primaryKey = 'uniqueId';
	public $incrementing  = false;

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'video_id'   => 'required|exists:videos,uniqueId',
		'title'      => 'required',
		'timeStart'  => 'required'
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'video'  => array('belongsTo',	'Video',				'foreignKey' => 'video_id'),
		'actors' => array('hasMany',	'Video_Quote_Actor',	'foreignKey' => 'video_quote_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getYoutubeTimestampAttribute()
	{
		$bits = explode(':', $this->timeStart);
		$seconds = ($bits[0] * 60) + $bits[1];

		return $seconds;
	}
	public function getEndTimeAttribute()
	{
		return $this->youtubeTimestamp + $this->timeEnd;
	}
	public function getLinkAttribute()
	{
		return HTML::link('/quotes/view/'. $this->id, $this->title) .'&nbsp;<a href="javascript:void(0);" onClick="youTubeTime(\''. $this->video->link .'\',\''. $this->youtubeTimestamp .'\', \''. $this->endTime .'\', \''. $this->id .'\');"><i class="fa fa-youtube"></i></a>';
	}
	public function getLinkOnlyAttribute()
	{
		return HTML::link('/quotes/view/'. $this->id, $this->title);
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
}