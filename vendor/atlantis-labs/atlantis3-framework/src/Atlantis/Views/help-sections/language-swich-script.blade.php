
@if (isset($oPage))
    {{-- edit page script --}}
    <script type="text/javascript">    
        $(document).ready(function() {
            
           $('#select-language').attr('style', '');

            $(document).on('change', '#select-language', function() {

                if ($(this).find(':selected').attr('data-active-version') == 0) {

                    $("<input type='hidden' name='page_body' value='' /><input type='hidden' name='language' value='"+$(this).val()+"' />").appendTo("#edit-page-form");

                    $("<input type='hidden' name='_create_lang_version' value='"+$(this).val()+"'/>").appendTo("#edit-page-form");
                    $('#edit-page-form [name="_update"]').click();
                }
                else{
                    window.location.href = $('base').attr('href') + "admin/pages/edit/{{ $oPage->id }}/" + $(this).find(':selected').attr('data-active-version') + '/' + $(this).val();
                }
            });
        });
    </script>
@else
    {{-- edit pattern script --}}
    <script type="text/javascript">    
        $(document).ready(function() {

            $('#select-language').attr('style', '');

            $(document).on('change', '#select-language', function() {

                if ($(this).find(':selected').attr('data-active-version') == 0) {

                    $("<input type='hidden' name='text' value='' /><input type='hidden' name='language' value='"+$(this).val()+"' />").appendTo("#edit-pattern-form");
                    $("<input type='hidden' name='_create_lang_version' value='"+$(this).val()+"'/>").appendTo("#edit-pattern-form");
                    
                    $('#edit-pattern-form [name="_update"]').click();
                }
                else{
                    window.location.href = $('base').attr('href') + "admin/patterns/edit/{{ $oPattern->id }}/" + $(this).find(':selected').attr('data-active-version') + '/' + $(this).val();
                }
            });
        });
    </script>

@endif