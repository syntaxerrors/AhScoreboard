<?php

class Round_Wave extends BaseModel
{
	/********************************************************************
	 * Declarations
	 *******************************************************************/
	/**
	 * Table declaration
	 *
	 * @var string $table The table this model uses
	 */
	protected $table      = 'round_waves';

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

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	public function modify($input)
	{
		if (isset($input['highestWave'])) {
			$this->highestWave = $input['highestWave'];

			$this->save();
		}
	}
}