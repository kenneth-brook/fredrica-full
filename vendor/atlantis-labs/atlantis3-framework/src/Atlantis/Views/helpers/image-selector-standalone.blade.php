
<div class="row gal-selector" id="gal-selector-{{$rand_id}}">
    <div class="columns">
        @if ($multi_images)
        <a class="button" data-open="image-selector-modal-{{$rand_id}}">@lang('admin::views.Add/Edit Files')</a>
        @else
        <a class="button" data-open="image-selector-modal-{{$rand_id}}">@lang('admin::views.Add/Edit Files')</a>
        @endif
        <div class="callout gal-container image-preview">
            @foreach ($images as $im)
            @if (!empty($im['thumbnail']))
            <img data-id="{!! $im['id'] !!}" src="{!! $im['thumbnail'] !!}">
            @elseif (!is_null($im) || $im != '')
            <span class="item">
                <em data-id="{!! $im['id'] !!}" class="icon icon-File application">
                  <em class="name">
                    <br>ID: {!! $im['id'] !!}<br>{!! $im['filename'] !!}
                </em>
            </em>
        </span>
        @endif
        @endforeach
    </div>
</div>


<div class="reveal full" id="image-selector-modal-{{$rand_id}}" data-reveal>
    <button class="close-button" data-close aria-label="@lang('admin::views.Close modal')" type="button">
        <span aria-hidden="true">&times;</span>
    </button>

    <div class="columns">
        <h3>@lang('admin::views.Select File')</h3>
    </div>


    <div class="columns large-7" style="min-height: 80vh;">
        <ul class="tabs" data-tabs id="example-tabs4">
            <li class="tabs-title is-active">
                <a href="#panela-{{$rand_id}}" aria-selected="true">@lang('admin::views.Choose from existing media')</a>
            </li>
            <li class="tabs-title">
                <a href="#panelb-{{$rand_id}}">@lang('admin::views.or upload new files')</a>
            </li>
        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
            <div class="tabs-panel is-active" id="panela-{{$rand_id}}">
                {!! DataTable::set(\Atlantis\Controllers\Admin\MediaWithFilesDataTable::class) !!}
            </div>
            <div class="tabs-panel" id="panelb-{{$rand_id}}">


                <div class="uploader" id="uploader-{{$rand_id}}">
                    @lang('admin::views.Uploader')
                </div>
                <div class="row">
                    <div class="columns large-8">

                        {!! Form::select('resize', $aResize, $resize_option, ['id' => 'resize']) !!}

                    </div>
                    <div class="columns large-4">
                        <a class="button alert float-right" onclick="$('.uploader').plupload('start')">@lang('admin::views.Upload')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="columns large-5">
        <label for="description">@lang('admin::views.Files')
            <span class="icon icon-Help top" data-tooltip title="@lang('admin::views.First Image in Gallery will be used as Featured Image')"></span>
        </label>

        @if ($multi_images)
        <div class="callout gal-container" id="gal-container-{{$rand_id}}" data-input-naming="{{$name}}">
            @if (count($images))
            @foreach ($images as $im)
            @if (!empty($im['thumbnail']))
            <span class="item">
              <img data-id="{!! $im['id'] !!}" src="{!! $im['thumbnail'] !!}">
              <a class="rmv-btn" title="remove" data-remove="{!! $im['id'] !!}"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>
              <a class="edit-btn" title="edit" target="_blank" href="{{url('/')}}/admin/media/media-edit/{!! $im['id'] !!}"><i class="fa fa fa-pencil" aria-hidden="true"></i></a>
              <input type="hidden" name="{{$name}}" value="{!! $im['id'] !!}">
          </span>
          @elseif (!is_null($im) || $im != '')
          <span class="item">
              <em data-id="{!! $im['id'] !!}" class="icon icon-File application"><em class="name"><br>ID: {!! $im['id'] !!}<br>{!! $im['filename'] !!}</em></em>
              <a class="rmv-btn" title="remove" data-remove="{!! $im['id'] !!}"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>
              <a class="edit-btn" title="edit" target="_blank" href="{{url('/')}}/admin/media/media-edit/{!! $im['id'] !!}"><i class="fa fa fa-pencil" aria-hidden="true"></i></a>
              <input type="hidden" name="{{$name}}" value="{!! $im['id'] !!}">
          </span>
          @else 
            <input type="hidden" name="{{$name}}" value="0">
          @endif
          @endforeach
          @endif

      </div>
      @else
      <div class="callout gal-container" id="gal-container-{{$rand_id}}" data-single-image data-input-naming="{{$name}}">


        @if (count($images) !=0 )
        @foreach ($images as $im)
        @if (!empty($im['thumbnail']))
        <span class="item">
          <img data-id="{!! $im['id'] !!}" src="{!! $im['thumbnail'] !!}">
          <a class="rmv-btn" title="remove" data-remove="{!! $im['id'] !!}"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>
          <a class="edit-btn" title="edit" target="_blank" href="{{url('/')}}/admin/media/media-edit/{!! $im['id'] !!}"><i class="fa fa fa-pencil" aria-hidden="true"></i></a>
          <input type="hidden" name="{{$name}}" value="{!! $im['id'] !!}">
      </span>
      @elseif (!is_null($im) || $im != '')
      <span class="item">
          <em data-id="{!! $im['id'] !!}" class="icon icon-File application"><em class="name"><br>@lang('admin::views.ID:') {!! $im['id'] !!}<br>{!! $im['filename'] !!}</em></em>
          <a class="rmv-btn" title="remove" data-remove="{!! $im['id'] !!}"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>
          <a class="edit-btn" title="edit" target="_blank" href="{{url('/')}}/admin/media/media-edit/{!! $im['id'] !!}"><i class="fa fa fa-pencil" aria-hidden="true"></i></a>
          <input type="hidden" name="{{$name}}" value="{!! $im['id'] !!}">
      </span>
      @endif
      @endforeach
      @else 
      <input type="hidden" name="{{$name}}" value="0">
      @endif

  </div>
  @endif

  <div class="row">
    <div class="columns">
        <a class="button alert select-image-done">Done</a>
    </div>
</div>
</div>
</div>
</div>


{!! Html::style('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/css/jquery.ui.plupload.css') !!}

<script type="text/javascript">   
    var gallerySelectorBuild = function(event) {
        @if($multi_images)
        var multi_selection = true;
        var max_file_count = 0;
        @else
        var multi_selection = false;
        var max_file_count = 1;
        @endif


          var JQUERYUI;
        jQuery.ui ? JQUERYUI = null : JQUERYUI = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js';

        $.getScript(JQUERYUI);

        $.getScript('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/jquery-sortble/jquery-sortable.js', function(data, textStatus, jqxhr) {

            $(".gal-container").not('.image-preview').sortable({
                containerSelector: 'div',
                itemSelector: 'span.item',
                tolerance: 0,
                placeholder: 'placeholder item',
                distance: 10,
                delay:150
            });

        });

        $.getScript('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/plupload.full.min.js', function(data, textStatus, jqxhr) {

            $.getScript('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/plupload-2.1.8/js/jquery.ui.plupload/jquery.ui.plupload.js', function(data, textStatus, jqxhr) {

                $(function() {
                    $("#uploader-{{$rand_id}}").plupload({
                        runtimes: 'html5,flash,silverlight,html4',
                        url: "{{ url('/') }}/admin/media/media-add",
                        headers: {
                            "x-csrf-token": "{{ csrf_token() }}"
                        },
                        max_file_size: "{!! intval(config('atlantis.allowed_max_filesize')) !!}mb",
                        chunk_size: '1mb',
                        multi_selection: multi_selection,
                        max_file_count: max_file_count,
                        filters: [
                        { title: "Image files", extensions: "{{ implode(',', config('atlantis.allowed_image_extensions')) }}" },
                        { title: "Zip files", extensions: "{{ implode(',', config('atlantis.allowed_others_extensions')) }}" }
                        ],
                        rename: true,
                        sortable: true,
                        dragdrop: true,
                        views: {
                            list: false,
                            thumbs: true,
                            active: 'thumbs'
                        },
                        buttons: {
                            start: false,
                            stop: false
                        },
                        preinit: {
                            BeforeUpload: function(up, file) {
                                var multipart_params =  {
                                    "filename" : "",
                                    "tags" : "",
                                    "credit" : "",
                                    "alt" : "",
                                    "weight" : "1",
                                    "css" : "",
                                    "anchor_link" : "",
                                    "resize" : $('#gal-selector-{{$rand_id}} [name="resize"]').val(),
                                    "caption" : "",
                                    "description" : "",
                                };
                                multipart_params.resize = $('#gal-selector-{{$rand_id}} [name="resize"]').val();
                                up.setOption('multipart_params', multipart_params);     
                            },
                            fileUploaded: function(up, file, response) {
                                var obj = jQuery.parseJSON(response.response);
                                if (response.status == 200) {
                                    var id = obj.id;
                                    var src = obj.thumbnail_path;
                                    if (obj.thumbnail_path != "") {
                                        var img = $('<img />', {
                                            'data-id': id,
                                            'src': src
                                        });
                                    } else {
                                        var name = '<em class="name"><br>ID: ' + id + '<br>' + obj.target_name + '</em>';
                                        var img = $('<span />', {
                                            'data-id': id,
                                            class: 'icon icon-File '
                                        });
                                        img = img.append(name);
                                    }
                                    var rmvBtn = '<a class="rmv-btn" title="remove" data-remove="' + id + '"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>';
                                    var editBtn = '<a class="edit-btn" title="edit" target="_blank" href="{{url('/')}}/admin/media/media-edit/' + id + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                                    var imgIds = '<input type="hidden" name="{{$name}}" value="' + id + '">';
                                    item = img.wrap('<span class="item"></span>').parent();
                                    
                                    //item.appendTo($('#gal-container'));
                                    var galleryContainer = $('#gal-container-{{$rand_id}}');
                                    if (typeof galleryContainer.attr('data-single-image') !== typeof undefined && galleryContainer.attr('data-single-image') !== "false") {
                                        galleryContainer.find('.rmv-btn').click();
                                        galleryContainer.html(item);
                                    } else {
                                        item.appendTo(galleryContainer);
                                    }
                                    $(rmvBtn).appendTo(item);
                                    $(editBtn).appendTo(item);
                                    $(imgIds).appendTo(item);
                                }
                            }
                        },
                        flash_swf_url: '/plupload/js/Moxie.swf',
                        silverlight_xap_url: '/plupload/js/Moxie.xap'
                    });
                });
            });
        });

    $(document).off('click' , '.select-image-done');
    $(document).on('click', '.select-image-done', function(ev) {
        items = $(this).closest('.gal-selector').find('[id*="gal-container-"] .item').clone();
        galleryContainer = $(this).closest('.gal-selector');

        $.each(items, function(i, item) {
            $(items[i]).find('[type="hidden"]').remove();
            $(items[i]).find('a.edit-btn').remove();
            $(items[i]).find('a.rmv-btn').remove();
            $(item).wrap('<span class="item "></span>');
        });
        $(this).closest('.gal-selector').find('.image-preview').html('');
        $(this).closest('.gal-selector').find('.image-preview').html(items).addClass('callout gal-container');
        $(this).closest('.gal-selector').find('.reveal').foundation('close');
    });
}

/*extended atlantisUtilities.reflow.imageSelector If image selector called ondemand  */
if (typeof atlantisUtilities != 'undefined') {
    atlantisUtilities.reflow.imageSelector = gallerySelectorBuild;
}
/*build gallery selector if called ondemand */
else{

    document.addEventListener("DOMContentLoaded", function(event) {
        gallerySelectorBuild();
    });
}
</script>
