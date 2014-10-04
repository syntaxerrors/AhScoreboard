{{ bForm::setType('basic')->open(false, array('url' => '/manage/add-winners/'. $video->id .'/'. $roundId, 'id' => 'winnersForm')) }}
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">{{ $video->title }} Winners</div>
				<div class="panel-body">
					<div id="winners">
						{{ Form::hidden('type', null, array('id' => 'type')) }}
						@if ($roundId != null && isset($round) && count($round->winners) > 0)
							@foreach ($round->winners->morph as $winner)
								<?php
									if ($winner instanceof Actor) {
										$actorId = $winner->id;
										$teamId  = null;
									} else {
										$actorId = null;
										$teamId  = $winner->id;
									}
								?>
								<div class="row">
									<div class="col-md-5">
										{{ bForm::select('winners[actor][]', $actorsArray, $actorId) }}
									</div>
									<div class="col-md-6">
										{{ bForm::select('winners[team][]', $teamsArray, $teamId) }}
									</div>
									<div class="col-md-1">
										<a href="javascript: void(0);" onClick="removeRow(this);">
											<i class="fa fa-times-circle"></i>
										</a>
									</div>
								</div>
							@endforeach
						@else
							<div class="row" id="template">
								<div class="col-md-5">
									{{ bForm::select('winners[actor][]', $actorsArray, null) }}
								</div>
								<div class="col-md-6">
									{{ bForm::select('winners[team][]', $teamsArray, null) }}
								</div>
								<div class="col-md-1">&nbsp;</div>
							</div>
						@endif
					</div>
					@if ($video->checkType('ROUND_BASED_GAMES'))
						<div class="row">
							<div class="col-md-11">
								<?php
									if ($roundId != null && isset($round) && $round->game != null) {
										$gameId = $round->game->game->id;
									} else {
										$gameId = null;
									}
								?>
								{{ bForm::select('game_id', $games, $gameId) }}
							</div>
							<div class="col-md-1">
								<a role="button" href="#remoteModal" data-toggle="modal" data-remote="/manage/newgame" class="btn btn-xs btn-primary" style="margin-bottom: -20px;">
									<i class="fa fa-plus"></i>
								</a>
							</div>
						</div>
					@endif
					@if ($video->checkType('ROUND_BASED_ACTORS'))
						<div class="row">
							<div class="col-md-5">
								{{ bForm::select('actors[actor][]', $multiActorsArray, $roundActors, array('multiple', 'style' => 'height: 200px;'), 'Actors') }}
							</div>
							<div class="col-md-6">
								{{ bForm::select('actors[team][]', $multiTeamsArray, $roundActors, array('multiple', 'style' => 'height: 200px;'), 'Teams') }}
							</div>
						</div>
					@endif
					<div class="btn-group">
						{{ Form::submit(
							'Finish',
							array(
								'class' => 'btn btn-sm btn-primary',
								'onClick' => 'setType("finish")'
							)
						) }}
						{{ Form::button('Add Row', array('class' => 'btn btn-sm btn-warning', 'id' => 'addRow')) }}
						{{ Form::submit(
							'Next Round',
							array(
								'class' => 'btn btn-sm btn-inverse',
								'onClick' => 'setType("nextRound")'
							)
						) }}
						{{ Form::submit(
							'Add Round',
							array(
								'class' => 'btn btn-sm btn-inverse',
								'onClick' => 'setType("addRound")'
							)
						) }}
						@if (isset($round) && $round->id != null)
							{{ HTML::link('/manage/delete-round/'. $round->id, 'Delete Round', array('class' => 'confirm-remove btn btn-sm btn-danger')) }}
						@endif
						{{ Form::submit(
							'Cancel',
							array(
								'class' => 'btn btn-sm btn-danger',
								'onClick' => 'setType("cancel")'
							)
						) }}
					</div>
				</div>
				<div class="panel-footer">
					<div id="message"></div>
				</div>
			</div>
			@include('manage.components.previousrounds')
			@include('manage.components.videodetails')
		</div>
	</div>
{{ bForm::close() }}