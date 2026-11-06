<div class = "social_share">
	<ul>
	@foreach($networks as $network)
	    <?php $url = str_replace('#url', request()->fullUrl() , $network['url']); 
	          $url = str_replace('#title', $data->title , $url)
              
	    ?>
        <li><a href = "{{ $url }}" class = "{{$network['class']}}" target= "_blank"></a></li>
	@endforeach
	</ul>
</div>