<div class="row">
	<div class="col-md-6">
		@foreach ($ahFeed->get_items() as $item)
			@include('video.components.rssfeeditem')
		@endforeach
	</div>
	<div class="col-md-6">
		@foreach ($rtFeed->get_items() as $item)
			@include('video.components.rssfeeditem')
		@endforeach
	</div>
</div>
<div id="scoresModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myScoresModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
				<h4 id="myScoresModalLabel">Modal header</h4>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer"></div>
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
		$('body').on('hidden', '#scoresModal', function () {
			$(this).removeData('modal');
		});
		function getModal(location) {
			$('#scoresModal').modal({
				remote: location,
				show: true
			});
		}
	@stop
</script>