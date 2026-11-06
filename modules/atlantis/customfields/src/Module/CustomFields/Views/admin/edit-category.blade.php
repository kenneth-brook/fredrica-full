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
            {!! Form::open(['url' => 'admin/modules/customfields/edit-category/' . $model->id , 'data-abide' => '', 'novalidate'=> '']) !!}
            <div class="row">
                <div class="columns">
                    <div class="float-right">
                        <div class="buttons">
                            <a href="/admin/modules/customfields/categories" class="back button tiny top primary" title="Go to Categories"
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
                            <a href="#panel1" aria-selected="true">Edit Custom Field Category</a>
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">
                            <div class="row">
                                <div class="columns large-8">
                                    <div class="row">
                                        <div class="columns">
                                            <label for="name">Category Name <span class="form-error">is required.</span>
                                                <span class="icon icon-Help top" data-tooltip title="Name for the custom field category."></span>
                                                {!! Form::input('text', 'category_name', old('category_name', $model->category_name), ['id'=>'category_name']) !!}
                                            </label>
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
                    <li class="accordion-item is-active" data-accordion-item>
                        <a href="#" class="accordion-title">Tip</a>
                        <div class="accordion-content" data-tab-content>
                            Assigning the custom field category to a theme template file will result in only
                            fields assigned to that category to show on add/edit pages in the CMS.
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
