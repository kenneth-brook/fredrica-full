@extends('atlantis-admin::admin-shell')

  @section('content')
  
  <main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">        
        <h1 class="huge page-title">Vanity Url</h1>
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
  <section class="pages-list">
    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <a href="#panel1" aria-selected="true">
              List
            </a>
          </li>
          <!-- <li class="tabs-title main">
            <a href="#panel2">
              Recently Used
            </a>
          </li> -->
          <li class="float-right list-filter">

            <a id="save-close-btn" class="alert button" href="{{ url('/') }}/admin/modules/vanityurl/add">Add URL</a>
          </li>	
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            {!! DataTable::set(\Module\VanityUrl\Controllers\Admin\VanityDataTable::class) !!}
          </div>
          <!--<div class="tabs-panel" id="panel2">
          </div> -->
        </div>
      </div>
    </div>
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
            Adding a wildcard is useful if you want to redirect users whenever the URL they follow starts with some particular characters. To add a wildcard, you can add the <b>:any</b> character at the end of the URL.
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