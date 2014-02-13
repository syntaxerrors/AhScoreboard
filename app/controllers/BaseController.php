<?php

class BaseController extends MenuController {

	/********************************************************************
	 * Extra Methods
	 *******************************************************************/
	/**
	 * This method will get the youtube video ID from a url
	*/
	public function getYoutubeId($url)
	{
		$link = str_replace(array('http://www.youtube.com/watch?v=', 'https://www.youtube.com/watch?v='), '', $url);

		if (strpos($link, '&') !== false) {
			$link = substr($link, 0, strpos($link, '&'));
		}

		return $link;
	}

	/**
	 * This method will get a user's timeline from twitter.
	 *
	 * @requires composer "thujohn/twitter": "dev-master"
	*/
	public function getUserTimeLine($handle, $count, $format = 'object')
	{
		return Twitter::getUserTimeline(array('screen_name' => $handle, 'format' => $format, 'count' => $count, 'exclude_replies' => true));
	}
}