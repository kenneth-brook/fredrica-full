<?php if( !is_null($video_link)){

	if(preg_match('/https:\/\/(www\.)*vimeo\.com\/.*/',$video_link)){
		if(preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $video_link, $output_array)) {
			$vidId = $output_array[5];
			$iframe = '<iframe src="https://player.vimeo.com/video/'.$vidId.'" width="465" height="262" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen style = "padding-bottom:5%"></iframe>';
		}

	}
	
	if(preg_match('/https:\/\/(www\.)*youtube\.com\/.*/',$video_link)){

		parse_str( parse_url( $video_link, PHP_URL_QUERY ), $array_of_vars );
		$vidId = $array_of_vars['v'];
		$iframe = '<iframe width="465" height="261.5625" src="https://www.youtube.com/embed/'.$vidId.'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen style = "padding-bottom:5%"></iframe>';

	}
}?>

@if(isset($iframe))
	{{ $iframe }}
@endif