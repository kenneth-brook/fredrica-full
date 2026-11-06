<h6>TAG CLOUD</h6>
<div style="font-size: 5rem">
	
	@foreach($tags as $t)

	<a data-density={{ $t->density }} data-count={{ $t->total  }} style="font-size:{{ 100 / $t->density }}rem" href="{{ url('/') .'/'. $anchor_url }}?tag={{ $t->tag }}">{{ $t->tag }}</a>

	@endforeach

</div>