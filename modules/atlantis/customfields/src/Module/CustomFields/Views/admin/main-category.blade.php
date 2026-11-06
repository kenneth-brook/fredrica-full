@extends('atlantis-admin::admin-shell')

@section('content')

    <main>
        <section class="greeting">
            <div class="row">
                <div class="columns ">
                    <h1 class="huge page-title">Custom Fields</h1>
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
                                <a id="save-close-btn" class="alert button" href="#">New</a>
                        </div> -->
                    </div>
                </div>
            </div>
        </section>
        <section class="pages-list editscreen">
            <div class="row">
                <div class="columns small-12">
                    <ul class="tabs" data-tabs id="example-tabs">
                        <li class="tabs-title is-active main">
                            <a href="#panel1" aria-selected="true">
                                Custom Fields Categories
                            </a>
                        </li>
                        <!-- <li class="tabs-title main">
                          <a href="#panel2">
                            Recently Used
                          </a>
                        </li> -->
                        <li class="float-right list-filter">
                            <a href="/admin/modules/customfields" class="back button tiny top primary" title="Go to Custom Fields" data-tooltip>
                                <span class=" back icon icon-Goto"></span>
                            </a>
                            <a id="save-close-btn" class="alert button" href="/admin/modules/customfields/add-category">Add Category</a>
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">
                            {!! \Atlantis\Helpers\DataTable::set(Module\CustomFields\Models\CustomFieldsCategoriesDataTable::class) !!}
                        </div>
                        <!--<div class="tabs-panel" id="panel2">
                        </div> -->
                    </div>
                </div>
            </div>
        </section>
    </main>
@stop