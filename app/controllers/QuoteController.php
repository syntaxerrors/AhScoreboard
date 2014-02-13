<?php

class QuoteController extends BaseController {

	public function getView($quoteId)
	{
		$quote = Video_Quote::find($quoteId);

		$this->setViewData('quote', $quote);
	}

}