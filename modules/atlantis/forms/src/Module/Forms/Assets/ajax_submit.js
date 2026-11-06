/**
 * Created by Ivan on 9/23/2016.
 */
 $(function() {
    /* prevent default submit on ajax forms*/
    var ajaxForms = $("form[data-use-ajax=1]");
    if (ajaxForms.length) {
        ajaxForms.on("submit", ajaxSubmit);
    }

    /*Attach Ajax validation on form fields*/
    var frontEndValidationForms = $("form[data-use_front_end_validation=1]");
    if (frontEndValidationForms.length) {
        var inputs = frontEndValidationForms.find('input, textarea, select').not('[type="checkbox"], [type="radio"]');

        var checkboxes = frontEndValidationForms.find('[type="checkbox"], [type="radio"]');
        
        $(inputs).off('blur');
        $(inputs).on('blur', $(inputs), validateInput);

        $(checkboxes).off('change');
        $(checkboxes).on('change', $(checkboxes), validateInput);
    }


    function ajaxSubmit(ev) {
        ev.preventDefault();
        var ajaxForm = $(this);

        var isCustom = ajaxForm.attr('data-is-custom-form') == '1' ? true : false;

        var data = ajaxForm.serialize();

        if ($('input[type="file"]').length) {

            if (!FormData) {
                // old browser alert
                return true;
            } else {
                var useFormData = 1;
                data = new FormData(this);

            }

        }
        var processData = false;
        var contentType = false;
        if (!useFormData) {
            processData = true;
            contentType = "application/x-www-form-urlencoded";
        }

        ajaxForm.addClass( "ajax-loading" );
        ajaxForm.removeClass( "ajax-done" );

        $.ajax({
            processData: processData,
            contentType: contentType,
            url: ajaxForm.attr("action"),
            type: "POST",
            data: data,
            success: function(htmlPage) {

                var id = ajaxForm.attr("id");

                var msgContainer = $(htmlPage).find('.msg-container#msg-' + id ).html();
                

                $('.msg-container#msg-' + id).html(msgContainer);
                var targetForm = $(htmlPage).find("#" + id).html();

                $(ajaxForm).html(targetForm);

                var inputs = $(ajaxForm).find('input, textarea, select');
                var inputs = $(ajaxForm).find('input, textarea, select').not('[type="checkbox"], [type="radio"]');
                var checkboxes = $(ajaxForm).find('[type="checkbox"], [type="radio"]');

                $(inputs).on('blur', validateInput);
                $(checkboxes).on('change', validateInput);


                /*hideForm if success*/
                if ($('.msg-container#msg-' + id + ' .success').text().length > 0) {

                    $.event.trigger({
                        type:    "successAjaxSubmit",
                        message: "Form submitted succesful via ajax",
                        time:    new Date()
                    });
                    if (ajaxForm.data("hide-after-submit")) {
                        ajaxForm.hide();
                    }
                } else {
                    //isCustom ? $(inputs).blur() : '';
                    validateInput(ev, true);
                }
            }

        }).done(function() {
            ajaxForm.addClass( "ajax-done" );
            ajaxForm.removeClass("ajax-loading")
        });
        return false;
    };

    function validateInput(ev, validateAll) {
        if (validateAll == true) {
            /*validate all inputs with single ajax request*/
            var field = $(ev.target).closest('form').find('input, textarea, select');
            var theForm = $(field[0]).closest('form');
            
        } else {
            /*if passed es a blur callback*/
            /*validate only target input*/
            var field = $(ev.target);
            var theForm = $(this).closest('form');
            
        }
        
        if ($(theForm).attr('data-use_front_end_validation') == 0) {
            return;
        }
        var isCustom = $(theForm).attr('data-is-custom-form') == '1' ? true : false;
        var data = theForm.serialize();

        if (isCustom) {
            var fieldErrorClass = $(theForm).attr('data-input_error_class') || '';
            var errorWrapperClass = $(theForm).attr('data-error_wrapper_class') || '';
        } else {
            var fieldErrorClass = 'has-error';
            var errorWrapperClass = 'a3-error-wrap';
        }

        if ($('input[type="file"]').length) {

            if (!FormData) {
                return true;
            } else {
                var useFormData = 0;
            }
        }
        var processData = false;
        var contentType = false;
        if (!useFormData) {
            processData = true;
            contentType = "application/x-www-form-urlencoded";
        }

        function successCallback(response) {
            $.each(field, function(i, el) {

                field = $(this);

                var fieldName = field.attr('name');
                var parent = field.parent();

                /*set different parent if checkbox or radio*/

                (field.attr('type') == 'checkbox' || field.attr('type') == 'radio') ? fieldName = field.attr('name').split('[')[0] : fieldName = field.attr('name');
                var errors = response.data.validator;
                (field.attr('type') == 'checkbox' || field.attr('type') == 'radio') ? parent = field.parent().parent() : parent = field.parent();



                /*set if error wrapper available*/
                if (errorWrapperClass == '' || parent.find('.' + errorWrapperClass).length == 0) {
                    hasErrorWrapper = false;
                } else { hasErrorWrapper = true }

                if (isCustom) {
                    if (typeof(errors[fieldName]) != 'undefined') {
                        var error = errors[fieldName][0];
                        
                        parent.addClass(fieldErrorClass);
                        /*parent.find('.' + 'a3-error-wrap').remove();
                        hasErrorWrapper ? parent.find('.' + errorWrapperClass).html(error) : $('<span class="error-msg a3-error-wrap">' + error + '</span>').insertBefore(field);
                        errorWrapperClass = 'a3-error-wrap';*/
                        parent.find('.' + errorWrapperClass).html(error);
                    } else {
                        parent.removeClass(fieldErrorClass);
                        /*hasErrorWrapper ? parent.find('.' + errorWrapperClass).html('') : parent.find('.a3-error-wrap').remove();*/
                        parent.find('.' + errorWrapperClass).html('');

                    }
                } else {

                    if (typeof(errors[fieldName]) != 'undefined') {
                        var error = errors[fieldName][0];
                        parent.find('.' + errorWrapperClass).html(error);
                        parent.addClass(fieldErrorClass);
                    } else {
                        parent.find('.' + errorWrapperClass).html('');
                        parent.removeClass(fieldErrorClass);
                    }
                }
            })
        }

        theForm.addClass( "ajax-loading" );
        theForm.removeClass( "ajax-done" );
        $.ajax({
            processData: processData,
            contentType: contentType,
            url: 'admin/modules/formsFrontEndValidation',
            type: "POST",
            data: data,
            success: successCallback
        }).done(function() {
            theForm.removeClass("ajax-loading");
            theForm.addClass( "ajax-done" );
        });
        return false;
    }


})
