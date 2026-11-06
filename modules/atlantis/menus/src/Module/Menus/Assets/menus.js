(function() {

   function resetIndexes() {
        $.each($('ol#sortable>li'), function (key, val) {
            $(this).find('[type="hidden"][name*="weight"]').val(key + 1);
            $(this).find('.index').text((key + 1));
            $(this).find('[data-length]').attr("data-length", key + 1);
            /*$(this).find('[data-toggle*="advanced-item"]').attr('data-toggle', 'advanced-item'+ (key + 1));
            $(this).find('[aria-controls*="advanced-item"]').attr('aria-controls', 'advanced-item'+ (key + 1));
            $(this).find('[id*="advanced-item"]').attr('id', 'advanced-item'+ (key + 1));
            Foundation.reInit($('[data-toggle]'));*/
        });
   }

    function addItems() {

        var initialLen = $("[id^='row_items_']").length;

        $(".add-menu-item, #add-menu-item").click(function(e) {
            e.preventDefault();
            /*put in order weights and indexex first*/
            resetIndexes();

            var thisLength = $("[id^='row_items_']").length;

            //$("[id^='row_items_']").last().find("[data-tooltip]").foundation('destroy');
            
            var clone = $("[id^='row_items_']").last().clone();

            //$("[id^='row_items_']").last().find("[data-tooltip]").foundation();

            $(clone).find('[data-length]').attr("data-length", thisLength + 1).attr("id", "advanced-item" + (initialLen + 1));

            //$(clone).find("div[id^='item']").attr("id", "item" + (thisLength + 1)).attr("data-href", thisLength + 1);

            $(clone).find("a[id^='btn_delete_']").attr("id", "btn_delete_" + (thisLength + 1));

            $(clone).find(".item-title").text("NEW ITEM");
            $(clone).find(".item-url").text("");
            $(clone).find(".item-type").text("text");
            $(clone).find("input").val("");

            /*SET INPUTS CLONE NAMES*/
            $(clone).find('[name]').each(function(key, val) {
                var newName = $(this).attr('name').substring(0, $(this).attr('name').lastIndexOf('['));
                newName += '[_' + (thisLength + 1) + ']';
                $(this).attr('name', newName);
            });

            $(clone).find('[type="hidden"][name*="weight"]').val(thisLength + 1);
            $(clone).find('.index').text((thisLength + 1));
            $(clone).find('a[data-toggle]').attr("data-toggle", "advanced-item" + (initialLen + 1));
            
            $(clone).attr("id", "row_items_" + (thisLength + 1));
            $("[id^='row_items_']").last().after(clone);
            $(clone).find('[name^="validation"]').change();

            $("#row_items_" + (thisLength + 1)).foundation();
            
            //$(clone).find("[data-tooltip]").foundation();
            resetIndexes();
            initialLen++;

        });

        $(document).on("click", "a[id^='btn_delete_']", function() {

            var thisLength = $("li[id^='row_items_']").length;

            if (thisLength > 1) {

                //var id = $(this).attr("id").split("btn_delete_")[1];
                //$("#row_items_" + id).remove();
                $(this).closest("[id^='row_items_']").remove()

            }
            resetIndexes();
        });
    }

    $(document).ready(function() {
        addItems();

        $(document).on('keyup', '.row.advanced .name', function(ev) {
            $(this).closest('li').find('.item-title').text($(this).val() || 'NEW Field');
        });

        $('.toggle-fields').click(function(ev) {
            ev.preventDefault();
            $('ol .row-item .icon-Settings').click();
        });

        if (typeof $.fn.sortable == 'function') {

          $("#sortable").sortable({
            handle: '.fa.fa-bars.move',
            onDrop: function ($item, container, _super, event) {
                $item.removeClass(container.group.options.draggedClass).removeAttr("style");
                $("body").removeClass(container.group.options.bodyClass);

                resetIndexes();


            }
        });
      }
  });
})()
