<div class="row" id="ajaxContent">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Favorite Game Videos
				<div class="panel-btn">
					{{ HTML::link('/set-all-watched', 'Mark All Watched', array('class' => 'confirm-continue')) }}
				</div>
			</div>
			<table class="table table-condensed">
				<thead>
					<tr>
						<th class="text-center">Tower</th>
						<th>Title</th>
						<th>Set As</th>
						<th class="text-center">Series Number</th>
						<th class="text-center">Rounds</th>
						<th class="text-center">Starring</th>
						<th class="text-center">Round Winners</th>
						<th class="text-center">Video Winners</th>
						<th class="text-center">YouTube</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($videos as $video)
						<?php
							// $winners      = null;
							// $roundWinners = null;

							// if ($video->checkType('CO_OP')) {
							// 	$winners = ($video->coopStats != null ? $video->coopStats->winPercentage : null);
							// } else {
							// 	if (!$video->checkType('ROUND_BASED_GAMES'))
							// 		$roundWinners = implode('<br />', $video->roundWinners->morph->name->toArray());
							// 	elseif ($video->rounds->winners->count() > 0) {
							// 		$roundWinners = '<table>';
							// 		foreach ($video->rounds as $round) {
							// 			$roundWinners .= '<tr><td>'. $round->game->name .'</td><td>'. implode(', ', $round->winners->morph->name->toArray()) .'</td></tr>';
							// 		}
							// 		$roundWinners .= '</table>';
							// 	}
							// 	if ($video->checkType('OVERALL_WINNER')) {
							// 		$winners      = implode('<br />', $video->winners->morph->name->toArray());
							// 	} elseif ($video->winners->count() > 0) {
							// 		$winners      = $roundWinners;
							// 	}
							// }

							$actors = implode('<br />', $video->actors->morph->name->toArray());
						?>
						<tr>
							<td class="text-primary text-center">{{ ($video->checkType('FOR_THE_TOWER') ? '<i class="fa fa-check-square-o fa-lg"></i>' : null) }}</td>
							<td>{{ stripslashes($video->title) }}</td>
							<td>@include('video.components.usermarks', array('videoId' => $video->id))
							<td class="text-center">
								@if ($video->seriesNumber > 0)
									{{ $video->seriesNumber }}
								@else
									&nbsp;
								@endif
							</td>
							<td class="text-center">
								@if ($video->rounds->count() > 0)
									{{ $video->rounds->count() }}
								@else
									&nbsp;
								@endif
							</td>
							<td class="text-center">
								@if ($video->actors->count() > 0)
									<a href="javascript: void();" rel="popover" data-toggle="popover" data-trigger="{{ isset($activeUser) ? $activeUser->popover : 'click' }}" data-placement="left" data-content="{{ $actors }}" data-html="true" title data-original-title="Actors">
										<i class="fa fa-user"></i>
									</a>
								@endif
							</td>
							<td class="text-center">
							</td>
							<td class="text-center">
							</td>
							<td class="text-center">
								<a href="http://www.youtube.com/watch?v={{ $video->link }}" target="_blank"><i class="fa fa-2x fa-youtube"></i></a>
							</td>
							<td>{{ date('F jS, Y', strtotime($video->date)) }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	@section('onReadyJs')
		// Make twitter paginator ajax
		$('.pagination a').on('click', function (event) {
			event.preventDefault();
			if ( $(this).attr('href') != '#') {
				$('#ajaxContent').load($(this).attr('href'));
			}
		});
	@stop
</script>