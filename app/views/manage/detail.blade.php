<div class="row">
	@if ($video->series->checkType('NON_GAME'))
		<div class="col-md-12">
			@include('manage.components.quotes')
		</div>
	@else
		<div class="col-md-6">
			@if ($video->checkType('CO_OP'))
				@include('manage.components.coop')
			@elseif ($video->checkType('WAVES'))
				@include('manage.components.waves')
			@elseif ($video->series->checkType('CHARACTERS'))
				@include('manage.components.characters')
			@else
				@include('manage.components.winners')
			@endif
		</div>
		<div class="col-md-6">
			@include('manage.components.quotes')
		</div>
	@endif
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

		function setType(type) {
			$('#type').val(type);
		}

		// $('#winnersForm').AjaxSubmit(
		// 	{
		// 		path: '/manage/add-winners/{{ $video->id }}/{{ $roundId }}',
		// 		successMessage: 'Winners added.'
		// 	},
		// 	function(data) {
		// 		$('#existingQuotes tbody tr.placeholder').remove();
		// 		var row = '<tr>'+
		// 			'<td>'+ data.resource.title +'</td>'+
		// 			'<td>'+ data.resource.timeStart +'</td>'+
		// 			'<td>'+ data.resource.timeEnd +'</td>'+
		// 			'<td>'+ data.resource.memberNames +'</td>'+
		// 		'</tr>';

		// 		$('#existingQuotes tbody').append(row);
		// 	}
		// );

		$('#quoteForm').AjaxSubmit(
			{
				path: '/manage/add-quote/{{ $video->id }}',
				successMessage: 'Quote added.'
			},
			function(data) {
				$('#existingQuotes tbody tr.placeholder').remove();
				var row = '<tr>'+
					'<td>'+ data.resource.title +'</td>'+
					'<td>'+ data.resource.timeStart +'</td>'+
					'<td>'+ data.resource.timeEnd +'</td>'+
					'<td>'+ data.resource.memberNames +'</td>'+
				'</tr>';

				$('#existingQuotes tbody').append(row);

				$('#quoteForm')[0].reset();
			}
		);
	</script>
@stop