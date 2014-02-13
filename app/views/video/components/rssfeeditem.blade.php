<?php
	$enclosure = $item->get_enclosure();
	$link      = $item->get_link();
	$title     = $enclosure->get_title();
	$date      = $item->get_local_date();

	// Skip certain video types that may show up
	// if (stripos($title, 'Full Play') !== false
	// 	|| stripos($title, 'Let\'s Build') !== false) {
	// 	continue 2;
	// }

	// Find existing episodes
	$id              = substr($item->get_id(), 42);
	$existingVideo = Video::where('link', $id)->first();
	$devLinks        = array();
	$memberLinks     = array();
	$teamLinks       = array();

	if ($existingVideo instanceof Episode) {
		// Get the dev links
		if (isset($activeUser) && $activeUser->checkPermission('ADD_EPISODES')) {
			$devLinks[] = HTML::linkIcon('/manage/edit/'. $existingVideo->id, 'fa fa-edit');

			if (!$existingVideo->checkType('CO_OP')) {
				$devLinks[] = HTML::linkIcon('/manage/winners/'. $existingVideo->id, 'fa fa-sitemap');
			} else {
				$devLinks[] = HTML::linkIcon('/manage/coop-stats/'. $existingVideo->id, 'fa fa-sitemap');
			}
			$devLinks = '<div class="panel-btn">'. implode('</div><div class="panel-btn">', $devLinks) .'</div>';
		}

		// See who competed in the episode
		if (count($existingVideo->actors) > 0) {
			foreach ($existingVideo->actors->morph as $competitor) {
				if ($competitor instanceof Actor) {
					$memberLinks[] = HTML::link('/actors/view/'. $competitor->id, $competitor->name);
				} else {
					$teamLinks[]   = HTML::link('/team/view/'. $competitor->id, $competitor->name);
				}
			}
			if (count($memberLinks) > 0) $memberLinks = implode('&nbsp;|&nbsp;', $memberLinks);
			if (count($teamLinks) > 0)   $teamLinks   = implode('&nbsp;|&nbsp;', $teamLinks);
		}
	} elseif (isset($activeUser) && $activeUser->checkPermission('ADD_EPISODES')) {
		$devLinks = '
			<div class="panel-btn">
				'. HTML::linkIcon('/video/add/'. urlencode($title) .'/'. $id .'/'. date('Y-m-d', strtotime($date)), 'fa fa-plus') .'
			</div>
		';
	}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		{{ $enclosure->get_title() }}
		@if (!is_array($devLinks))
			{{ $devLinks }}
		@endif
	</div>
	<div class="panel-body">
		<div class="media">
			<a class="pull-left" href="{{ $link }}" target="_blank">
				<img class="media-object" src="{{ $enclosure->get_thumbnail() }}" style="width: 200px;" />
			</a>
			<div class="media-body">
				<h4 class="media-heading">
					<a href="{{ $link }}" target="_blank">{{ $title }}</a>
					@if ($existingVideo != null)
						<span class="pull-right">
							@include('video.components.usermarks', array('videoId' => $existingVideo->id))
						</span>
					@endif
				</h4>
				{{ $enclosure->get_description() }}
			</div>
		</div>
	</div>
	<div class="panel-footer" style="color: #ffffff;">
		<div class="row">
			<div class="col-md-5">
				<span class="pull-left">
					{{ date('F jS, Y \a\t h:ia', strtotime($date)) }}
				</span>
			</div>
			<div class="col-md-2">
				@if ($existingVideo != null && 
					($existingVideo->rounds->winners->count() > 0 || $existingVideo->coopStats != null))
					<div class="text-center">
						<a role="button" href="javascript:void(0);" data-remote="/scores/{{ $existingVideo->id }}" class="confirm-spoiler">Score</a>
					</div>
				@endif
			</div>
			<div class="col-md-5" style="vertical-align: top;">
				@if (!is_array($memberLinks))
					<span class="pull-right">
						{{ $memberLinks }}
					</span>
					<br />
				@endif
				@if (!is_array($teamLinks))
					<span class="pull-right">
						{{ $teamLinks }}
					</span>
				@endif
			</div>
		</div>
	</div>
</div>