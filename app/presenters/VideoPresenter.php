<?php

class VideoPresenter extends Syntax\Core\CorePresenter {

	public function image()
	{
		if (File::exists(public_path() .'/img/youtube/'. $this->resource->id .'.jpg')) {
			return '/img/youtube/'. $this->resource->id .'.jpg';
		}
	}

	public function youtubeLink()
	{
		return 'http://www.youtube.com/watch?v='. $this->resource->link;
	}
}