$(document).ready(function()
{

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $("#selectBtn").click(function()
    {
        var catID = $("#select-category option:selected").val();

        $.post('/admin/modules/customfields/get-category-fields', { cat_id: catID }, function(databack)
        {
            $("#field-container").empty().append(databack.status);

            atlantisUtilities.reflow.all();

        }, 'json');
    });

});