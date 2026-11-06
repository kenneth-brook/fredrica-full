
<?php $modules = $sidebar_tabs; ?>
@if (count($modules))
<aside id="sidebarWrapper">
    <div class="dataTables_wrapper">
        <div class="dataTables_processing" style="display: none;">
        </div>
    </div>
    <div class="row column clearfix actions">
        <a href="javascript:void(0);" class="float-right sidebar-toggle">
            <span class="fa fa-close" aria-hidden="true"></span>
        </a>
        <a href="javascript:void(0);" class="float-right sidebar-pin">
            <span class="fa fa-thumb-tack fa-x2" aria-hidden="true"></span>
        </a>

    </div>
    <div id="sidebarNav">
        <ul class="modules-list vertical tabs" data-tabs id="example-tabs">
            <a href="javascript:void(0);" class="sidebar-toggle toggle-small-screen">
                <span class="icon icon-FullScreen"></span>
            </a>
            @foreach ($modules as $m)
            <?php if (substr($m['sidebar_icon'], 0, 1) === '/')
            {

                $icon = '<img class="module-icon" src="'. url('/') . '/modules/'.$m['module']['path'].$m['sidebar_icon'].'">';
            }
            else
            {
                $icon = '<span class="'.$m['sidebar_icon'].'"></span>';
            }?>
            <li class="tabs-title" id="sidebar-module-{{$m['module']['id']}}">
                <a class="left"
                data-action="{{ url('/') }}/{{$m['sidebar_action']}}"
                data-id="{{$m['module']['id']}}"
                href="#module-tab-{{$m['module']['id']}}"
                data-tooltip
                title="{{$m['sidebar_name']}}"
                data-page-id="{{ $oPage->id }}"
                data-version-id="{{ $oPage->version_id }}"
                data-page-url="{{ $oPage->url }}">
                {!! $icon !!}
            </a>
        </li>
        @endforeach
        <!-- <li class="tabs-title">
            <a data-href="" href="#milen" class="button hollow left tiny" data-tooltip title="fake">    <span class="icon icon-Mail left"></span>
            </a>
            <span class="label alert rounded notification">1</span>
        </li> -->
    </ul>
</div>
<div class="sidebar-content-wrapper">
    <div class="dataTables_wrapper">
        <div class="tabs-content a3" data-tabs-content="example-tabs">
            @foreach ($modules as $m)
            <div class="tabs-panel" id="module-tab-{{$m['module']['id']}}">
                {{-- Module content --}}
            </div>
            @endforeach
       </div>
   </div>
</div>
</aside>

@section('js')
@parent
<script type="text/javascript">
    $(document).ready(function() {
        function toggleMenu() {
            $('.sidebar-toggle').click(function(e) {
                $('body').toggleClass("sidebar-expand");
                if ($('body').hasClass("sidebar-pinned")) {
                    $('body').removeClass("sidebar-pinned");
                }
                else {}
                    e.preventDefault();
            });
            $('.sidebar-pin').click(function(e) {
                $('body').toggleClass("sidebar-pinned");
                e.preventDefault();
            });
            $('.modules-list .tabs-title>a').click(function(e) {
                if (!$('body').hasClass("sidebar-expand")) {
                    $('body').addClass("sidebar-expand");
                    $('[role="tooltip"]').hide();
                }
            });
        }

        $(document).ready(function() {
            toggleMenu();
            atlantisUtilities.Sidebar.init();
        });
    });

    atlantisUtilities.Sidebar = {
        config : {
            currentModule : {
                name: 0,
                isLoaded : 0,
            },
            isOpened : false,
        },
        modules:{

        },
        getModuleData : function(url, container, module, params) {
            var _this = atlantisUtilities.Sidebar;
            if (typeof _this.modules[module] != 'undefined'){
                if (_this.modules[module].isLoaded == true) {
                    return;
                }
            }
            _this.modules[module] = {};
            _this.showLoader();

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data : params,
                headers: {
                    'X-CSRF-TOKEN': $('[name="_token"]').val()
                }
            })
            .done(function(response) {
                
                _this.modules[module+''].isLoaded = true;
                $(container).html(response.view);
                
            })
            .fail(function() {
                _this.modules[module+''].isLoaded = false;
                

            })
            .always(function() {
                _this.hideLoader();
                
            });

        },
        showLoader : function () {
            $('#sidebarWrapper .dataTables_processing').show();
        },
        hideLoader : function () {
            $('#sidebarWrapper .dataTables_processing').hide();
        },
        init : function(){
            var _this = atlantisUtilities.Sidebar;
            $('.modules-list .tabs-title>a').click(function(event) {
                var module = $(this).attr('data-id');
                var container = $(this).attr('href');
                var url = $(this).attr('data-action');
                var params = {
                    page_id : '{{ $oPage->page_id }}',
                    version_id : '{{ $oPage->version_id }}',
                    language : '{{ $oPage->language }}',
                    page_url : '{{ $oPage->url }}'
                }
                _this.getModuleData(url , container, module, params)
            });
        }
    }

</script>
@stop

@endif