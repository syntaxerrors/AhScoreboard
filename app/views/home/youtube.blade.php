{{ HTML::iframe('http://www.youtube.com/embed/' . $id .'?start='. $timestamp .'&end='. $end .'&autoplay=1', array(
	'class'			=> 'youtube-player',
	'type'			=> 'text/html',
	'height'		=> '420',
	'width'			=> '700',
	'frameborder'	=> 0
)) }}