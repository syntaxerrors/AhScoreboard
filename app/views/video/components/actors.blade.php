<div class="row" id="competitorRow">
	<div class="col-md-offset-2 col-md-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<a class="accordion-toggle" data-toggle="collapse" href="#collapseCompetitors" onClick="$(this).children().toggleClass('fa-chevron-down').toggleClass('fa-chevron-up');">
						Actors / Teams <i class="fa fa-chevron-down"></i>
				</a>
			</div>
			<div id="collapseCompetitors" class="panel-body accordion-body">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6">
								<strong class="text-primary">Achievement Hunter</strong>
								@foreach ($ahActors as $actor)
									<?php
										$checked = null;
										if (isset($video) && $video->actors->count() > 0) {
											$actorCompetitors = $video->actors->filter(function ($actor) {
												if ($actor->morph_type == 'Actor') {
													return $actor;
												}
											});
											if ($actorCompetitors->morph->contains($actor->id)) {
												$checked = ' checked="checked"';
											}
										}
									?>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="actor[Actor::{{ $actor->id }}]" id="{{ $actor->name }}" {{ $checked }} />
											{{ $actor->name }}
										</label>
									</div>
								@endforeach
							</div>
							<div class="col-md-6">
								<strong class="text-primary">Podcast/Patch Crew</strong>
								@foreach ($podcastActors as $actor)
									<?php
										$checked = null;
										if (isset($video) && $video->actors->count() > 0) {
											$actorCompetitors = $video->actors->filter(function ($actor) {
												if ($actor->morph_type == 'Actor') {
													return $actor;
												}
											});
											if ($actorCompetitors->morph->contains($actor->id)) {
												$checked = ' checked="checked"';
											}
										}
									?>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="actor[Actor::{{ $actor->id }}]" id="{{ $actor->name }}" {{ $checked }} />
											{{ $actor->name }}
										</label>
									</div>
								@endforeach
							</div>
						</div>
						<hr />
						<strong class="text-primary">News Reporters</strong>
						@foreach ($newsActors as $actor)
							<?php
								$checked = null;
								if (isset($video) && $video->actors->count() > 0) {
									$actorCompetitors = $video->actors->filter(function ($actor) {
										if ($actor->morph_type == 'Actor') {
											return $actor;
										}
									});
									if ($actorCompetitors->morph->contains($actor->id)) {
										$checked = ' checked="checked"';
									}
								}
							?>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="actor[Actor::{{ $actor->id }}]" id="{{ $actor->name }}" {{ $checked }} />
									{{ $actor->name }}
								</label>
							</div>
						@endforeach
						<hr />
						<div class="row">
							<div class="col-md-6">
								<strong class="text-primary">General Actors</strong>
								@foreach ($actors as $actor)
									<?php
										$checked = null;
										if (isset($video) && $video->actors->count() > 0) {
											$actorCompetitors = $video->actors->filter(function ($actor) {
												if ($actor->morph_type == 'Actor') {
													return $actor;
												}
											});
											if ($actorCompetitors->morph->contains($actor->id)) {
												$checked = ' checked="checked"';
											}
										}
									?>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="actor[Actor::{{ $actor->id }}]" id="{{ $actor->name }}" {{ $checked }} />
											{{ $actor->name }}
										</label>
									</div>
								@endforeach
							</div>
							<div class="col-md-6">
								<strong class="text-primary">Voice Actors</strong>
								@foreach ($voiceActors as $actor)
									<?php
										$checked = null;
										if (isset($video) && $video->actors->count() > 0) {
											$actorCompetitors = $video->actors->filter(function ($actor) {
												if ($actor->morph_type == 'Actor') {
													return $actor;
												}
											});
											if ($actorCompetitors->morph->contains($actor->id)) {
												$checked = ' checked="checked"';
											}
										}
									?>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="actor[Actor::{{ $actor->id }}]" id="{{ $actor->name }}" {{ $checked }} />
											{{ $actor->name }}
										</label>
									</div>
								@endforeach
							</div>
						</div>
					</div>
					<div class="col-md-6">
						@foreach ($teams as $team)
							<?php
								$checked = null;
								if (isset($video) && $video->actors->count() > 0) {
									$teamCompetitors = $video->actors->filter(function ($actor) {
										if ($actor->morph_type == 'Team') {
											return $actor;
										}
									});
									if ($teamCompetitors->morph->contains($team->id)) {
										$checked = ' checked="checked"';
									}
								}
							?>
							<div class="checkbox">
								<label>
									<input type="checkbox" name="actor[Team::{{ $team->id }}]" id="{{ $team->name }}" {{ $checked }} />
									{{ $team->name }}
								</label>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>