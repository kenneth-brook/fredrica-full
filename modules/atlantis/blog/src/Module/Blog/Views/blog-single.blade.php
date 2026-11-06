<h1 class="title-heading">{{ $data->title }}</h1>

@if(!is_null($data->posted_date))
<h5>{{ \Carbon\Carbon::createFromFormat('Y-m-d h:i:s', $data->posted_date)->format('F d, Y') }}</h5>
@endif
{!! $data->body !!}

