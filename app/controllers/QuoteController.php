<?php

class QuoteController extends BaseController {

	public function getIndex()
	{
		$videos = Video::has('quotes')->orderBy('date', 'desc')->paginate(20);

		$this->setViewData('videos', $videos);
	}

	public function getView($quoteId)
	{
		$quote = Video_Quote::find($quoteId);

		$this->setViewData('quote', $quote);
	}

}