<h2>{{ $actor->name }}</h2>
<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">VS Stats</div>
			<table class="table table-condensed table-striped table-hover">
				<tbody>
					<tr>
						<td>Beast Streak</td>
						<td>{{ HTML::link('/video/view-streak/VERSUS/'. $actor->id, $actor->bestStreak('VERSUS')) }}</td>
					</tr>
				</tbody>
			</table>
			<table class="table table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Competed In</th>
						<th>Wins</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Total</td>
						<td>{{ $actor->totalAppearances('VERSUS') }}</td>
						<td>{{ $actor->totalWins('VERSUS') }}</td>
					</tr>
					@foreach ($actors as $opponent)
						<?php $competedAgainst = $actor->competedAgainst('VERSUS', $opponent->id); ?>
						@if ($competedAgainst->count() > 0)
							<tr>
								<td>Against {{ $opponent->fullName }}</td>
								<td>{{ $competedAgainst->count() }}</td>
								<td>{{ $competedAgainst->winners->morph->where('id', $actor->id)->count() }}</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Achievement HUNT Stats</div>
			<table class="table table-condensed table-striped table-hover">
				<tbody>
					<tr>
						<td>Beast Streak</td>
						<td>{{ HTML::link('/video/view-streak/ACHIEVEMENT_HUNT/'. $actor->id, $actor->bestStreak('ACHIEVEMENT_HUNT')) }}</td>
					</tr>
				</tbody>
			</table>
			<table class="table table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Competed In</th>
						<th>Wins</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Total</td>
						<td>{{ $actor->totalAppearances('ACHIEVEMENT_HUNT') }}</td>
						<td>{{ $actor->totalWins('ACHIEVEMENT_HUNT') }}</td>
					</tr>
					@foreach ($actors as $opponent)
						<?php $competedAgainst = $actor->competedAgainst('ACHIEVEMENT_HUNT', $opponent->id); ?>
						@if ($competedAgainst->count() > 0)
							<tr>
								<td>Against {{ $opponent->fullName }}</td>
								<td>{{ $competedAgainst->count() }}</td>
								<td>{{ $competedAgainst->winners->morph->where('id', $actor->id)->count() }}</td>
							</tr>
						@endif
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Tower of Pimps Stats</div>
			<table class="table table-condensed table-striped table-hover">
				<tbody>
					<tr>
						<td>Beast Streak</td>
						<td>{{ HTML::link('/video/view-streak-type/FOR_THE_TOWER/'. $actor->id, $actor->bestStreakByType('FOR_THE_TOWER')) }}</td>
					</tr>
				</tbody>
			</table>
			<table class="table table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Competed In</th>
						<th>Wins</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Total</td>
						<td>{{ $actor->totalAppearancesByType('FOR_THE_TOWER') }}</td>
						<td>{{ $actor->totalWinsByType('FOR_THE_TOWER') }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>