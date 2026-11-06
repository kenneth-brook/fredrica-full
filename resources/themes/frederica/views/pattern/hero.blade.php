<script type="text/javascript">
  $(document).ready(function() {
    var slides = $('.cycle-slideshow').children('img').length;
    
    //alert( slides);
    
    if (slides <= 1) {
      $('.cycle-next').css("display", "none");
      $('.cycle-prev').css('display' , 'none');
    } else {
      $('.cycle-next').css("display", "visible");
      $('.cycle-prev').css('display' , 'visible');
    }
    $('.cycle-slideshow').on('cycle-initialized', function(e, opts, API) {
    });

  });
</script>
<div class="hero-bar">
    <div class="cycle-slideshow">
        <div class="cycle-prev"></div>
        <div class="cycle-next"></div>
        <?=$text; ?>
    </div>
</div>