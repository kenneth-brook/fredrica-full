<script type="text/javascript">
    // Initialize the widget when the DOM is ready
    document.addEventListener("DOMContentLoaded", function (event) {

        $.fn.serializeObject = function()
        {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };

        var useSmartCrop = {{ (config('atlantis.use_smart_crop') == 1) ? 'true' : 'false' }};
        var filefinished = false;
        var url = "{{ $action == 'add' ? url('/').'/admin/media/media-add' : url('/').'/admin/media/media-edit/'.$media->id }}";
        var sizes = [];
        <?php $index = 0;
            $sizes = array_merge(config('atlantis.static_images'), config('atlantis.responsive_images'));
            ?>
                @foreach($aResize as $i=>$r)
                @if ($i != '')
            sizes[{{$index}}] = {
            name : '{{$r}}',
            width : {{$sizes[$i]['thumbnail']['width']}},
            height: {{$sizes[$r]['thumbnail']['height']}},
            crop: {{ $sizes[$r]['thumbnail']['crop'] == false ? 0 : $sizes[$r]['thumbnail']['crop']}}
        };
        @endif
        <?php $index++; ?>
        @endforeach

        var uploader = $("#uploader").plupload({
            // General settings
            runtimes: 'html5,flash,silverlight,html4',
            url: url,
            headers: {
                "x-csrf-token": "{{ csrf_token() }}"
            },
            // Maximum file size
            max_file_size: "{!! intval(config('atlantis.allowed_max_filesize')) !!}mb",
            chunk_size: '1mb',
            // Specify what files to browse for
            filters: [
                {title: "@lang('admin::views.Image files')", extensions: "{{ implode(',', config('atlantis.allowed_image_extensions')) }}"},
                {title: "@lang('admin::views.Zip files')", extensions: "{{ implode(',', config('atlantis.allowed_others_extensions')) }}"}
            ],
            // Rename files by clicking on their titles
            rename: true,
            // Sort files
            sortable: true,
            // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
            dragdrop: true,
            // Views to activate
            views: {
                list: false,
                thumbs: true, // Show thumbs
                active: 'thumbs'
            },
            buttons: {
                start :{{ $action == 'add' ? 'false' : 'true' }},
                stop:false
            },
            multi_selection: {{ $action == 'add' ? 'true' : 'false' }},
            prevent_duplicates: {{ $action == 'add' ? 'false' : 'true' }},
            max_file_count: {{ $action == 'add' ? '0' : '1' }},
            multipart_params : $('#media-form').serializeObject(),

            preinit : {
                BeforeUpload: function (up, file) {

                    $('#media-form :input').prop('readonly', true);
                    var fileType = file.getNative().type.split('/')[0];
                    if(useSmartCrop && fileType == 'image'){

                        if (!filefinished) {
                            up.stop();

                            cropSmart().handleFiles(file.getNative(), function(result) {

                                var multipart_params =  $('#media-form').serializeObject();

                                multipart_params.smartcrop = result.topCrop;

                                up.setOption('multipart_params', multipart_params);

                                /*crop calculations finished and start uploader */
                                filefinished = true;
                                $('#uploader').plupload('start');
                            });
                            return false;
                        }

                        if (!filefinished) {
                        }
                    }
                    else{
                        var multipart_params =  $('#media-form').serializeObject();
                        up.setOption('multipart_params', multipart_params);
                    }
                },
                UploadFile: function(up, file) {
                    //up.setOption('multipart_params', $('#media-form').serializeObject());
                },
                FileUploaded: function(up, file, info) {
                    /*reset the value and get ready to start the new file*/
                    filefinished = false;
                    var response = jQuery.parseJSON(info.response);

                    if (response.success != null) {

                    } else if (response.error != null) {
                        var error = {
                            code: -601,
                            message: response.error,
                            file: file,
                            response: response,
                            status: response.status,
                            responseHeaders: response.responseHeaders
                        }
                        up.trigger('Error', error);
                    }
                },
                UploadComplete: function(up, file) {
                    $('#media-form :input').prop('readonly', false);
                    window.location.href="{{ url('/') }}/admin/media";
                }
            },
            init: {
                Error: function(up, err) {
                    console.log(err);
                }
            },
            // Flash settings
            flash_swf_url: '/plupload/js/Moxie.swf',
            // Silverlight settings
            silverlight_xap_url: '/plupload/js/Moxie.xap'
        });


        $('#uploader').on('complete', function (event, args) {
            //window.location.href="{{ url('/') }}/admin/media";
        });

        var cropSmart = function() {
            var img;
            var useFaceDetection = {{ (config('atlantis.use_facedetection') == 1) ? 'true' : 'false' }};
            var options = {
                width: 200,
                height: 150,
                minScale: 1,
                ruleOfThirds: true,
                debug: false,
            }

            function faceDetectionJquery(options, callback) {
                if (typeof $.fn.faceDetection !== 'function'){
                    callback();
                    return;
                }
                $(img).faceDetection({
                    complete: function(faces) {
                        if (faces === false) { return false;}
                        options.boost = Array.prototype.slice.call(faces, 0).map(function(face) {
                            return {
                                x: face.x,
                                y: face.y,
                                width: face.width,
                                height: face.height,
                                weight: 1.0
                            };
                        });

                        callback();
                    }
                });
            }

            function getOptions(){
                var resize = $('#resize').val();
                for (i in sizes){
                    if (sizes[i].name == resize){
                        options.width = sizes[i].width;
                        options.height = sizes[i].height;
                    }
                }
            }

            return{
                handleFiles: function(file, callback) {

                    getOptions();

                    if (file) {
                        if (typeof FileReader !== 'undefined' && file.type.indexOf('image') != -1) {
                            var reader = new FileReader();

                            reader.onload = function(evt) {
                                img = new Image();
                                img.onload = function() {
                                    if (!img) return;
                                    if (useFaceDetection === true) {
                                        faceDetectionJquery(options, function() {
                                            /*After Image analized run callback*/
                                            smartcrop.crop(img, options).then(callback);

                                        });
                                    }
                                    else {
                                        /*After Image analized run callback*/
                                        smartcrop.crop(img, options).then(callback);

                                    }
                                };
                                img.src = evt.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                }
            }

        }

        /*SCRIPTS FOR EDIT VIEW ONLY*/
                @if ($action == 'edit')


        var cropMnual = function() {

                var options = { width:200, height:150 };

                function getOptions(version){
                    <?php
                        $presets = array_merge(config('atlantis.static_images'), config('atlantis.responsive_images'));
                        $index = 0;
                        ?>
                            @if (array_key_exists($selected_resize, $presets))
                        cSizes = [];
                    @foreach($presets[$selected_resize] as $i=>$r)
                        cSizes[{{$index}}] = {
                        version : '{{$i}}',
                        width : {{$presets[$selected_resize][$i]['width']}},
                        height: {{$presets[$selected_resize][$i]['height']}},
                        crop: {{$presets[$selected_resize][$i]['crop'] == "" ? 0 : 1}}
                    };
                    <?php $index++; ?>
                            @endforeach

                        for (i in cSizes){

                        if (cSizes[i].version == version){
                            options.width = cSizes[i].width;
                            options.height = cSizes[i].height;
                        }
                    }
                    @endif

                    if(Foundation.MediaQuery.atLeast('large')){
                        options.maxBoxWidth = 720;
                    }
                    if(Foundation.MediaQuery.atLeast('xlarge')){
                        options.maxBoxWidth = 880;
                    }
                    if(Foundation.MediaQuery.atLeast('xxlarge')){
                        options.maxBoxWidth = 1060;
                    }

                }
                return {
                    init: function (img, version) {
                        getOptions(version);

                        img.Jcrop({
                            aspectRatio: options.width / options.height,
                            boxWidth: options.maxBoxWidth,
                            boxHeight: $(window).height() - 60,
                            minSize:  [options.width, options.height],

                        })
                    },
                    thumbnailPreview : function (img, version) {
                        getOptions(version);
                        var thumbnailPreview;
                        var jcrop_api = img.Jcrop('api');
                        thumbnailPreview = jcrop_api.initComponent('Thumbnailer', { width: options.width, height: options.height });
                        jcrop_api.ui.preview = thumbnailPreview;
                        /*replace the previe to the sidebar and scale it to fit the sidebar*/
                        var tmb = $('.jcrop-active .jcrop-thumb').detach();
                        $('.jcrop-thumb-replacer').html(tmb);
                        var cW = $('.jcrop-thumb-replacer').width();
                        var scale = options.width > cW ? cW/options.width : 1;
                        tmb.css('transform', 'scale(' + scale + ')');
                    },
                    attachListeners : function (img) {
                        var api  = img.Jcrop('api');
                        api.container.on('cropmove cropend',function(e,s,c){
                            $('#cropx').val(parseInt(c.x));
                            $('#cropy').val(parseInt(c.y));
                            $('#cropw').val(parseInt(c.w));
                            $('#croph').val(parseInt(c.h));
                        });
                        api.newSelection();
                        api.ui.selection.center();
                    },
                    destroy : function (img) {
                        if (typeof img.Jcrop('api') != 'undefined') {
                            img.Jcrop('api').destroy();
                        }
                    }
                }
            }


        /*pass which version to be recroped to the modal*/
        $('[data-open="manualCrop"]').on('click', function() {
            var targetModal = $('#' + $(this).data('open'));
            cvers = $(this).data('version');
            targetModal.find('[name="version"]').val(cvers);
            targetModal.find('.version-name').html(cvers)
        });

        /*init Jcrop after the image load lazy load set up for reveal images*/
        originalImgLoaded = false;
        $(document).on('open.zf.reveal' , '[data-reveal].crop', function() {
            var img  = $(this).find('img.original-image');
            var version  = $(this).find('[name="version"]').val();
            if (originalImgLoaded) {
                cropMnual().init(img, version);
                cropMnual().thumbnailPreview(img, version);
                cropMnual().attachListeners(img);
            }
            else{
                $(img).load(function () {
                    originalImgLoaded = true;
                    cropMnual().init(img, version);
                    cropMnual().thumbnailPreview(img, version);
                    cropMnual().attachListeners(img);
                    $(img).off('load');
                });
            }
        });

        $(document).on('closed.zf.reveal' , '[data-reveal].crop', function() {

            var img  = $(this).find('img.original-image');
            cropMnual().destroy(img);

        });



        /*More UI fixes*/
        $('[data-toggle="uploader"]').click(function (ev) {
            $('#thumb').hide();
        });

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }

        $('a.copy-path, .copy-path .path').click(function(ev) {
            ev.preventDefault();
            var $copy = $(this).closest('tr').find('.path');
            $copy.select();
            copyToClipboard($copy);
            $(this).closest('tr').find('.text').addClass('copied');
        })
        $('.copy-path .text').on('animationend webkitAnimationEnd oAnimationEnd', function() {
            $(this).removeClass('copied');
        });

        @endif
    });
</script>