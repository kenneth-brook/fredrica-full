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
                                All Categories
                            </a>
                        </li>
                        <!-- <li class="tabs-title main">
                          <a href="#panel2">
                            Recently Used
                          </a>
                        </li> -->
                        <li class="float-right list-filter">
                            <a href="{{url('/')}}/admin/modules/blog" class="back button tiny top primary" title="Go to Blog list" data-tooltip>
                                <span class=" back icon icon-Goto"></span>
                            </a>
                            <a id="save-close-btn" class="alert button" href="{{url('/')}}/admin/modules/blog/category-add">New Category</a>
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">
                            {!! DataTable::set(\Module\Blog\Controllers\Admin\CategoriesDataTable::class) !!}
                        </div>
                        <!--<div class="tabs-panel" id="panel2">
                        </div> -->
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer>
        @include("blog-admin::admin.includes.bulb")
    </footer>
@stop