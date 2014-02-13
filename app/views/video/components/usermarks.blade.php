@if (isset($activeUser) && !is_null($activeUser))
	<ul class="list-inline">
		<?php
			$favorite   = 'none';
			$unfavorite = 'none';

			if ($activeUser->isFavorite($videoId)) {
				$unfavorite = 'inline-block';
			} else {
				$favorite = 'inline-block';
			}

			$watched   = 'none';
			$unwatched = 'none';

			if ($activeUser->hasWatched($videoId)) {
				$unwatched = 'inline-block';
			} else {
				$watched = 'inline-block';
			}
		?>
		<li>
			<a href="javascript: void(0);" onClick="setUnfavorite('{{ $videoId }}');" id="unfavorite_{{ $videoId }}" style="display: {{ $unfavorite }};">
				<i class="fa fa-heart" title="Unfavorite"></i>
			</a>
			<a href="javascript: void(0);" onClick="setFavorite('{{ $videoId }}');" style="display: {{ $favorite }};" id="favorite_{{ $videoId }}">
				<i class="fa fa-heart-o" title="Favorite"></i>
			</a>
		</li>
		<li>
			<a href="javascript: void(0);" onClick="setUnwatch('{{ $videoId }}');" style="display: {{ $unwatched }};" id="unwatched_{{ $videoId }}">
				<i class="fa fa-eye" title="Mark unwatched"></i>
			</a>
			<a href="javascript: void(0);" onClick="setWatch('{{ $videoId }}');" style="display: {{ $watched }};" id="watched_{{ $videoId }}">
				<i class="fa fa-eye-slash" title="Mark watched"></i>
			</a>
		</li>
	</ul>

	@section('js')
		@parent
		<script>
			function setUnfavorite(videoId) {
				$.get('/video/unfavorite/'+ videoId +'/Video');
				$('#unfavorite_'+ videoId).toggle();
				$('#favorite_'+ videoId).toggle();
			}
			function setFavorite(videoId) {
				$.get('/video/favorite/'+ videoId +'/Video');
				$('#unfavorite_'+ videoId).toggle();
				$('#favorite_'+ videoId).toggle();
			}
			function setUnwatch(videoId) {
				$.get('/video/unwatched/'+ videoId);
				$('#unwatched_'+ videoId).toggle();
				$('#watched_'+ videoId).toggle();
			}
			function setWatch(videoId) {
				$.get('/video/watched/'+ videoId);
				$('#unwatched_'+ videoId).toggle();
				$('#watched_'+ videoId).toggle();
			}
		</script>
	@stop
@endif