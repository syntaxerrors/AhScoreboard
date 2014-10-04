<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Details
				<div class="pull-right">
					{{ $video->typeLabel }}
				</div>
			</div>
			<div class="panel-body">
				<strong>Games</strong>
				<br />
				{{ implode('<br />', $video->games->game->labelName->toArray()) }}
				@if (!$video->series->checkType('CHARACTERS'))
					<hr />
					<strong>Actors</strong>
					<br />
					{{ implode('<br />', $video->actors->morph->link->toArray()) }}
				@endif
			</div>
		</div>
	</div>
</div>