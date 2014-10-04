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

	public function typeLabel()
	{
		$labels = [];

		if ($this->resource->series->checkType('NON_GAME')) {
			$labels[] = '<span class="label label-danger">Non Game</span>';
		}
		if ($this->resource->checkType('WAVES')) {
			$labels[] = '<span class="label label-success">Waves</span>';
		}
		if ($this->resource->checkType('CO_OP')) {
			$labels[] = '<span class="label label-info">Co-op</span>';
		}
		if ($this->resource->checkType('FOR_THE_TOWER')) {
			$labels[] = '<span class="label label-warning">For the Tower</span>';
		}
		if ($this->resource->checkType('ROUND_BASED_ACTORS')) {
			$labels[] = '<span class="label" style="background-color: #de5bdb;">RBA</span>';
		}
		if ($this->resource->checkType('ROUND_BASED_GAMES')) {
			$labels[] = '<span class="label" style="background-color: #a25bde;">RBG</span>';
		}
		if ($this->resource->checkType('OVERALL_WINNER')) {
			$labels[] = '<span class="label label-info">Overall</span>';
		}

		if (count($labels) > 0) {
			return implode($labels);
		} else {
			return '&nbsp;';
		}
	}
}