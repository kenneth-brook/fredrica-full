<h3>Categories List</h3>
<ul>
	@foreach($categories as $c)
	<li>
		<a href="{{ $categories_url }}{{$c->category_name}}">{{ $c->category_name }}</a>
	</li>
	@endforeach
</ul>
