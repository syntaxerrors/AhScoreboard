{{ bForm::setType('basic')->open(false, array('url' => '/manage/add-waves/'. $video->id .'/'. $roundId, 'id' => 'winnersForm')) }}
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">{{ $video->title }} Waves</div>
				<div class="panel-body">
					<div id="winners">
						{{ Form::hidden('type', null, array('id' => 'type')) }}
						@if ($roundId != null && isset($round) && $round->wave != null)
							<div class="row">
								<div class="col-md-12">
									{{ bForm::text('highestWave', $round->wave->highestWave, array(), 'Highest Wave') }}
								</div>
							</div>
						@else
							<div class="row" id="template">
								<div class="col-md-12">
									{{ bForm::text('highestWave', null, array(), 'Highest Wave') }}
								</div>
							</div>
						@endif
					</div>
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