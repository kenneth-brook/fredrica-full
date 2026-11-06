@extends('atlantis-admin::admin-shell')

@section('title')
@lang('admin::views.Add Role') | @lang('admin::views.A3 Administration') | {{ config('atlantis.site_name') }}
@stop

@section('scripts')
@parent
{{-- Add scripts per template --}}
@stop

@section('styles')
@parent
{{-- Add styles per template --}}
@stop

@section('content')
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">@lang('admin::views.Add Role')</h1>
        @if (isset($msgInfo))
        <div class="callout warning">
          <h5>{!! $msgInfo !!}</h5>
        </div>
        @endif
        @if (isset($msgSuccess))
        <div class="callout success">
          <h5>{!! $msgSuccess !!}</h5>
        </div>
        @endif
        @if (isset($msgError))
        <div class="callout alert">
          <h5>{!! $msgError !!}</h5>
        </div>
        @endif
      </div>
    </div>
  </section>
  <section class="editscreen">
    {!! Form::open(['url' => 'admin/roles/add', 'data-abide' => '', 'novalidate'=> '']) !!}
    <div class="row">
      <div class="columns">
        <div class="float-right">
          <div class="buttons">
            <a href="admin/roles" class="back button tiny top primary" title="@lang('admin::views.Go to Roles')" data-tooltip>
              <span class=" back icon icon-Goto"></span>
            </a>
            {!! Form::input('submit', '_save_close', trans("admin::views.Save & Close"), ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
            {!! Form::input('submit', '_update', trans("admin::views.Update"), ['class' => 'alert button', 'id'=>'update-btn']) !!}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <!-- data-status: active, disabled or dev -->
            <a href="#panel1" aria-selected="true">@lang('admin::views.New Role')</a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">

            <div class="row">
              <div class="columns large-12">
                <div class="row">
                  <div class="columns medium-4">
                    @if ($errors->get('name'))
                    <label for="name" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('name')[0] }}</span>
                      {!! Form::input('text', 'name', old('name'), ['class' => 'is-invalid-input', 'id'=>'name']) !!}
                    </label>
                    @else
                    <label for="name">@lang('admin::views.Name') <span class="form-error">@lang('admin::views.is required.')</span>
                      {!! Form::input('text', 'name', old('name'), ['id'=>'name', 'required'=>'required']) !!}
                    </label>
                    @endif
                  </div>
                  <div class="columns medium-8">
                    @if ($errors->get('description'))
                    <label for="description" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('description')[0] }}</span>
                      {!! Form::input('text', 'description', old('description'), ['class' => 'is-invalid-input', 'id'=>'description']) !!}
                    </label>
                    @else
                    <label for="description">@lang('admin::views.Description') <span class="form-error">@lang('admin::views.is required.')</span>
                      {!! Form::input('text', 'description', old('description'), ['id'=>'description', 'required'=>'required']) !!}
                    </label>
                    @endif
                  </div>
                  
                  <div class="columns">
                    <label>@lang('admin::views.Permissions')</label>
                  </div>
                 @foreach ($aAdminItems as $admin_item_key => $admin_item)
                  <div class="columns medium-4">
                    <div class="switch tiny">                      
                      {!! Form::checkbox('admin_items[]', $admin_item_key, $admin_item['checked'], ['class' => 'switch-input', 'id' => 'admin_item_' . $admin_item_key]) !!}
                      <label class="switch-paddle" for="admin_item_{!! $admin_item_key !!}"></label>
                      <i>{{ $admin_item['name'] }}</i>
                    </div>
                  </div>
                  @endforeach

                  <div class="columns">
                    <label>@lang('admin::views.Module Permissions')</label>
                  </div>
                  @foreach ($aModules as $module_item_key => $module_item)
                  <div class="columns medium-4">
                    <div class="switch tiny">                      
                      {!! Form::checkbox('modules_items[]', $module_item_key, $module_item['checked'], ['class' => 'switch-input', 'id' => 'admin_item_' . $module_item_key]) !!}
                      <label class="switch-paddle" for="admin_item_{!! $module_item_key !!}"></label>
                      <i>{{ $module_item['name'] }}</i>
                    </div>
                  </div>
                  @endforeach

                  <div class="columns medium-4 end"></div>
                </div>
              </div>            
            </div>
          </div>
        </div>
      </div>
    </div>
    {!! Form::close() !!}
  </section>
</main>
<footer>
  
  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
@stop