<div class="blog-listing">
	
	@foreach ($oBlogs as $blog)
	<?php 
	$links = explode("\n", $blog->description);
	/*if $previewImage is string imported from the old site use the old iamge name else use A3 media object*/
	$previewImage = '';
	if(strpos($blog->imgs, '.jpg') !== false || strpos($blog->imgs, '.png') !== false || strpos($blog->imgs, '.pdf') !== false || strpos($blog->imgs, '.gif')) {            
		$previewImage = '/resources/media/user/'.$blog->imgs;
		$thumb = $previewImage;
	}
	elseif ($blog->imgs != ""){
		$previewImage = MediaTools::getImage(intval($blog->imgs));
		$thumb = $previewImage->thumbnail;
		$previewImage = $previewImage->original_filename;
	}
	?>

	<article class="blog-entry full-width">
		<div class="blog-entry-content">
			<h2>{{  $blog->title }}</h2>
			<small class="entry-date">{!! \Carbon\Carbon::createFromFormat('Y-m-d h:i:s',$blog->posted_date)->format('F d, Y') !!}
			</small>

			{!! $blog->body !!}

			<ul class="links">
				@if (!empty($links))
				@foreach ($links as $l)
				@if(strpos($l, '.pdf') !== false)
				<li>
					<a class="pdf" target="_blank" href="https://www.golfdigest.com/story/best-teachers-state-ranking">Download PDF</a>
				</li>
				@elseif(strpos($l, 'youtube.com') !== false || strpos($l, 'youtu.be') !== false)
				<li>
					<a class="video" target="_blank" href="https://www.golfdigest.com/story/best-teachers-state-ranking">Watch Video</a>
				</li>
				@elseif($l != "")
				<li>
					<a target="_blank" href="https://www.golfdigest.com/story/best-teachers-state-ranking" class="external">Visit External Site</a>
				</li>
				@endif
				@endforeach
				@endif
			</ul>
		</div>
	</article>
	@endforeach
</div>