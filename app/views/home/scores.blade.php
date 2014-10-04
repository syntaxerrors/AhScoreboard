<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
	<h3 id="myModalLabel">{{ $video->title }}</h3>
</div>
<div class="modal-body">
	@if ($video->checkType('OVERALL_WINNER') || (!$video->checkType('WAVES') && !$video->checkType('CO_OP') && !$video->checkType('ROUND_BASED_GAMES') && $video->rounds->count() == 1))
		<h3>Winner: {{ $video->winners->first()->morph->name }}</h3>
	@endif
	<table>
		<tbody>
			@if ($video->type != null)
				<tr>
					<td>Type:</td>
					<td>{{ $video->type }}</td>
				</tr>
			@endif
			<tr>
				<td>Actors:</td>
				<td>{{ implode(', ', $video->actors->morph->firstNameLink->toArray()) }}</td>
			</tr>
			<tr>
				<td>Games:</td>
				<td>{{ implode(', ', $video->games->game->labelName->toArray()) }}</td>
			</tr>
		</tbody>
	</table>
	@if ($video->rounds->count() > 0 && ($video->checkType('WAVES') || $video->checkType('CO_OP') || $video->checkType('ROUND_BASED_GAMES') || $video->rounds->count() != 1))
		<div class="well">
			<table class="table table-condensed">
				<caption>Round Details</caption>
				<thead>
					<tr>
						<th>Round</th>
						@if ($video->checkType('ROUND_BASED_GAMES'))
							<th>Game</th>
						@endif
						@if ($video->checkType('ROUND_BASED_ACTORS'))
							<th>Actors</th>
						@endif
						@if ($video->checkType('WAVES'))
							<th>Highest wave reached</th>
						@elseif ($video->checkType('CO_OP'))
							<th>Co-Op Detail</th>
						@else
							<th>Winner(s)</th>
						@endif
					</tr>
				</thead>
				<tbody>
					@foreach ($video->rounds as $videoRound)
						<?php
							$actors = implode('<br />', $videoRound->actors->morph->name->toArray());
						?>
						<tr>
							<td>Round {{ $videoRound->roundNumber }}</td>
							@if ($video->checkType('ROUND_BASED_GAMES'))
								<td>{{ $videoRound->game->link }}</td>
							@endif
							@if ($video->checkType('ROUND_BASED_ACTORS'))
								<td>
									<a href="javascript: void();" rel="popover" data-toggle="popover" data-trigger="{{ isset($activeUser) ? $activeUser->popover : 'click' }}" data-placement="right" data-content="{{ $actors }}" data-html="true" title data-original-title="Actors">
										Actors
									</a>
								</td>
							@endif
							<td>
								@if ($video->checkType('WAVES'))
									{{ $videoRound->wave->highestWave }}
								@elseif ($video->checkType('CO_OP'))
									{{ $videoRound->coopStat->display }}
								@else
									{{ implode(', ', $videoRound->winners->morph->link->toArray()) }}
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	@elseif (!$video->checkType('ROUND_BASED_GAMES') && $video->rounds->count() == 1)
		{{ null }}
	@else
		No details added yet.
	@endif
</div>
<div class="modal-footer">
	<div class="btn-group">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>