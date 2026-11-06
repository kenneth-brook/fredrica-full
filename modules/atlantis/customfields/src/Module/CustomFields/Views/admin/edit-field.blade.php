@extends('atlantis-admin::admin-shell')
@section('content')
    <main>
        <section class="greeting">
            <div class="row">
                <div class="columns ">
                    <h1 class="huge page-title">Custom Fields</h1> @if (isset($msgInfo))
                        <div class="callout warning">
                            <h5>{!! $msgInfo !!}</h5>
                        </div>
                    @endif @if (isset($msgSuccess))
                        <div class="callout success">
                            <h5>{!! $msgSuccess !!}</h5>
                        </div>
                    @endif
                    @if (isset($msgError))
                        <div class="callout alert">
                            <h5>{!! $msgError !!}</h5>
                        </div>
                    @endif
                    @if(count($errors->all()))
                        <div class="alert callout">
                            @foreach($errors->all() as $error)
                                <span>{{ $error }}</span><br />
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
        <section class="editscreen">
            {!! Form::open(['url' => 'admin/modules/customfields/edit-field/' . $model->id , 'data-abide' => '', 'novalidate'=> '']) !!}
            <div class="row">
                <div class="columns">
                    <div class="float-right">
                        <div class="buttons">
                            <a href="/admin/modules/customfields/" class="back button tiny top primary" title="Go to Custom Fields"
                               data-tooltip>
                                <span class=" back icon icon-Goto"></span>
                            </a>
                            {!! Form::input('submit', '_save_close', 'Save & Close', ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
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
                            <a href="#panel1" aria-selected="true">Edit Custom Field</a>
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">
                            <div class="row">
                                <div class="columns large-8">
                                    <div class="row">
                                        <div class="columns">
                                            <label for="name">Field Label <span class="form-error">is required.</span>
                                                <span class="icon icon-Help top" data-tooltip title="Label for the custom field."></span>
                                                {!! Form::input('text', 'field_label', old('field_label', $model->field_label), ['id'=>'field_label', 'readonly'=>'']) !!}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="columns">
                                            <label for="name">Field Type<span class="form-error">is required.</span>
                                                <span class="icon icon-Help top" data-tooltip title="Choose type for the custom field."></span>
                                                   
                                                    {!! Form::select('field_type', ['' => ''] + [
                                                                'single_line' => trans('Single Line Text'),
                                                                'multi_line'  => trans('Multi Line Text With Editor'),
                                                                'image' => trans('Single Image Browser'),
                                                                'gallery' => trans('Gallery Browser'),
                                                                'datepicker' => trans('Datepicker')
                                                            ], $model->field_type, ['disabled'=>'']) !!}
                                            </label>
                                        </div>
                                    </div>
                                    @if(count($categories))
                                    <div class="row">
                                        <div class="columns">
                                            <label for="categories">Select Category
                                                {!! Form::select('category_id', ['' => ''] + $categories, $model->category_id, ['disabled'=>''] ) !!}
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </section>
    </main>
@stop
