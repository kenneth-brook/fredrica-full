<script>
  // instance, using default configuration.
  CKEDITOR.replace('{{ $attributes["id"] }}', {
    'allowedContent': true,
    'enterMode': CKEDITOR.ENTER_BR,
    'autoGrow_minHeight': '{{ $height }}',
    'height': '{{ $height }}',
    'baseHref' : '{{ url('/') }}/',
    'baseUrl' : '{{ url('/') }}/'

  });
  
  atlantisUtilities.reflow.editor = function() {
    $.each($('[id^="ckeditor-"]'), function(i, el) {
      var editorId = $(el).attr('id');
      if (typeof(CKEDITOR.instances[editorId]) == 'undefined') { 
        CKEDITOR.replace(editorId, {
          'allowedContent': true,
          'enterMode': CKEDITOR.ENTER_BR,
          'autoGrow_minHeight': '{{ $height }}',
          'height': '{{ $height }}',
          'baseHref' : '{{ url('/') }}/',
          'baseUrl' : '{{ url('/') }}/'
        });
      }
    })
  }
</script>