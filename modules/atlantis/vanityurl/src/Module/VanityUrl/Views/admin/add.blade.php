@extends('atlantis-admin::admin-shell')

@section('title')
Add Category| A3 Administration | {{ config('atlantis.site_name') }}
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
        <h1 class="huge page-title">Add Url</h1>
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
    {!! Form::open(['url' => 'admin/modules/vanityurl/add', 'data-abide' => '', 'novalidate'=> '']) !!}
    <div class="row">
      <div class="columns">
        <div class="float-right">
          <div class="buttons">
            <a href="{{ url('/') }}/admin/modules/vanityurl" class="back button tiny top primary" title="Go to List" data-tooltip>
              <span class=" back icon icon-Goto"></span>
            </a>
            {!! Form::input('submit', '_save_close', 'Save &amp; Close', ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
            {!! Form::input('submit', '_update', 'Update', ['class' => 'alert button', 'id'=>'update-btn']) !!}
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <!-- data-status: active, disabled or dev -->
            <a href="#panel1" aria-selected="true">New Vanity Url</a>
          </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">

            <div class="row">
              <div class="columns large-9">
                <div class="row">
                  <div class="columns medium-4">
                    @if ($errors->get('source_url'))
                    <label for="source_url" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('source_url')[0] }}</span>
                      {!! Form::input('text', 'source_url', old('source_url'), ['class' => 'is-invalid-input', 'id'=>'source_url']) !!}
                    </label>
                    @else
                    <label for="source_url">Source URL <span class="form-error">is required.</span>
                      {!! Form::input('text', 'source_url', old('source_url'), ['id'=>'source_url', 'required'=>'required']) !!}
                    </label>
                    @endif
                  </div>
                  <div class="columns medium-4 end">
                    @if ($errors->get('dest_url'))
                    <label for="dest_url" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('dest_url')[0] }}</span>
                      {!! Form::input('text', 'dest_url', old('dest_url'), ['class' => 'is-invalid-input', 'id'=>'dest_url']) !!}
                    </label>
                    @else
                    <label for="dest_url">Destination URL <span class="form-error">is required.</span>
                      {!! Form::input('text', 'dest_url', old('dest_url'), ['id'=>'dest_url', 'required'=>'required']) !!}
                    </label>
                    @endif
                  </div>                                   
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
  <div class="helper">
    <button type="button" class="icon icon-Bulb" data-panel-toggle="tips-panel"></button>
    <div class="right-panel side-panel" id="tips-panel" data-atlantis-panel>
      <ul class="accordion" data-accordion>

        <li class="accordion-item" data-accordion-item>
          <a href="#" class="accordion-title">Adding wildcards to Source URLs</a>
          <div class="accordion-content" data-tab-content>
            Adding a wildcard is useful if you want to redirect users whenever the URL they follow starts with some particular characters. To add a wildcard, you can add the <b>:any</b> characters at the end of the URL.
            For example, the source URL <em>goo:any</em> would redirect users visiting any page that starts with goo such as /google /goofle and so on to the destination URL.
          </div>
        </li>
      </ul>
    </div>
  </div>
  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
@stop