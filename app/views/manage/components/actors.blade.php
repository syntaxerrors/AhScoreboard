<div class="row">
	<div class="col-md-12">
		{{ bForm::select('actors[actor][]', $actorsArray, null, array('multiple'), 'Actors') }}
		<table class="table table-condesnsed" id="competitors">
			<tbody>
				@foreach ($actors as $actor)
					<?php
						$checked = null;
						$actorCompetitors = $video->actors->filter(function ($actor) {
							if ($actor->morph_type == 'Actor') {
								return $actor;
							}
						});
						if ($actorCompetitors->morph->contains($actor->id)) {
							$checked = ' checked="checked"';
						}
					?>
					<tr>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="actors[Actor::{{ $actor->id }}]" id="{{ $actor->name }}" {{ $checked }} />
								{{ $actor->name }}
							</label>
						</div>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<table class="table table-condesnsed" id="competitors">
			<tbody>
				@foreach ($teams as $team)
					<?php
						$checked = null;
						$teamCompetitors = $video->actors->filter(function ($actor) {
							if ($actor->morph_type == 'Team') {
								return $actor;
							}
						});
						if ($teamCompetitors->morph->contains($team->id)) {
							$checked = ' checked="checked"';
						}
					?>
					<tr>
						<div class="checkbox">
							<label title="{{ implode(', ', $team->actors->name->toArray()) }}">
								<input type="checkbox" name="actors[Team::{{ $team->id }}]" id="{{ $team->name }}" {{ $checked }} />
								{{ $team->name }}
							</label>
						</div>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>