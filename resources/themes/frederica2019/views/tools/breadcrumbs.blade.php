@if (!$pages->isEmpty())
<?php //dd($pages) ?>
<section class="container-lg container">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
			@foreach ($pages as $page)
			
			@if ($page->status == 1)
			<?php $page_url = url($page->url); ?>
			<li class="breadcrumb-item {{ ($current_url == $page_url || strpos($page_url, ':any') !== false) ? 'active' : '' }}">
				<a {!! ($current_url == $page_url || strpos($page_url, ':any') !== false)  ? 'href="' . $current_url . '"' : 'href="' . $page_url . '"' !!}>{{ $page->name }}</a>
			</li>
			@endif
			@endforeach
		</ol>
	</nav>
</section>
@endif