{{ bForm::open() }}
	<div class="row">
		<div class="col-md-offset-2 col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">Add New Video</div>
				<div class="panel-body">
					{{ bForm::select('series_id', $series, $seriesId, array('id' => 'series_id', 'required' => 'required'), 'Series') }}
					<div class="form-group">
						<label class="col-md-2 control-label" for="game_id">Game</label>
						<div class="col-md-9">
							{{ Form::select('game_id', $games, $gameId, array('id' => 'game_id', 'required' => 'required', 'class' => 'form-control')) }}
						</div>
						<a role="button" href="#remoteModal" data-toggle="modal" data-remote="/manage/newgame">
							<i class="fa fa-plus"></i>
						</a>
					</div>
					{{ bForm::text('seriesNumber', $seriesNumber, array('id' => 'seriesNumber'), 'Video Number') }}
					{{ bForm::select('parentId', Video::orderBy('title', 'asc')->get()->toSelectArray('Select a video', 'id', 'title'), null, array('id' => 'parentId'), 'Parent Video') }}
					{{ bForm::text('title', $title, array('id' => 'title', 'required' => 'required'), 'Title') }}
					{{ bForm::text('link', $link, array('id' => 'link', 'required' => 'required'), 'YouTube Link') }}
					{{ bForm::date('date', $date, array(),  'Date') }}
					{{ bForm::select('types[]', $types, null, array('multiple'), 'Types') }}
					<div class="form-group">
						<div class="col-md-offset-2 col-md-10">
							{{ Form::submit('Submit', array('class' => 'btn btn-sm btn-primary')) }}
							{{ Form::submit('Add Winners/Quotes', array('class' => 'btn btn-sm btn-info', 'name' => 'details')) }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('video.components.actors')
{{ bForm::close() }}
<script>
	@section('onReadyJs')
		$('#episodeCompetitorFlag').on('click', function() {
			$('#competitorRow').toggle();
		});
	@stop
</script>