{!!   \Atlantis\Helpers\Assets::registerScript('https://code.jquery.com/jquery-1.12.4.min.js', 9)  !!}
{!!   \Atlantis\Helpers\Assets::registerScript('https://code.jquery.com/ui/1.12.1/jquery-ui.js', 10)  !!}
{!!  \Atlantis\Helpers\Assets::registerStyle("//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css") !!}

<script>
    $( function() {

        $("{{ '#' . $id }}").datepicker({
         dateFormat: '{{ $format  }}'
        });
    } );
</script>