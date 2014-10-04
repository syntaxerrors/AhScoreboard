<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">{{ $quote->title }}</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div style="width: 100%; height: 420px;" class="center-block" id="youtube">
							{{ HTML::iframe('http://www.youtube.com/embed/' . $quote->video->link .'?start='. $quote->youtubeTimestamp .'&end='. $quote->endTime, array(
								'class'			=> 'youtube-player',
								'type'			=> 'text/html',
								'height'		=> '420',
								'width'			=> '100%',
								'frameborder'	=> 0
							)) }}
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12">
								<h4>Transcript</h4>
								{{ nl2br($quote->quotes) }}
							</div>
						</div>
						<hr />
						<div class="row">
							<div class="col-md-12">
								<h4>Actors in this quote</h4>
								{{ implode('<br />', $quote->actors->morph->link->toArray()) }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>