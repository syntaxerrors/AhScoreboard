<div class="row" id="ajaxContent">
	<div class="col-md-offset-1 col-md-10">
		<div class="panel panel-default">
			<div class="panel-heading">
				Videos: Found 
				@if ($paginator == true)
					{{ $videos->getTotal() }}
				@else
					{{ $videos->count() }}
				@endif
				<div class="panel-btn">
					{{ HTML::linkIcon('/video/add', 'fa fa-plus-circle') }}
				</div>
				@if ($paginator == true)
					<div class="pull-right">
						<small>
							Showing results {{ $videos->getFrom() }} - {{ $videos->getTo() }}
						</small>
					</div>
				@endif
			</div>
			<table class="table table-hover table-condensed table-striped">
				<thead>
					<tr>
						<th>Title</th>
						<th>Type</th>
						<th>Series Number</th>
						<th class="text-center">Starring</th>
						<th class="text-center">Rounds</th>
						<th class="text-center">Winners</th>
						<th class="text-center">YouTube</th>
						<th>Date</th>
						<th class="text-center">Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($videos as $video)
						<?php
							$winners = null;
							if ($video->checkType('OVERALL_WINNER')) {
								$winners = implode('<br />', $video->winners->morph->name->toArray());
							} else {
								$winners = implode('<br />', $video->rounds->winners->morph->name->toArray());
							}

							$actors = implode('<br />', $video->actors->morph->name->toArray());
						?>
						<tr>
							<td>{{ stripslashes($video->title) }}</td>
							<td>{{ $video->typeLabel }}</td>
							<td>{{ $video->seriesNumber }}</td>
							<td class="text-center">
								@if ($video->actors->count() > 0)
									<a href="javascript: void();" rel="popover" data-toggle="popover" data-trigger="{{ $activeUser->popover }}" data-placement="left" data-content="{{ $actors }}" data-html="true" title data-original-title="Actors">
										<i class="fa fa-user"></i>
									</a>
								@endif
							</td>
							<td class="text-center">{{ $video->rounds->count() }}</td>
							<td class="text-center">
								@if ($video->checkType('CO_OP'))
									@if ($video->rounds->coopStat->count() > 0)
										<?php
											$coopDetails = $video->coopPercentage .'<hr />';
											$coopDetails .= implode('<br />', $video->rounds->coopStat->displayClean->toArray());
										?>
										<a href="javascript: void();" rel="popover" data-toggle="popover" data-trigger="{{ $activeUser->popover }}" data-placement="left" data-content="{{ $coopDetails }}" data-html="true" title data-original-title="Co-Op Stats">
											<i class="fa fa-trophy"></i>
										</a>
									@endif
								@elseif ($video->checkType('WAVES'))
									@if ($video->rounds->wave->count() > 0)
										<?php
											$waveDetails = implode('<br />', $video->rounds->wave->highestWave->toArray());
										?>
										<a href="javascript: void();" rel="popover" data-toggle="popover" data-trigger="{{ $activeUser->popover }}" data-placement="left" data-content="{{ $waveDetails }}" data-html="true" title data-original-title="Highest Waves">
											<i class="fa fa-trophy"></i>
										</a>
									@endif
								@else
									@if ($winners != null)
										<a href="javascript: void();" rel="popover" data-toggle="popover" data-trigger="{{ $activeUser->popover }}" data-placement="left" data-content="{{ $winners }}" data-html="true" title data-original-title="Winners">
											<i class="fa fa-trophy"></i>
										</a>
									@endif
								@endif
							</td>
							<td class="text-center"><a href="http://www.youtube.com/watch?v={{ $video->link }}" target="_blank"><i class="fa fa-2x fa-youtube"></i></td>
							<td>{{ date('F jS, Y', strtotime($video->date)) }}</td>
							<td class="text-center">
								<div class="btn-group">
									{{ HTML::link('/manage/detail/'. $video->id, 'Details', array('class' => 'btn btn-xs btn-primary')) }}
									<button class="btn btn-xs btn-inverse dropdown-toggle" data-toggle="dropdown">
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu text-left">
										@if ($video->checkType('OVERALL_WINNER'))
											<li>{{ HTML::link('/manage/overall-winner/'. $video->id, 'Overall') }}</li>
										@endif
										<li>{{ HTML::link('/video/view/'. $video->id, 'View') }}</li>
										<li>{{ HTML::link('/video/edit/'. $video->id, 'Edit') }}</li>
										<li>{{ HTML::link('/video/delete/'. $video->id, 'Delete', array('class' => 'confirm-remove text-error')) }}</li>
									</ul>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<div class="text-center">
				@if ($paginator == true)
					{{ $videos->links() }}
				@endif
			</div>
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