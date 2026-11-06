@extends('atlantis-admin::admin-shell')

@section('styles')
    @parent
    {{-- Add styles per template --}}
@stop

@section('content')
    <main>
        <section class="greeting">
            <div class="row">
                <div class="columns ">
                    <h1 class="huge page-title">Add Category</h1>
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
            {!! Form::open(array('url' => url('/').'/admin/modules/blog/category-add', 'data-abide' => '', 'novalidate'=> '')) !!}
            <div class="row">
                <div class="columns">
                    <div class="float-right">
                        <div class="buttons">
                            <a href="{{url('/')}}/admin/modules/blog/categories" class="back button tiny top primary" title="Go to Category list"
                               data-tooltip>
                                <span class="back icon icon-Goto"></span>
                            </a>
                            {!! Form::input('submit', '_save_close', 'Save & Close', ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="columns small-12">
                    <ul class="tabs" data-tabs id="example-tabs">
                        <li class="tabs-title is-active main">
                            <!-- data-status: active, disabled or dev -->
                            <a href="#panel1" aria-selected="true">New Category</a>
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">

                            <div class="row">
                                <div class="columns large-9">
                                    <div class="row">
                                        <div class="columns medium-4">
                                            <label for="category_name">Category Name <span class="form-error">is required.</span>
                                                {!! Form::input('text', 'category_name', old('category_name'), ['required'=>'required', 'id'=>'category_name']) !!}
                                            </label>
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
        @include("blog-admin::admin.includes.bulb")
    </footer>
@stop
