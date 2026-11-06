<div class="reveal" id="{!! $modal_id !!}" data-reveal>
    @if(empty(\Pages::pageExists($module_id)))
        <h1>@lang('admin::views.Creating Resource')</h1>
        {!! Form::open(['url' => 'admin/modules/create-page/', 'data-abide' => '', 'novalidate'=> '']) !!}
        <p class="lead">@lang('admin::views.This module will attempt to create the following pages and patterns for you')</p>
        <button class="close-button" data-close aria-label="@lang('admin::views.Close modal')" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        @if(isset($module_data['create_pages']))
            <strong>@lang('admin::views.Pages:')</strong>
            @foreach ($module_data['create_pages'] as $page)
                <p>{{$page['name']}} : {{$page['url']}}</p>
            @endforeach
        @endif
        @if(isset($module_data['create_patterns']) && !empty($module_data['create_patterns']))
            <strong>@lang('admin::views.Patterns:')</strong>

            @foreach ($module_data['create_patterns'] as $pattern)
                <p>{{$pattern['name']}}</p>
            @endforeach
        @endif
        {!! Form::input('hidden', 'iModuleId', $module_id, old('iModuleId')) !!}
        <input type="submit" name="_uninstall_module" value="@lang('admin::views.Create')" id="update-btn" class="alert button">
        {!! Form::close() !!}
    @else
        <h1>@lang('admin::views.Page Exist')</h1>
        <p class="lead">@lang('admin::views.This module is trying to create the following pages that already exist in your installation')</p>
        <button class="close-button" data-close aria-label="@lang('admin::views.Close modal')" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    @endif
</div>