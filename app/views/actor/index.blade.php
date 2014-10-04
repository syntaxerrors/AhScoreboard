<div class="panel panel-default">
	<div class="panel-heading">Actors</div>
	<div class="list-glow-sm">
		<div class="list-glow-labels">
			<div class="col-md-4">Name</div>
			<div class="col-md-2">Appeared In</div>
			<div class="col-md-4">Most Recent Video</div>
		</div>
		<ul class="list-glow-group no-heading">
			@foreach ($ahActors as $actor)
				<li class="open">
					<div class="list-glow-group-item">
						<div class="col-md-4">{{ $actor->link }}</div>
						<div class="col-md-2">{{ $actor->videosCount }}</div>
						<div class="col-md-4">{{ $actor->latestVideo }}</div>
					</div>
				</li>
			@endforeach
			@foreach ($actors as $actor)
				<li>
					<div class="list-glow-group-item">
						<div class="col-md-4">{{ $actor->link }}</div>
						<div class="col-md-2">{{ $actor->videosCount }}</div>
						<div class="col-md-4">{{ $actor->latestVideo }}</div>
					</div>
				</li>
			@endforeach
		</ul>
	</div>
</div>