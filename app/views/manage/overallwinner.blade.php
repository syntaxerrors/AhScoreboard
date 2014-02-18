<div class="row">
	<div class="col-md-offset-2 col-md-8">
		{{ bForm::setType('basic')->open() }}
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">{{ $video->title }} Overall Winner</div>
						<div class="panel-body">
							<div id="winners">
								@if ($video->winners->count() > 0)
									@foreach ($video->winners->morph as $winner)
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
							<div class="btn-group">
								{{ Form::submit(
									'Finish',
									array(
										'class' => 'btn btn-sm btn-primary',
										'onClick' => 'setType("finish")'
									)
								) }}
								{{ Form::button('Add Row', array('class' => 'btn btn-sm btn-warning', 'id' => 'addRow')) }}
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
	</div>
</div>

@section('js')
	<script>
		$('#addRow').click(function() {
			var tr = $('#template');
			var clone = tr.clone();

			clone.find('.col-md-1').html('<a href="javascript: void(0);" onClick="removeRow(this);"><i class="fa fa-times-circle"></i></a>');

			$('#winners').append(clone);
		});

		function removeRow(object) {
			if ($('#winners div.row').length > 1) {
				$(object).parent().parent().remove();
			} else {
				$(object).parent().parent().find('select').val(0);
				$(object).remove()
			}
		}
	</script>
@stop