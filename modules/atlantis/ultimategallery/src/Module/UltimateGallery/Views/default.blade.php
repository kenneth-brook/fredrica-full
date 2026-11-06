<h2>{{ $gallery->name }}</h2>
<small>view: {{ $gallery->view }} | description : {{ $gallery->description }} |  </small>
<ul class="row">
	@foreach($images as $image)
	<li class="column-2 col-lg-2" id="galleru_{{ $gallery->id }}__image_{{ $image->id }}">
		<figure>
			@if ($image->anchor_link)
			<a href="{{ $image->anchor_link }}">
			@endif

				<img {{ $image->css ? 'style="'.$image->css.'"' : '' }}  alt="{{ $image->alt }}" src="/{{ $image->original_filename }}">
			
			@if ($image->anchor_link)
			</a>
			@endif
			<figcaption>
				<small>Caption: {{ $image->caption }} <br> Image Description: {{ $image->description }} <br> Credit : {{ $image->credit }} <br>
					Alt: {{ $image->alt }}<br>
					Link: {{ $image->anchor_link  }}<br>
					Styles: {{ $image->css  }}<br>
				  </small>
			</figcaption>
		</figure>
		<hr>
	</li>
	@endforeach
</ul>
<style type="text/css">

{{ $gallery->css or ''}}
</style>
