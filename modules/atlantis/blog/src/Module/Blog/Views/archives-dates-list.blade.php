<h3>Archives</h3>
<ul>
	@foreach($list as $y=>$months)
	<li class="year">
		<h4>{{$y}}</h4>
		<ul>
			@foreach ($months as $k=>$m)
			<li>
				<a href="{{ $archive_url }}{{$y}}/{{$k}}">{{$m}}</a>
			</li>
		</ul>
		@endforeach
	</li>
	@endforeach
</ul>
