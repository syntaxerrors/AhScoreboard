<div class="panel panel-default">
	<div class="panel-heading">{{ $actor->fullName }}'s Best Streak {{ $type->name }}</div>
	<div class="panel-body">
		@foreach ($actor->bestStreakByType($type->keyName, true) as $video)
			<div class="media">
				<a class="pull-left" href="{{ $video->youtubeLink }}" target="_blank">
					<img class="media-object" src="{{ $video->image }}" style="width: 200px;" />
				</a>
				<div class="media-body">
					<h4 class="media-heading">
						<a href="{{ $video->youtubeLink }}" target="_blank">{{ $video->title }}</a>
						@if (isset($activeUser) && $activeUser != null)
							<span class="pull-right">
								@include('video.components.usermarks', array('videoId' => $video->id))
							</span>
						@endif
					</h4>
					<table class="table table-condensed">
						<tbody>
							<tr>
								<td><strong>Aired</strong></td>
								<td>{{ date('F jS, Y', strtotime($video->date)) }}</td>
							</tr>
							<?php
								$actors = clone $video->actors;
								$actorsArray = $actors->filter(function ($actor) {
									if ($actor->morph_type == 'Actor') return true;
								})->morph->firstNameLink->toArray();

								$teams = clone $video->actors;
								$teamsArray = $actors->filter(function ($actor) {
									if ($actor->morph_type == 'Team') return true;
								})->morph->link->toArray();
							?>
							@if (count($actorsArray) > 0)
								<tr>
									<td style="width: 75px;"><strong>Actors</strong></td>
									<td>{{ implode(', ', $actorsArray) }}</td>
								</tr>
							@endif
							@if (count($teamsArray) > 0)
								<tr>
									<td style="width: 75px;"><strong>Teams</strong></td>
									<td>{{ implode(', ', $teamsArray) }}</td>
								</tr>
							@endif
							@if ($video->games->count() > 0)
								<tr>
									<td style="width: 75px;"><strong>Games</strong></td>
									<td>{{ implode(', ', $video->games->game->labelName->toArray()) }}</td>
								</tr>
							@endif
							@if ($video->quotes->count() > 0)
								<tr>
									<td style="width: 75px;"><strong>Quotes</strong></td>
									<td>{{ implode(', ', $video->quotes->linkOnly->toArray()) }}</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		@endforeach
	</div>
</div>