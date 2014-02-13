@if ($video->rounds->count() > 0 || (isset($round) && $video->previousRounds($round->id)->count() > 0))
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Previous Rounds</div>
				<table class="table table-condensed">
					<tbody>
						@foreach ($video->rounds as $videoRound)
							<?php
								$winners = implode(', ', $videoRound->winners->morph->link->toArray());
							?>
							<tr>
								<td>{{ $videoRound->roundNumber }}</td>
								@if ($video->checkType('ROUND_BASED_GAMES'))
									<td>{{ $videoRound->games->game->name }}</td>
								@endif
								@if ($video->checkType('ROUND_BASED_ACTORS'))
									<?php
										$actors = implode('<br />', $videoRound->actors->morph->name->toArray());
									?>
									<td>
										<a href="javascript: void();" rel="popover" data-toggle="popover" data-trigger="{{ $activeUser->popover }}" data-placement="right" data-content="{{ $actors }}" data-html="true" title data-original-title="Actors">
											Actors
										</a>
									</td>
								@endif
								<td>
									{{ $winners }}
								</td>
								<td class="text-right">
									<div class="btn-group">
										@if (!isset($round) || $round->id != $videoRound->id)
											<a href="/manage/detail/{{ $video->id }}/{{ $videoRound->id }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
											<a href="/manage/delete-round/{{ $videoRound->id }}" class="confirm-remove btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></a>
										@endif
									</div>
								</td>
							</tr>
						@endforeach
						@if ($video->checkType('OVERALL_WINNER'))
							@if ($video->winners->count() > 0)
								<tr>
									<td>Winner</td>
									@if ($video->checkType('ROUND_BASED_ACTORS') && $video->checkType('ROUND_BASED_GAMES'))
										<td colspan="3">
									@elseif ($video->checkType('ROUND_BASED_ACTORS') || $video->checkType('ROUND_BASED_GAMES'))
										<td colspan="2">
									@else
										<td>
									@endif
										{{ $video->winners[0]->morph->link }}
									</td>
									<td class="text-right">
										<div class="btn-group">
											<a href="/manage/overall-winner/{{ $video->id }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
										</div>
									</td>
								</tr>
							@endif
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endif