(function() {

    $(document).on("forminvalid.zf.abide", function(ev,form) {
        $firstInvalid = '';
        $firstInvalid = $(ev.target).find('.is-invalid-input').first().closest('[data-toggler]');
        if($firstInvalid.length){
            $firstInvalid.addClass('expanded');
            $("html, body").animate({ scrollTop: $firstInvalid.offset().top - 40 });
        }
    });
   
    function addItems() {

        var initialLen = $("[id^='row_items_']").length;

        $(".add-menu-item").click(function(e) {
            e.preventDefault();
            var thisLength = $("[id^='row_items_']").length;

            var clone = $("[id^='row_items_']").last().clone();

            $(clone).find('[data-length]').attr("data-length", thisLength + 1).attr("id", "advanced-item" + (initialLen + 1));

            //$(clone).find("div[id^='item']").attr("id", "item" + (thisLength + 1)).attr("data-href", thisLength + 1);

            $(clone).find("a[id^='btn_delete_']").attr("id", "btn_delete_" + (thisLength + 1));

            $(clone).find(".item-title").text("NEW ITEM");
            $(clone).find(".item-url").text("");
            $(clone).find(".item-type").text("text");
            $(clone).find("input").val("");
            $(clone).find('[type="hidden"][name*="weight"]').val(thisLength + 1);
            $(clone).find('.index').text((thisLength + 1));
            $(clone).find('a[data-toggle]').attr("data-toggle", "advanced-item" + (initialLen + 1));
            /*SET SWITCHES CLONE ATTRIBUTES*/
            $(clone).find('.switch').each(function(key, val) {
                var newName = $(this).find('.switch-input').attr('name').substring(0, $(this).find('.switch-input').attr('name').lastIndexOf('['));
                newName += '[_' + (thisLength + 1) + ']';
                $(this).find('.switch-paddle').attr('for', newName);
                $(this).find('.switch-input').attr('id', newName);
            });
            /*SET INPUTS CLONE NAMES*/
            $(clone).find('[name]').each(function(key, val) {
                var newName = $(this).attr('name').substring(0, $(this).attr('name').lastIndexOf('['));
                newName += '[_' + (thisLength + 1) + ']';
                $(this).attr('name', newName);
                //$(this).val();
                $(this).removeAttr('value');
            });
            $(clone).find('[name*="weight[_"]').val((initialLen + 1));
            
            $(clone).attr("id", "row_items_" + (thisLength + 1));
            $("[id^='row_items_']").last().after(clone);
            $(clone).find('[name^="validation"]').change();
            $("#row_items_" + (thisLength + 1)).foundation();

            initialLen++;

            //;
            Foundation.reInit('abide');

        });

        $(document).on("click", "a[id^='btn_delete_']", function() {

            var thisLength = $("li[id^='row_items_']").length;

            if (thisLength > 1) {

                var id = $(this).attr("id").split("btn_delete_")[1];

                $("#row_items_" + id).remove();

            }
        });
        $(document).on("change", "select[name^='validation']", function() {
            var _self = $(this).closest('.menu-item');
            /*When field is email enable swiches*/
            if ($(this).val() == 'required|email' && !(_self.find('.use-as-switches').is(':visible'))) {
                _self.find('.use-as-switches').slideToggle('fast');
                _self.find('input[name^="use_reply_to"], input[name^="use_email_from"]').removeAttr('disabled');
            }
            /*OTHERWISE*/
            else if ($(this).val() != 'required|email' && _self.find('.use-as-switches').is(':visible')) {
                _self.find('.use-as-switches').slideToggle('fast');
                _self.find('input[name^="use_reply_to"], input[name^="use_email_from"]').attr('disabled', 'disabled');

            }
        });
    }

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    function generateTemplate() {
        var Tmpl = {
            basic: '',
            error: '',
            foundation: '',
            bootstrap: ''
        };

        Tmpl.basic += ('{{before_form_text}}\n');
        Tmpl.foundation += ('{{before_form_text}}\n');
        Tmpl.foundation += ('<div class="row">\n');
        Tmpl.bootstrap += ('{{before_form_text}}\n');
        Tmpl.bootstrap += ('<div class="row">\n');

        $.each($('#sortable .row-item'), function(i, el) {
            var name = $(this).find('[name^="field_name"]').val() || '';
            var error = '';
            var label = ($(this).find('[name^="label"]').val() || '');
            if (name == '') {
                Tmpl.error = '<div class="callout alert">There are some empty field names. Fill them and rebuild the template.</div>'
            } else {
                error = '{!!' + name + '!!}';
                name = '{{' + name + '}}';
            }

            Tmpl.basic += ('<label for="">' + label + '\n');
            Tmpl.basic += ('  ' + error + '\n');
            Tmpl.basic += ('  ' + name + '\n');
            Tmpl.basic += '</label><br>\n';

            Tmpl.foundation += ('   <div class="small-12 columns">\n');
            Tmpl.foundation += ('         <label for="">' + label + '\n');
            Tmpl.foundation += ('               <span class="form-error">' + error + '</span>\n');
            Tmpl.foundation += ('               ' + name + '\n');
            Tmpl.foundation += '          </label>\n';
            Tmpl.foundation += ('   </div>\n');

            Tmpl.bootstrap += ('    <div class="form-group">\n');
            Tmpl.bootstrap += ('          <label class="col-form-label" for="">' + label + '\n');
            Tmpl.bootstrap += ('                <span class="badge badge-danger">' + error + '</span>\n');
            Tmpl.bootstrap += ('          </label>\n');
            Tmpl.bootstrap += ('          ' + name + '\n');
            Tmpl.bootstrap += ('    </div>\n');
        });

        Tmpl.foundation += ('</div>\n');
        Tmpl.bootstrap += ('</div>\n');

        if ($('#captchaSwitch').is(':checked')) {
            Tmpl.basic += ('{{captcha}}\n');
            Tmpl.foundation += ('<div class="row column">{{captcha}}</div>\n');
            Tmpl.bootstrap += ('<div class="row column">{{captcha}}</div>\n');
        }

        Tmpl.basic += ('{{submit_button}}\n');
        Tmpl.basic += ('{{after_form_text}}\n');

        Tmpl.foundation += ('<div class="row column">{{submit_button}}</div>\n');
        Tmpl.foundation += ('{{after_form_text}}\n');

        Tmpl.bootstrap += ('<div class="row">{{submit_button}}</div>\n');
        Tmpl.bootstrap += ('{{after_form_text}}\n');
        return Tmpl;
    }

    $(document).ready(function() {
        addItems();

        $(document).on('keyup', '.row.advanced .name', function(ev) {
            $(this).closest('li.row-item').find('.item-title').text($(this).val() || 'NEW Field');
        });
        $(document).on('change', '.row.advanced .type', function(ev) {
            $(this).closest('li.row-item').find('.item-type').text($(this).val());
        });

        $('[name^="validation"]').change();

        $('.copy-tmpl').click(function(ev) {

            ev.preventDefault();
            var $copy = $(this).closest('.reveal').find('.tabs-panel.is-active textarea');
            copyToClipboard($copy);
            $(this).find('.text').addClass('copied');
        })
        $('.copy-tmpl .text').on('animationend webkitAnimationEnd oAnimationEnd', function() {
            $(this).removeClass('copied');  
        });


        $('.customTmpl').click(function(ev) {
            ev.preventDefault();
            var tmpl = generateTemplate();
            $('#customTmpl .errors').html(tmpl.error);
            $('#customTmpl #panelBasic textarea').text(tmpl.basic);
            $('#customTmpl #panelFoundation textarea').text(tmpl.foundation);
            $('#customTmpl #panelBootstrap textarea').text(tmpl.bootstrap);

        });

        $('.toggle-fields').click(function(ev) {
            ev.preventDefault();
            $('ol .row-item .icon-Settings').click();
        });

        if (typeof $.fn.sortable == 'function') {

            $("#sortable").sortable({
                handle: '.fa.fa-bars.move',
                onDrop: function($item, container, _super, event) {
                    $item.removeClass(container.group.options.draggedClass).removeAttr("style");
                    $("body").removeClass(container.group.options.bodyClass);

                    $.each($('ol#sortable>li'), function(key, val) {
                        $(this).find('[type="hidden"][name*="weight"]').val(key + 1);
                        $(this).find('.index').text((key + 1));
                    });
                }
            });
        }
    });
})()
