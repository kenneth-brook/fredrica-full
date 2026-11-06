@extends('atlantis-admin::admin-shell')

@section('scripts')
@parent
{{-- Add scripts per template --}}
{!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/foundation-datepicker.min.js') !!}
@stop

@section('styles')
@parent
{{-- Add styles per template --}}
@stop

@section('content')
<main>
  <section class="greeting">
    <div class="row">
      <div class="columns ">
        <h1 class="huge page-title">Export</h1>
        @if (isset($msgInfo))
        <div class="callout warning">
          <h5>{!! $msgInfo !!}</h5>
        </div>
        @endif
        @if (isset($msgSuccess))
        <div class="callout success">
          <h5>{!! $msgSuccess !!}</h5>
        </div>
        @endif
        @if (isset($msgError))
        <div class="callout alert">
          <h5>{!! $msgError !!}</h5>
        </div>
        @endif
      </div>
    </div>
  </section>

  <section class="editscreen">
    {!! Form::open(array('url' => url('/').'/admin/modules/forms/export/' . $form->id, 'data-abide' => '', 'novalidate'=> '', 'id' => 'export-form')) !!}

    <div class="row">
      <div class="columns">
        <div class="float-right">
          <div class="buttons">
            <a href="{{url('/')}}/admin/modules/forms" class="back button tiny top primary" title="Go to Forms" data-tooltip>
              <span class=" back icon icon-Goto"></span>
            </a>
            {!! Form::input('submit', '_export_csv', 'Export to CSV', ['class' => 'alert button start-export-csv', 'id'=>'update-btn']) !!}
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="columns small-12">
        <ul class="tabs" data-tabs id="example-tabs">
          <li class="tabs-title is-active main">
            <a href="#panel1" aria-selected="true">
              Export - {{ $form->name }}
            </a>
          </li>
          <!-- <li class="tabs-title main">
            <a href="#panel2">
              Recently Used
            </a>
          </li> -->

        </ul>
        <div class="tabs-content" data-tabs-content="example-tabs">
          <div class="tabs-panel is-active" id="panel1">
            <div class="row">
              <div class="columns medium-4">                          
                <label id="from_date" for="from_date">From</label>
                <span class="fa fa-calendar dtp-wrapper">
                  {!! Form::input('text', 'from_date', old('from_date'), ['id'=>'from_date', 'class' => 'dtp']) !!}
                </span>

              </div>
              <div class="columns medium-4">                          
                <label id="to_date" for="to_date">To</label>
                <span class="fa fa-calendar dtp-wrapper">
                  {!! Form::input('text', 'to_date', old('to_date'), ['id'=>'to_date', 'class' => 'dtp']) !!}
                </span>

              </div>
              <div class="columns medium-4 end">                          
                <br>
                <div class="proccessing-export" style="display: none">
                  <i class="fa fa-refresh fa-spin fa-2x fa-fw alert"></i>
                  <span>Please wait...</span>
                </div>
                <div class="proccessing-done" style="display: none">
                  <i class="fa fa-check fa-2x success"></i>
                  <span>Done.</span>
                </div>
                <div class="proccessing-error" style="display: none">
                  <i class="fa fa-exclamation fa-2x alert"></i>
                  <span>Error.</span>
                </div>
                </label>
              </div>
            </div>


          </div>
          <!--<div class="tabs-panel" id="panel2">
          </div> -->
        </div>
      </div>
    </div>
    {!! Form::close() !!}
  </section>


</main>
<footer>
  @include('forms-admin::admin/help-sections/forms-add-edit')
  <div class="row">
    <div class="columns">
    </div>
  </div>
</footer>
@stop

@section('js')
<script type="text/javascript">

  var formFields;

  $('#export-form').on('click', '.start-export-csv', function (e) {
    e.preventDefault();

    formFields = $('#export-form').serializeArray();
    makeRequest(null);

  });

  function makeRequest(arr) {

    var tempFields = JSON.parse(JSON.stringify(formFields));

    if (arr != null) {
      arr.forEach(function (obj) {
        tempFields.push(obj);
      });
    }

    var request = $.ajax({
      url: $('#export-form').attr('action'),
      type: 'POST',
      dataType: "json",
      data: tempFields
    });
    $('#export-form :input').prop('disabled', true);

    startProccessingMsg(null);

    request.done(function (data) {

      if (data.is_done != null) {
        if (data.is_done) {
          //exporting is done
          $('#export-form :input').prop('disabled', false);
          doneProcessingMsg('<a href="' + data.download_link + '" target="_blank">' + data.export_name + '</a>');
        } else {
          //send new request with next_offset
          makeRequest([
            {
              'name': 'offset',
              'value': data.next_offset
            },
            {
              'name': 'export_name',
              'value': data.export_name
            },
            {
              'name': 'exported',
              'value': data.exported
            },
            {
              'name': 'total',
              'value': data.total
            }
          ]);
          proccessingMsg('Exporting... (' + data.exported + '/' + data.total + ')');
        }
      } else if (data.validation_error != null) {

        // validation error
        $.each($.parseJSON(JSON.stringify(data.validation_error)), function (key, val) {
          var errorMsg = val[0];
          var label = $('#' + key);
          label.addClass('is-invalid-label form-error is-visible');
          label.text(errorMsg);
        });
        $('#export-form :input').prop('disabled', false);
        errorProccessingMsg(null);
      } else {
        if (data.error) {
          errorProccessingMsg(data.error);
        } else {
          errorProccessingMsg('Something went wrong');
        }
        //something went wrong
        $('#export-form :input').prop('disabled', false);
      }

    });
  }

  function startProccessingMsg(msg) {
    $('.proccessing-export').css('display', '');
    $('.proccessing-done').css('display', 'none');
    $('.proccessing-error').css('display', 'none');
    if (msg != null) {
      $('.proccessing-export span').text(msg);
    }
  }

  function doneProcessingMsg(downloadLink) {
    $('.proccessing-export').css('display', 'none');
    $('.proccessing-done').css('display', '');
    $('.proccessing-error').css('display', 'none');
    if (downloadLink != null) {
      $('.proccessing-done span').html(downloadLink);
    }
  }

  function errorProccessingMsg(msg) {
    $('.proccessing-export').css('display', 'none');
    $('.proccessing-done').css('display', 'none');
    $('.proccessing-error').css('display', '');
    if (msg != null) {
      $('.proccessing-error span').html(msg);
    }
  }

  function proccessingMsg(msg) {
    $('.proccessing-export span').text(msg);
  }

</script>
@stop