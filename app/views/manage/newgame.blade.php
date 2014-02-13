{{ bForm::setSizes(3)->open(false, array('id' => 'submitForm')) }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
		<h3 id="myModalLabel">Add New Game</h3>
	</div>
	<div class="modal-body">
		{{ bForm::text('name', null, array('class' => 'input-block-level', 'placeholder' => 'Name'), 'Name') }}
		{{ bForm::text('keyName', null, array('class' => 'input-block-level', 'placeholder' => 'Key Name'), 'Key Name') }}
		{{ bForm::text('commonName', null, array('class' => 'input-block-level', 'placeholder' => 'Common Name'), 'Common Name') }}
		{{ bForm::checkbox('challengeFlag', 1, false, array(), 'Challenge Flag') }}
	</div>
	<div class="modal-footer">
		<div class="btn-group">
			{{ Form::submit('Submit', array('class' => 'btn btn-sm btn-primary')) }}
			<button class="btn btn-sm btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
		</div>
		<div id="message"></div>
	</div>
{{ Form::close() }}
@section('js')
	<script>
		$('#submitForm').AjaxSubmit(
			{
				path: '/{{ Request::path() }}',
				successMessage: 'Entry successfully updated.'
			},
			function(data) {
				resource = data.resource;
				$('select[name=game_id]').prepend('<option value="'+ resource.uniqueId +'">'+ resource.name +'</option>').val(resource.uniqueId);
				$('#remoteModal').modal('toggle');
			}
		);
	</script>
@endsection