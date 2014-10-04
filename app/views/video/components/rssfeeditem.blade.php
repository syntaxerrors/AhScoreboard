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

	if ($existingVideo instanceof Video) {
		// Get the dev links
		if (isset($activeUser) && $activeUser->checkPermission('ADD_EPISODES')) {
			$devLinks[] = HTML::linkIcon('/video/edit/'. $existingVideo->id, 'fa fa-edit', null, array('class' => 'btn btn-xs btn-primary'));
			$devLinks[] = HTML::linkIcon('/manage/detail/'. $existingVideo->id, 'fa fa-sitemap', null, array('class' => 'btn btn-xs btn-primary'));
			$devLinks = implode($devLinks);
		}

		// See who competed in the episode
		if (count($existingVideo->actors) > 0) {
			foreach ($existingVideo->actors->morph as $competitor) {
				if ($competitor instanceof Actor) {
					$memberLinks[] = $competitor->firstNameLink;
				} else {
					$teamLinks[]   = HTML::link('/team/view/'. $competitor->id, $competitor->name);
				}
			}
			if (count($memberLinks) > 0) $memberLinks = implode('&nbsp;|&nbsp;', $memberLinks);
			if (count($teamLinks) > 0)   $teamLinks   = implode('&nbsp;|&nbsp;', $teamLinks);
		}
	} elseif (isset($activeUser) && $activeUser->checkPermission('ADD_EPISODES')) {
		$devLinks = HTML::linkIcon('/video/add/'. urlencode($title) .'/'. $id .'/'. date('Y-m-d', strtotime($date)), 'fa fa-plus', null, array('class' => 'btn btn-xs btn-primary'));
	}
?>
<tr>
	<td><img class="media-object" src="{{ $enclosure->get_thumbnail() }}" style="width: 100px;" /></td>
	<td style="vertical-align: top;">
		<a href="{{ $link }}" target="_blank">{{ $title }}</a>
		<br />
		{{ date('m-d-Y h:ia', strtotime($date)) }}
	</td>
	<td style="width: 10%;">
		<div class="btn-group">
			@if (!is_array($devLinks))
				{{ $devLinks }}
			@endif
		</div>
	</td>
</tr>