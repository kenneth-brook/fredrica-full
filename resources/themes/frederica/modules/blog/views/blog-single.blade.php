<?php 
$links = explode("\n", $data->description);
/*if $previewImage is string imported from the old site use the old iamge name else use A3 media object*/
$previewImage = '';
if(strpos($data->imgs, '.jpg') !== false || strpos($data->imgs, '.png') !== false || strpos($data->imgs, '.pdf') !== false || strpos($data->imgs, '.gif')) {            
	$previewImage = '/resources/media/user/'.$data->imgs;
	$thumb = $previewImage;
}
elseif ($data->imgs != ""){
	$previewImage = MediaTools::getImage(intval($data->imgs));
	
	if(!is_null($previewImage)){

		$thumb = $previewImage->thumbnail;
		$previewImage = $previewImage->original_filename;
		
	}
}
?>
<script type="text/javascript">

	$(document).ready( function () {

		$(".swipebox").swipebox();

	});

</script>

<div id="mainColumn" class="contents">
	<article class="blog-entry">
		<a href="/whats-new"> Go Back to News </a>

		<div class="blog-entry-content">
			<h1>{{ $data->title }}</h1>
			<small class="entry-date">{{ \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $data->posted_date)->format('F d, Y') }}</small>

			{{ $data->body }}

			<ul class="links">
				@if (!empty($links))
				@foreach ($links as $l)
				@if(strpos($l, '.pdf') !== false)
				<li>
					<a class="pdf" target="_blank" href="{{ $l }}">Download PDF</a>
				</li>
				@elseif(strpos($l, 'youtube.com') !== false || strpos($l, 'youtu.be') !== false)
				<li>
					<a class="video" target="_blank" href="{{ $l }}">Watch Video</a>
				</li>
				@elseif($l != "")
				<li>
					<a target="_blank" href="{{ $l }}" class="external">Visit External Site</a>
				</li>
				@endif
				@endforeach
				@endif
			</ul>
		</div>
		<aside class="blog-entry-photos">
			@if (isset($previewImage) && $previewImage != '' && isset($thumb))
			<a href="{{ $previewImage }}" class="swipebox">
				<img src="{{ $thumb }}" alt="{{ $data->title }}">
			</a>
			@endif
		</aside>
	</article>
</div>