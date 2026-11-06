<div class="reveal" id="wpXml" data-reveal>
    {!! Form::open(['url' => 'admin/modules/blog/wp-import', 'files' => true , 'id' => "wpXmlForm", 'data-abide' => 'true', 'novalidate' => 'novalidate']) !!}
    <h1>Import Posts from WP</h1>

    <label for="wpXml">Choose file <span class="form-error">Required</span>
        {!! Form::file('wpXml',  ['id'=>'wpXml', 'required' => 'required', 'accept' => '.xml, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel']) !!}
    </label>

    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
    <input type="submit" name="_update_config" value="Import" id="import-btn" class="success button">
    <i style="visibility:hidden" class="fa fa-refresh fa-spin fa-2x fa-fw  alert import-loader"></i>
    <div class="done-import-status">
        
    </div>

    {!! Form::close() !!}
</div>

@section('js')
@parent
<script type="text/javascript">

document.addEventListener("DOMContentLoaded", function (event) {
    
    $('#wpXmlForm').submit(function (ev) {
        ev.preventDefault();

        $('.import-loader').show().css('visibility', 'visible');
        $('.done-import-files').hide();
        $('.error-import-files').hide();
        data = new FormData($("#wpXmlForm")[0]);
        
        $.ajax({
          url: '{{ url('/') }}/admin/modules/blog/wp-import',
          data: data,
          processData: false,
          contentType: false,
          type: 'POST',
          success: function(data){
            console.log(data);
            $('.done-import-status').html(data);
            $('.import-loader').hide();
        }
    });

    });

});
</script> 

@stop 