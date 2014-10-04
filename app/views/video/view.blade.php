<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">{{ $video->title }}</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div style="height: 420px;" class="center-block" id="youtube">
							{{ HTML::iframe('http://www.youtube.com/embed/' . $video->link, array(
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
							@if ($video->series->checkType('NON_GAME'))
								<div class="col-md-6">
									<h4>Actors</h4>
									{{ implode('<br />', $video->actors->morph->link->toArray()) }}
								</div>
								<div class="col-md-6">
									<h4>Quotes</h4>
									{{ implode('<br />', $video->quotes->link->toArray()) }}
								</div>
							@else
								<div class="col-md-3">
									<h4>Actors</h4>
									{{ implode('<br />', $video->actors->morph->link->toArray()) }}
								</div>
								<div class="col-md-3">
									@if ($video->checkType('CO_OP'))
										<h4>Co-Op success per round</h4>
										{{ implode('<br />', $video->rounds->coopStat->display->toArray()) }}
									@elseif ($video->checkType('WAVES'))
										<h4>Highest wave per round</h4>
										{{ implode('<br />', $video->rounds->wave->highestWave->toArray()) }}
									@else
										<h4>Winners</h4>
										{{ implode('<br />', $video->winners->morph->link->toArray()) }}
									@endif
								</div>
								<div class="col-md-3">
									<h4>Games</h4>
									{{ implode('<br />', $video->games->game->name->toArray()) }}
								</div>
								<div class="col-md-3">
									<h4>Quotes</h4>
									{{ implode('<br />', $video->quotes->link->toArray()) }}
								</div>
							@endif
						</div>
						<div class="row">
							<div class="col-md-12">
								<div id="quote"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				@include('video.components.usermarks', array('videoId' => $video->id))
			</div>
		</div>
		@if ($video->parent != null)
			<?php $parent = $video->parent; ?>
			<div class="panel panel-default">
				<div class="panel-heading">Previous Videos in this Series</div>
				<div class="panel-body">
					@while ($parent != null)
						{{ HTML::link('/video/view/'. $parent->id, $parent->title) }}<br />
						<?php $parent = $parent->parent; ?>
					@endwhile
				</div>
			</div>
		@endif
		@if ($video->child != null)
			<div class="panel panel-default">
				<div class="panel-heading">Next Videos in this Series</div>
				<div class="panel-body">
					<?php $child = $video->child; ?>
					@while ($child != null)
						{{ HTML::link('/video/view/'. $child->id, $child->title) }}<br />
						<?php $child = $child->child; ?>
					@endwhile
				</div>
			</div>
		@endif
	</div>
</div>

@section('js')
	<script>
		function youTubeTime(id, timestamp, end, quoteId) {
			$('#youtube').empty().load('/youtube/'+ id +'/'+ timestamp +'/'+ end);
			$('#quote').empty().load('/video/quote/'+ quoteId);
		}
	</script>
@endsection