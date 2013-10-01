<?php

class Forum_Board_Type extends Forum
{
	protected $table = 'forum_board_types';

	public function boards()
	{
		return $this->hasMany('Forum_Board', 'forum_board_type_id');
	}

}