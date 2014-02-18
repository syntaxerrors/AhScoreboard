<?php

class Round_Coop extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'round_coops';

	/********************************************************************
	 * Aware validation rules
	 *******************************************************************/
	public static $rules = array(
		'round_id'   => 'required|exists:rounds,uniqueId',
	);

	/********************************************************************
	 * Relationships
	 *******************************************************************/
	public static $relationsData = array(
		'round' => array('belongsTo', 'Round', 'foreignKey' => 'round_id'),
	);

	/********************************************************************
	 * Getter and Setter methods
	 *******************************************************************/
	public function getStatusAttribute()
	{
		if ($this->lossFlag == 1) {
			return 'LOSS';
		}

		return 'WON';
	}

	public function getDisplayAttribute()
	{
		if ($this->lossFlag == 1) {
			return '<span class="text-danger">Lost</span>';
		}

		return '<span class="text-success">Won</span>';
	}

	public function getDisplayCleanAttribute()
	{
		if ($this->lossFlag == 1) {
			return 'Lost';
		}

		return 'Won';
	}

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	public function modify($input)
	{
		if (isset($input['status'])) {
			if ($input['status'] == 'WON') {
				$this->lossFlag = 0;
				$this->winFlag  = 1;
			} else {
				$this->lossFlag = 1;
				$this->winFlag  = 0;
			}

			$this->save();
		}
	}
}