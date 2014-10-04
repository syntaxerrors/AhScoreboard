<!-- <span class="fa-stack fa-3x">
  <i class="fa fa-circle fa-stack-2x" style="color: #000;"></i>
  <i class="fa fa-circle-o fa-stack-2x text-primary"></i>
  <i class="fa fa-star fa-stack-1x text-primary"></i>
  <i class="fa fa-filter fa-flip-vertical fa-stack-1x" style="color: #000;"></i>
</span> -->
<div class="row" id="ajaxContent">
	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">Links</div>
			<div class="list-glow-sm">
				<ul class="list-glow-group no-header">
					<li>
						<div class="list-glow-group-item">
							<div class="col-md-12">{{ HTML::link('http://roosterteeth.com', 'RoosterTeeth.com', array('target' => '_blank')) }}</div>
						</div>
					</li>
					<li>
						<div class="list-glow-group-item">
							<div class="col-md-12">{{ HTML::link('http://achievementhunter.com/', 'AchievementHunter.com', array('target' => '_blank')) }}</div>
						</div>
					</li>
					<li>
						<div class="list-glow-group-item">
							<div class="col-md-12">{{ HTML::link('http://www.youtube.com/user/RoosterTeeth', 'RoosterTeeth @ YouTube', array('target' => '_blank')) }}</div>
						</div>
					</li>
					<li>
						<div class="list-glow-group-item">
							<div class="col-md-12">{{ HTML::link('http://www.youtube.com/user/LetsPlay', 'Lets Play @ YouTube', array('target' => '_blank')) }}</div>
						</div>
					</li>
					<li>
						<div class="list-glow-group-item">
							<div class="col-md-12">{{ HTML::link('http://www.youtube.com/user/know', 'The Know @ YouTube', array('target' => '_blank')) }}</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		@foreach ($videos as $video)
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="media">
						<a class="pull-left" href="{{ $video->youtubeLink }}" target="_blank">
							<img class="media-object" src="{{ $video->image }}" style="width: 200px;" />
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="{{ $video->youtubeLink }}" target="_blank">{{ $video->title }}</a>
								@if (isset($activeUser) && $activeUser != null)
									<span class="pull-right">
										@include('video.components.usermarks', array('videoId' => $video->id))
									</span>
								@endif
							</h4>
							<?php
								$actors = clone $video->actors;
								$actorsArray = $actors->filter(function ($actor) {
									if ($actor->morph_type == 'Actor') return true;
								})->morph->firstNameLink->toArray();

								$teams = clone $video->actors;
								$teamsArray = $actors->filter(function ($actor) {
									if ($actor->morph_type == 'Team') return true;
								})->morph->link->toArray();
							?>
							<table class="table table-condensed">
								<tbody>
									@if (count($actorsArray) > 0)
										<tr>
											<td style="width: 75px;"><strong>Actors</strong></td>
											<td>{{ implode(', ', $actorsArray) }}</td>
										</tr>
									@endif
									@if (count($teamsArray) > 0)
										<tr>
											<td style="width: 75px;"><strong>Teams</strong></td>
											<td>{{ implode(', ', $teamsArray) }}</td>
										</tr>
									@endif
									@if ($video->games->count() > 0)
										<tr>
											<td style="width: 75px;"><strong>Games</strong></td>
											<td>{{ implode(', ', $video->games->game->labelName->toArray()) }}</td>
										</tr>
									@endif
									@if ($video->quotes->count() > 0)
										<tr>
											<td style="width: 75px;"><strong>Quotes</strong></td>
											<td>{{ implode(', ', $video->quotes->linkOnly->toArray()) }}</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="panel-footer" style="color: #ffffff;">
					<div class="row">
						<div class="col-md-5">
							<span class="pull-left">
								{{ date('F jS, Y', strtotime($video->date)) }}
							</span>
						</div>
						<div class="col-md-2">
							@if ($video->rounds->winners->count() > 0 || $video->rounds->waves->count() > 0)
								<div class="text-center">
									<a href="javascript:void(0);" role="button" data-toggle="modal" data-remote="/scores/{{ $video->id }}" class="confirm-spoiler">Score</a>
								</div>
							@endif
						</div>
						<div class="col-md-5">
							<span class="pull-right">
								<span class="fa-stack">
									<i class="fa fa-circle fa-stack-2x text-primary"></i>
									{{ HTML::linkIcon('/video/view/'. $video->id, 'fa fa-expand fa-stack-1x', null, array('title' => 'View in site', 'target' => '_blank', 'style' => 'color: #000;')) }}
								</span>
								<span class="fa-stack">
									<i class="fa fa-circle fa-stack-2x text-primary"></i>
									{{ HTML::linkIcon($video->youtubeLink, 'fa fa-youtube fa-lg fa-stack-1x', null, array('title' => 'View on YouTube', 'target' => '_blank', 'style' => 'color: #000;')) }}
								</span>
							</span>
						</div>
					</div>
				</div>
			</div>
		@endforeach
		<div class="text-center">
			{{ $videos->links() }}
		</div>
	</div>
</div>
<script>
	@section('onReadyJs')
		verifyContinue = 0;
		$("a.confirm-spoiler").click(function(e) {
			e.preventDefault();
			var location = $(this).attr('data-remote');
			if (verifyContinue == 0) {
				bootbox.dialog({
					message: "Are you sure you want to continue?<br />(This will contain spoilers)",
					buttons: {
						danger: {
							label: "No",
							className: "btn-primary"
						},
						success: {
							label: "Yes",
							className: "btn-primary",
							callback: function() {
								verifyContinue = 1;
								getModal(location);
							}
						},
					}
				});
			} else {
				getModal(location);
			}
		});
		$('body').on('hidden.bs.modal', '#modal', function () {
			$(this).removeData('modal');
		});
		$("div[id$='Modal']").on('hidden.bs.modal',
			function () {
				$(this).removeData('bs.modal');
			}
		);
		$("div[id$='modal']").on('hidden.bs.modal',
			function () {
				$(this).removeData('bs.modal');
			}
		);
		// $('body').on('hidden', '#scoresModal', function () {
		// 	$(this).removeData('modal');
		// });
		function getModal(location) {
			$('#modal').modal({
				remote: location,
				show: true
			});
		}
		// Make twitter paginator ajax
		$('.pagination a').on('click', function (event) {
			event.preventDefault();
			if ( $(this).attr('href') != '#') {
				$("html, body").animate({ scrollTop: 0 }, 0);
				$('#ajaxContent').load($(this).attr('href'));
			}
		});
	@stop
</script>