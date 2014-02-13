<hr />
<div class="row">
	<div class="col-md-8">
		<h4>Transcript</h4>
		{{ nl2br($quote->quotes) }}
	</div>
	<div class="col-md-4">
		<h4>Actors in this quote</h4>
		{{ implode('<br />', $quote->actors->actor->link->toArray()) }}
	</div>
</div>