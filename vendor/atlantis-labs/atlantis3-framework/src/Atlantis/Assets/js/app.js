$(document).foundation();
var atlantisUtilities = {

    init: function(arguments) {
        if (arguments) {
            if (arguments == 'atlCheckbox') {
                this.atlCheckbox();
            }
            return;
        }
        if (arguments) {
            if (arguments == 'addMedia') {
                this.addMedia();
            }
            return;
        }
        this.atlCheckbox();
        this.sidePanel();
        this.setBulkIds();
        this.addMedia();
    },
    reflow: {
        /*
         * collect tables to be reflowed. In case when some module call two or more tables async
         * \vendor\atlantis-labs\atlantis3-framework\src\Atlantis\Views\helpers\data-table.blade.php
         * TODO must collect all table custom data
         */
         tablesToReflow: [

         ],
         datepicker: function() {
            if (typeof $.fn.fdatepicker != 'undefined') {
                $('.dtp').fdatepicker();
            }
        },
        editor: function() {
            console.warn('The global function atlantisUtilities.reflow.editor must be overridden into your editor module');
        },
        imageSelector: function() {
            /*Defined in the \vendor\atlantis-labs\atlantis3-framework\src\Atlantis\Views\helpers\image-selector-standalone.blade.php*/
        },
        datatables: function() {
            /*Defined in the \vendor\atlantis-labs\atlantis3-framework\src\Atlantis\Views\helpers\data-table.blade.php*/
        },
        all: function() {
            $(document).foundation();
            this.datepicker();
            this.editor();
            this.datatables();
            atlantisUtilities.addMedia();
            this.imageSelector();
        }
    },
    atlCheckbox: function() {
        var checkboxes = $('[data-atl-checkbox]');
        $.each(checkboxes, function(key, val) {
            $(this).unbind('click');
            $(this).on('click', function(ev) {

                $(this).toggleClass('checked');
                if ($(this).hasClass('checked')) {
                    $(this).closest('tr').addClass('selected');
                    $(this).find('[type="checkbox"]').attr('checked', 'checked').val(true);
                } else {
                    $(this).closest('tr').removeClass('selected');
                    $(this).find('[type="checkbox"]').removeAttr('checked').val(false);
                }
            })
        })
    },
    setBulkIds: function() {
        $('.form_bulk_action').submit(function(ev) {
            ev.preventDefault();
            var ids = [];
            var selectedRows = $('#' + $(this).attr('data-table-id') + ' tr.selected').find('.checkbox [type="checkbox"]');
            $.each(selectedRows, function(key, val) {
                ids.push($(val).attr('data-id'));
            })
            $(this).find('[name="bulk_action_ids"]').val(ids);
            $('.form_bulk_action').unbind('submit');
            $('.form_bulk_action').submit();
        })

    },
    sidePanel: function() {
        var panelsToggler = $('[data-panel-toggle]');
        $.each(panelsToggler, function(key, val) {
            panelsToggler.on('click', function() {
                $('#' + $(this).attr('data-panel-toggle')).toggleClass('is-open');
                $(this).toggleClass('icon-Bulb icon-Arrow');
            })
        })
    },
    addMedia: function() {
        $('.add-media-table').off('draw.dt');
        $('.add-media-table').on('draw.dt', function() {

            var galleryContainer = $(this).closest('.gal-selector').find($('.gal-container'));

            $.each(galleryContainer.find(('.item img, .item .icon')), function(index, el) {

                $('.add-to-gal[data-image-id="' + $(el).attr('data-id') + '"]').addClass('disabled');
            })
        });

        function flyToElement(flyer, flyingTo) {
            var $func = $(this);
            var divider = 1;
            var flyerClone = $(flyer).clone();
            $(flyerClone).css({ position: 'absolute', top: $(flyer).offset().top + "px", left: $(flyer).offset().left + "px", opacity: 1, 'z-index': 9000 });
            $('body').append($(flyerClone));
            var gotoX = $(flyingTo).offset().left + ($(flyingTo).width() / 2) - ($(flyer).width() / divider) / 2;
            var gotoY = $(flyingTo).offset().top + ($(flyingTo).height() / 2) - ($(flyer).height() / divider) / 2;
            $(flyerClone).animate({
                opacity: 0.3,
                left: gotoX,
                top: gotoY,
                width: $(flyer).width() / divider,
                height: $(flyer).height() / divider
            }, 200,
            function() {
                $(flyerClone).remove();
            });
        }

        /*Remove Images*/
        $(document).on('click', '.rmv-btn', function(ev) {
            ev.preventDefault();
            $('.add-to-gal[data-image-id="' + $(this).attr('data-remove') + '"]').removeClass('disabled');

            $(this).closest('.item').animate({
                opacity: 0
            }, 200,
            function function_name(argument) {
                $(this).remove();
            });

        });
        /*Add Images*/
        $(document).off('click', '.add-to-gal');
        $(document).on('click', '.add-to-gal', function(ev) {

            ev.preventDefault();
            var galleryContainer = $(this).closest('.gal-selector').find($('[id*="gal-container-"]'));
            var imageNameing = $(this).closest('.gal-selector').find($('[data-input-naming]')).attr('data-input-naming');
            imageNameing = (typeof imageNameing == 'undefined') ? 'imgs[]'  : imageNameing;

            galleryContainer.length == 0 ? galleryContainer = $('#gal-container') : true;
            

            $(this).addClass('disabled');

            var id = $(this).attr('data-image-id');
            if (typeof $(this).attr('data-image-path') != 'undefined') {
                var src = $(this).attr('data-image-path');
                var img = $('<img />', {
                    'data-id': id,
                    'src': src
                });
            } else {
                var classes = $(this).attr('data-file-type').replace('/', ' ');
                var name = '<em class="name"><br>ID: ' + id + '<br>' + $(this).closest('tr').find('.icon').attr('data-name') + '</em>';
                var img = $('<em />', {
                    'data-id': id,
                    'src': src,
                    class: 'icon icon-File ' + classes
                });
                img = img.append(name);

            }
            var rmvBtn = '<a class="rmv-btn" title="remove" data-remove="' + id + '"><i class="fa fa-times-circle alert" aria-hidden="true"></i></a>';
            var editBtn = '<a class="edit-btn" title="edit" target="_blank" href="admin/media/media-edit/' + id + '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
            var imgIds = '<input type="hidden" name="' + imageNameing + '" value="' + id + '">';

            item = img.wrap('<span class="item"></span>').parent();

            img.css('opacity', 0);
            if (typeof galleryContainer.attr('data-single-image') !== typeof undefined && galleryContainer.attr('data-single-image') !== "false") {
                galleryContainer.find('.rmv-btn').click();
                galleryContainer.html(item);
            } else {
                item.appendTo(galleryContainer);
            }
            $(rmvBtn).appendTo(item);
            $(editBtn).appendTo(item);
            $(imgIds).appendTo(item);
            var flyingTo = $(galleryContainer).children().last() /*|| $(galleryContainer)*/ ;
            var clonedThumb = $(this).closest('tr').find('img');

            $(this).closest('tr').find('img').length != 0 ? clonedThumb = $(this).closest('tr').find('img') : clonedThumb = $(this).closest('tr').find('.icon')[0];

            if (!(flyingTo.length)) {
                flyingTo = $(galleryContainer)
            }
            flyToElement(clonedThumb, flyingTo);

            setTimeout(function() {
                img.css('opacity', 1);
            }, 200);

        })

    }

}


$.fn.limitText = function(maxLength, warnAt, infodivID) {
    if (this.length == 0) {
        return;
    };
    $("#" + infodivID).empty().html((maxLength - $(this).val().length));
    $(this).bind('keyup', function(e) {
        maxLength = parseInt(maxLength);
        var thisLength = $(this).val().length;
        if (thisLength > parseInt(warnAt)) {
            $("#" + infodivID).addClass('redtext');
        } else {
            $("#" + infodivID).removeClass('redtext');
        }
        if ((thisLength + 1) > maxLength) {
            $(this).val($(this).val().substring(0, maxLength));
            $("#" + infodivID).empty().html(maxLength - thisLength);
            return false;
        } else {
            $("#" + infodivID).empty().html(maxLength - thisLength);
        }
    });
}

function attachListeners() {
    $(document).on('change', '.gal-selector select', function(ev) {
        var id = $(this).val();
        id == '0' ? $(this).closest('.gal-selector').find('.edit-gal').addClass('disabled') : $(this).closest('.gal-selector').find('.edit-gal').removeClass('disabled');

        $(this).closest('.gal-selector').find('.edit-gal').attr('href', 'admin/media/gallery-edit/' + id);
    });
    $(document).on('click', '.gal-selector .refresh-gal', function(ev) {
        ev.preventDefault();
        $(this).closest('.gal-selector').find('select').attr('disabled', 'disabled');
        $.ajax({
            url: 'admin/media/all-galleries',
            context: $(this)

        })
        .done(function(response, status) {
            if (status == 'success') {

                var options = ['<option value="0"></option>'];
                $.each(response, function(key, opt) {
                    options.push('<option value="' + opt.id + '">' + opt.name + '</option>')
                })
                $(this).closest('.gal-selector').find('select').html(options);
            }
        })
        .fail(function(argument) {
            $(this).closest('.gal-selector').find('select').removeAttr('disabled');
        })
        .always(function(argument) {
            $(this).closest('.gal-selector').find('select').removeAttr('disabled');
        });
    })
}

/*Pattern Arrtibutes DataTable*/
function datatablesConfig() {
    if (typeof $.fn.DataTable != 'undefined') {

        var attributesTable = $('.dataTable.attributes').DataTable({
            autoWidth: false,
            searching: false,
            info: false,
            paging: false,
            columnDefs: [{ targets: 'no-sort', orderable: false }]
        });

        /*
         * Select all button
         */
         var selected = false;
         $(document).on('click', '.dataTable .select-all', function(ev) {

            var table = $(this).closest('table');
            if (!selected) {
                table.find('tr:not(.selected) [data-atl-checkbox]').click();
                selected = true;
            } else {
                table.find('tr.selected [data-atl-checkbox]').click();
                selected = false;
            }
        })

        /*
         *
         * Patterns Attributes Table
         */
         $('#create-attr-row').click(function(ev) {
            ev.preventDefault;
            //$('.new-attr-modal').foundation('open');
            var index = attributesTable.rows().count();
            attributesTable.row.add(
                ['<span class="text edittable">Name</span><input class="visually-hidden" type="text" name="attr[' + index + '][name]"/>',
                '<span class="text edittable">Value</span><input class="visually-hidden" type="text" name="attr[' + index + '][value]"/>',
                '<a href="#" data-tooltip title="Delete Attribute" class="icon icon-Delete top"></a>'
                ])
            .draw();
            var elem = new Foundation.Equalizer($('.tabs-content'), {});
        });

         $('.dataTable.attributes').on('click', '.icon-Delete', function(ev) {
            attributesTable.row($(this).parents('tr'))
            .remove()
            .draw();
        });
         $(document).on('click', '.attributes td', function(ev) {
            var text = $(this).find('.text.edittable');
            var input = $(this).find('input');
            input.val(text.text());

            text.hide();
            input.removeClass('visually-hidden').focus().on('blur', function() {
                text.show();

                text.text(input.val());

                input.addClass('visually-hidden');
            });

        });
     }
 }
 $.fn.makeURL = function(DisplayElement) {

    $(this).keyup(function() {
        /**
         * Regex Filter to Remove non URL Friendly
         * Characters
         */
        //var re = /\s|\/|\\|\'|\"|\,|\.|\!|\?|\^|\<|\>|\<>|\@|\#|\$|\&|\*|\(|\)|\=|\%|\`|\;|\:/gi;

        var cleanStr = $(this).val().replace(/[^\w ]+/g, '').replace(/ +/g, '-').toLowerCase();
        if ($('#' + DisplayElement).val().indexOf("/") != -1) {
            var split = $('#' + DisplayElement).val().split("/");
        }
        if (split && split[0] != "") {
            $('#' + DisplayElement).val(split[0] + "/" + cleanStr.toLowerCase());
        } else {
            $('#' + DisplayElement).val(cleanStr.toLowerCase());
        }
    });
};

function categoryChange() {
    var action, url, template;
    var target = $('#page_url');
    if (!target.length) {
        return;
    }

    var initial = {
        template: $('#categories_id option[selected]').attr('data-template'),
        string: $('#categories_id option[selected]').attr('data-string'),
        action: $('#categories_id option[selected]').attr('data-action')
    };

    var initialPageUrl = target.val().toLowerCase();
    var baseUrl;

    if (initial.action == 'prepend' && initialPageUrl.indexOf(initial.string) == 0) {
        baseUrl = target.val().replace(initial.string + '/', '');
    } else if (initial.action == 'append' && initialPageUrl.indexOf(initial.string) == initialPageUrl.length - initial.string.length) {
        baseUrl = target.val().replace('/' + initial.string, '');
    } else {
        baseUrl = target.val();
    }
    $('#categories_id').on('change', function(ev) {
        action = ev.target.selectedOptions[0].dataset.action
        url = ev.target.selectedOptions[0].dataset.string;
        template = ev.target.selectedOptions[0].dataset.template;


        $('#page_template').val(template);

        if (action == 'prepend') {
            target.val(url + '/' + baseUrl);
        } else if (action == 'append') {
            target.val(baseUrl + '/' + url);
        }
    });
}

function removePattern() {
    $('a.ajax-remove-pattern').click(function(ev) {
        ev.preventDefault();
        var patternId = $(this).attr('data-pattern');
        var patternType = $(this).attr('data-patterntype');
        var pageId = $(this).attr('data-page');
        var url = 'admin/pages/remove-pattern/' + pageId + '/' + patternId + '/' + patternType;

        function changeModal(modal, newState) {
            if (newState == 'excluded') {
                modal.find('h1').html('Add pattern');
                modal.find('a.button').html('Add pattern');
                modal.find('.lead').html('Are you sure you want to add this pattern?');
                modal.find('[data-patterntype]').attr('data-patterntype', newState);
            } else {
                modal.find('h1').html('Remove pattern');
                modal.find('a.button').html('Remove pattern');
                modal.find('.lead').html('Are you sure you want to remove this pattern?');
                modal.find('[data-patterntype]').attr('data-patterntype', newState);
            }
        }

        function appendPatterns(patterns) {
            var specific = [];
            var common = [];
            var excluded = [];
            if (typeof(patterns.specific) !== 'undefined') {
                $.each(patterns.specific, function(index, el) {
                    specific.push('<li><a href="admin/patterns/edit/' + el.id + '">' + el.name + '</a><a data-open="removePattern' + el.id + '" data-tooltip title="Remove pattern from this page" class="rmv-pattern fa fa-times top"></a></li>');
                });
            }
            $('.page-patterns-list.specific').html(specific);
            if (typeof(patterns.common) !== 'undefined') {
                $.each(patterns.common, function(index, el) {
                    common.push('<li><a href="admin/patterns/edit/' + el.id + '">' + el.name + '</a><a data-open="removePattern' + el.id + '" data-pattern-id="' + el.id + '" data-tooltip title="Remove pattern from this page" class="rmv-pattern fa fa-times top"></a></li>');
                });
            }
            $('.page-patterns-list.common').html(common);

            if (typeof(patterns.excluded) !== 'undefined') {
                $.each(patterns.excluded, function(index, el) {
                    excluded.push('<li><a href="admin/patterns/edit/' + el.id + '">' + el.name + '</a><a data-open="removePattern' + el.id + '" data-tooltip title="Add pattern from this page" class="rmv-pattern fa fa-times top"></a></li>');
                });
            }
            $('.page-patterns-list.excluded').html(excluded);
        }
        $.ajax({
            method: 'GET',
            url: url,
            context: $(this)
        }).done(function(response, status) {
            var modal = $(this).closest('[data-reveal]');
            var newType = '';
            var id = $(this).attr('data-pattern');

            $(this).closest('[data-reveal]').foundation('close');
            if (status == 'success') {
                if ($(this).attr('data-patterntype') !== 'excluded') {
                    newType = 'excluded';
                } else {
                    newType = 'common';
                    if (typeof(response.patterns.specific) !== 'undefined') {
                        $.each(response.patterns.specific, function(index, el) {
                            if (el.id == id) {
                                newType = 'specific';
                            }
                        });
                    }
                }

                changeModal(modal, newType);
                appendPatterns(response.patterns);
            }
        });
    })
}

function tagsinputConfig() {
    if (typeof $.fn.tagsInput != 'undefined') {
        $('.inputtags').tagsInput({
            'height': 'auto',
            'width': 'initial',
        });
    }
}

function uiFixes() {
    $('.editscreen .accordion').on('down.zf.accordion', function() {
        var elem = new Foundation.Equalizer($('.tabs-content[data-equalizer]'), {});
    });
    $('button[data-open]').click(function function_name(ev) {
        ev.preventDefault();
    })

    $('.bulk-action select').on('change', function() {
        if ($(this).val() != "bulk_none") {
            $('.bulk-action .button').removeClass('disabled');
        } else {
            $('.bulk-action .button').addClass('disabled');
        }
    });
    if (typeof $.fn.limitText != 'undefined') {
        $('#meta_description').limitText(255, 230, 'meta_description_info');
        $('#meta_keywords').limitText(255, 230, 'meta_keywords_info');
    }
    $(document).on('closeme.zf.reveal', '[data-reveal]', function() {
        var img = $(this).find('[data-src]');
        $(img).attr('src', $(img).attr('data-src'));
    });

    $('.tabs-title .actions a').click(function(ev) {
        ev.stopPropagation();

        if ($(this).attr('data-open')) {
            var element = $('#' + $(this).attr('data-open'));
            element.foundation('open');
        } else if ($(this).attr('href')) {
            //window.locagtion.href($(this).attr('href'));
        }

    });

    /*Remove menu item outline blink*/
    $('#main-nav li a').removeAttr('tabindex');
}

function mobileFixes(){
    /*Dataatble icons */
    if (!Foundation.MediaQuery.atLeast('medium')) {
        $(document).on( 'draw.dt', function ( e, settings ) {  
            var api = new $.fn.dataTable.Api( settings );
            var table = api.table().node();
            var icons = $(table).find('.name .actions .icon');
            if (icons.length > 0 && typeof $(icons.first()).attr('data-alt-text') == 'undefined' ) {
                $.each(icons, function(index, icon) {
                    $(icon).attr('data-alt-text', $('#' + $(icon).attr('data-toggle')).text());
                });
            }

            $('.name .actions .opened').removeClass('opened');
            var actionIcons = $(table).find('.name .actions').not('.opened');
            $(actionIcons).on('touch, click', function(ev){
                $('.name .actions.opened').removeClass('opened');
                $(this).addClass('opened')
            });
        });
    }        
}

$(document).ready(function() {
    /* $.getJSON("http://quotesondesign.com/wp-json/posts?filter[orderby]=rand&filter[posts_per_page]=1&callback=", function(a) {
     $('body').append('<div class="row text-center"><div class="columns large-6 large-centered">'+a[0].content+'<p>— ' + a[0].title + '</p></div></div>')
 });*/
 atlantisUtilities.init();
 uiFixes();
 mobileFixes();
 $(window).resize(function(){
    mobileFixes();
});
 categoryChange();
 removePattern();
 /*Plugins Configuration*/

    //datepickerConfig();
    atlantisUtilities.reflow.datepicker();
    tagsinputConfig();
    datatablesConfig();
    attachListeners();

});