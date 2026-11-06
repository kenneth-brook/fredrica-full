<div class="reveal" id="{!! $modal_id !!}" data-reveal>
    
        <h1>@lang('admin::views.Creating Resource')</h1>
        {!! Form::open(['url' => 'admin/themes/create-resource/', 'data-abide' => '', 'novalidate'=> '']) !!}
        <p class="lead">@lang('admin::views.This module will attempt to create the following pages and patterns for you')</p>
        <button class="close-button" data-close aria-label="@lang('admin::views.Close modal')" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        @if(isset($filesetup['create_pages']))
            <strong>@lang('admin::views.Pages:')</strong>
            @foreach ($filesetup['create_pages'] as $page)
                <p>{{$page['name']}} : {{$page['url']}}</p>
            @endforeach
        @endif
        @if(isset($filesetup['create_patterns']) && !empty($filesetup['create_patterns']))
            <strong>@lang('admin::views.Patterns:')</strong>

            @foreach ($filesetup['create_patterns'] as $pattern)
                <p>{{$pattern['name']}}</p>
            @endforeach
        @endif

        {!! Form::input('hidden', 'theme_path', $filesetup['_theme_path'], old('iModuleId')) !!}

        <input type="submit" name="_update" value="@lang('admin::views.Create')" id="update-btn" class="alert button">
        {!! Form::close() !!}
    
</div>