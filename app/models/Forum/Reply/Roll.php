<?php

class Forum_Reply_Roll extends Forum
{
	protected $table = 'forum_reply_rolls';

	public function reply()
	{
		return $this->belongsTo('Forum_Reply', 'forum_reply_id');
	}

}