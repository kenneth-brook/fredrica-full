@extends('atlantis-admin::admin-shell')

@section('scripts')
    @parent
    {{-- Add scripts per template --}}
    {{-- <script src="http://a3.angel.dev.gentecsys.net/media/js/vendor/jquery.js"></script> --}}
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
                    <h1 class="huge page-title">Blog</h1>
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
                    <div class="float-right">
                        <!-- <div class="buttons">
                                <a id="save-close-btn" class="alert button" href="#">New Page</a>
                        </div> -->
                    </div>
                </div>
            </div>
        </section>
        <section class="pages-list">
            <div class="row">
                <div class="columns small-12">
                    <ul class="tabs" data-tabs id="example-tabs">
                        <li class="tabs-title is-active main">
                            <a href="#panel1" aria-selected="true">
                                All Entries
                            </a>
                        </li>
                        <!-- <li class="tabs-title main">
                          <a href="#panel2">
                            Recently Used
                          </a>
                        </li> -->
                        <li class="float-right list-filter">
                            <a data-open="wpXml" class="hollow button primary"><i class="fa fa-wordpress" aria-hidden="true"></i> Wordpress Importer</a>
                            <a data-open="configBlog" class="alert button">Config</a>
                            <a class="alert button" href="{{url('/')}}/admin/modules/blog/categories">Categories</a>
                            <a id="save-close-btn" class="alert button" href="{{url('/')}}/admin/modules/blog/add">New Entry</a>
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">
                            {!! DataTable::set(\Module\Blog\Controllers\Admin\EntriesDataTable::class) !!}
                        </div>
                        <!--<div class="tabs-panel" id="panel2">
                        </div> -->
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>

        <div class="row">
            <div class="columns">
            </div>
        </div>
        @include("blog-admin::admin.includes.wp-importer")
        

        <div class="reveal" id="configBlog" data-reveal>
            {!! Form::open(['url' => 'admin/modules/blog/update-config']) !!}
            <h1>Config</h1>

            <label for="anchor_url">Anchor Url
                {!! Form::select('anchor_url', $pages_list , isset($config['anchor_url']) ? $config['anchor_url'] : NULL, ['id'=>'anchor_url']) !!}
            </label>

            <label for="anchor_url">Single Entry Url
                {!! Form::select('single_url', $pages_list , isset($config['single_url']) ? $config['single_url'] : NULL, ['id'=>'single_url']) !!}
            </label>

            <label for="anchor_url">Categories Url
                {!! Form::select('categories_url', $pages_list, isset($config['categories_url']) ? $config['categories_url'] : NULL, ['id'=>'categories_url']) !!}
            </label>

            <label for="anchor_url">Archives Url
                {!! Form::select('archives_url', $pages_list, isset($config['archives_url']) ? $config['archives_url'] : NULL, ['id'=>'archives_url'])  !!}
            </label>

            <label for="datetime_format">Date Format
                {!!  Form::select('datetime_format', $datetime_formats, isset($config['datetime_format']) ? $config['datetime_format']: '%A, %B %e, %Y') !!}
            </label>

            <label for="datetime_format">Blog Entries Per Page
                {!!  Form::select('per_page', ['5' => 5, '10' => 10, '15' => 15, '20' => 20], isset($config['per_page']) ? $config['per_page']: 5) !!}
            </label>

            <label for="datetime_format">Comment Engine
                {!!  Form::select('comment_engine', $comment_engines, isset($config['comment_engine']) ? $config['comment_engine']: '') !!}
            </label>

            <button class="close-button" data-close aria-label="Close modal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <input type="submit" name="_update_config" value="Update" id="update-btn" class="success button">
            {!! Form::close() !!}
        </div>
        @include("blog-admin::admin.includes.bulb")
    </footer>
@stop