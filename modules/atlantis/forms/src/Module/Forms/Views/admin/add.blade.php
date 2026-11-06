@extends('atlantis-admin::admin-shell')

@section('scripts')
@parent
{{-- Add scripts per template --}}
{!! Html::script('modules/atlantis/forms/src/Module/Forms/Assets/forms.js') !!} 
{!! Html::script('modules/atlantis/forms/src/Module/Forms/Assets/jquery-sortable.js') !!} 
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
                <h1 class="huge page-title">Add Form</h1>
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
        {!! Form::open(array('url' => url('/').'/admin/modules/forms/add', 'data-abide' => '', 'novalidate'=> '')) !!}
        <div class="row">
            <div class="columns">
                <div class="float-right">
                    <div class="buttons">
                        <a href="{{url('/')}}/admin/modules/forms" class="back button tiny top primary" title="Go to Forms" data-tooltip>
                            <span class=" back icon icon-Goto"></span>
                        </a>
                        {!! Form::input('submit', '_save_close', 'Save & Close', ['class' => 'alert button', 'id'=>'save-close-btn']) !!}
                        {!! Form::input('submit', '_update', 'Update', ['class' => 'alert button', 'id'=>'update-btn']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="columns small-12">
                <ul class="tabs" data-tabs id="example-tabs">
                    <li class="tabs-title is-active main">
                        <!-- data-status: active, disabled or dev -->
                        <a href="#panel1" aria-selected="true">New Form</a>
                    </li>
                    <li class="tabs-title">
                        <!-- data-status: active, disabled or dev -->
                        <a href="#panel2" aria-selected="true">Lookup Data</a>
                    </li>
                </ul>
                <div class="tabs-content" data-tabs-content="example-tabs">
                    <div class="tabs-panel is-active" id="panel1">

                        <div class="row">
                            <div class="columns large-9">
                                <div class="row">
                                    <div class="columns medium-4">
                                        @if ($errors->get('name'))
                                        <label for="name" class="is-invalid-label"><span class="form-error is-visible">{{ $errors->get('name')[0] }}</span>
                                            <span class="icon icon-Help top" data-tooltip title="This will be used to identify the Menu."></span>
                                            {!! Form::input('text', 'name', old('name'), ['class' => 'is-invalid-input', 'id'=>'name']) !!}
                                        </label>
                                        @else
                                        <label for="name">Form Name <span class="form-error">is required.</span>
                                            <span class="icon icon-Help top" data-tooltip title="This will be used to identify the Menu."></span>
                                            {!! Form::input('text', 'name', old('name'), ['id'=>'name', 'required'=>'required']) !!}
                                        </label>
                                        @endif
                                    </div>
                                    <div class="columns medium-4">
                                        <label for="form_class">Form Class
                                            {!! Form::input('text', 'form_class', old('form_class'), ['id'=>'form_class']) !!}
                                        </label>                    
                                    </div>
                                    <div class="columns medium-4">
                                        <label for="btn_value">Submit button name <span class="form-error">is required.</span>
                                            {!! Form::input('text', 'btn_value', old('btn_value'), ['id' => 'btn_value', 'required'=>'required']) !!}
                                        </label>                    
                                    </div>
                                    <div class="columns medium-4">
                                        <label for="btn_class">Button class
                                            {!! Form::input('text', 'btn_class', old('btn_class'), ['id' => 'btn_class']) !!}
                                        </label>                    
                                    </div>
                                    <div class="columns medium-4">
                                        <label for="redirect_url">Redirect url after success
                                            {!! Form::input('text', 'redirect_url', old('redirect_url'), ['id' => 'redirect_url']) !!}
                                        </label>                    
                                    </div>
                                    <div class="columns medium-4">
                                        <label for="redirect_url_error">Redirect url after error
                                            {!! Form::input('text', 'redirect_url_error', old('redirect_url_error'), ['id' => 'redirect_url_error']) !!}
                                        </label>                    
                                    </div>
                                    <div class="columns medium-4">
                                        <label for="hide_form_upon_success">Hide form after successful submission
                                            <div class="switch tiny">
                                                {!! Form::checkbox( 'hide_form_upon_success', '1', old('hide_form_upon_success') ? true : false, ['class' => 'switch-input', 'id' => 'hide_form_upon_success']) !!}
                                                <label class="switch-paddle" for="hide_form_upon_success">
                                                    <span class="show-for-sr">
                                                    </span>
                                                </label>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="columns medium-4">
                                        <label for="submit_with_ajax">Submit the form with AJAX
                                            <div class="switch tiny">
                                                {!! Form::checkbox( 'submit_with_ajax', '1', old('submit_with_ajax') ? true : false, ['class' => 'switch-input', 'id' => 'submit_with_ajax']) !!}
                                                <label class="switch-paddle" for="submit_with_ajax">
                                                    <span class="show-for-sr">
                                                    </span>
                                                </label>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="columns medium-4">
                                        <label for="use_flood_control">Flood Control
                                            <div class="switch tiny">
                                                {!! Form::checkbox( 'use_flood_control', '1', old('use_flood_control') ? true : false, ['class' => 'switch-input', 'id' => 'use_flood_control']) !!}
                                                <label class="switch-paddle" for="use_flood_control">
                                                    <span class="show-for-sr">
                                                    </span>
                                                </label>
                                            </div>
                                        </label>
                                    </div>


                                    <div class="columns">
                                        <hr>
                                        <div class="row">                      

                                            @if ($errors->get('field_value'))
                                            <div class="columns large-12 ">
                                                @foreach ($errors->get('field_value') as $filed_value_error)
                                                <div class="callout alert"><h5>{{ $filed_value_error }}</h5></div>
                                                @endforeach
                                            </div>
                                            @endif

                                            <div class="columns large-12">
                                                <label class="float-left" for="">Form Fields</label>
                                                <div class="float-right">
                                                    <a class="button hollow  toggle-fields">Toggle All</a>                                                        
                                                    <a class="button alert add-menu-item">Add New Field</a>
                                                </div>
                                            </div>
                                            <ol id="sortable" class="columns large-12">
                                                @foreach ($itemsFields as $item)
                                                <li class="row-item" id="row_items_{!! $item['weight']['value'] !!}">
                                                    <div class="callout">
                                                        <div class="row menu-item">
                                                            <div class="columns">
                                                                <label for="">
                                                                    <span class="fa fa-bars move"></span>
                                                                    <span class="index">{{ $item['weight']['value'] }}</span>.
                                                                    <span class="item-title">
                                                                        {{ $item['field_name']['value'] }}
                                                                    </span>
                                                                    <small>                   
                                                                        [type="<span class="item-type">{{ $item['field_type']['value'] }}</span>"]
                                                                    </small>
                                                                </label>
                                                                <span class="actions">
                                                                    <a data-toggle="advanced-item{!! $item['weight']['value'] !!}" class=""><span data-tooltip title="Show/Hide Advanced Settings" class="icon icon-Settings top"></span></a>
                                                                </span>
                                                            </div>
                                                            <div class="columns">
                                                                <div class="row advanced expanded" data-length="1" id="advanced-item{!! $item['weight']['value'] !!}" data-toggler=".expanded">
                                                                    <br>
                                                                    <div class="columns large-3">
                                                                        <label for="{!! $item['field_name']['name'] !!}">
                                                                            Name <span class="form-error">is required field</span>
                                                                            <span class="icon icon-Help top" data-tooltip title="Look tips for more information"></span>
                                                                            {!! Form::input('text', $item['field_name']['name'], $item['field_name']['value'], ['required'=>'required', 'class' => 'name']) !!}
                                                                        </label>
                                                                        <label for="">
                                                                            Type  
                                                                            <span class="icon icon-Help top" data-tooltip title="Look tips for more information"></span>
                                                                            {!! Form::select($item['field_type']['name'], $fieldTypes, $item['field_type']['value'], ['id' => '', 'class' => 'type']) !!}
                                                                        </select>
                                                                    </label>
                                                                </div>
                                                                <div class="columns large-3">
                                                                    <label for="">
                                                                        Label
                                                                        <span class="icon icon-Help top" data-tooltip title="Look tips for more information"></span>
                                                                        {!! Form::input('text', $item['label']['name'], $item['label']['value'], []) !!}
                                                                    </label> 
                                                                    <label for="">
                                                                        Validation
                                                                        <span class="icon icon-Help top" data-tooltip title="Look tips for more information"></span>
                                                                        {!! Form::select($item['validation']['name'], $validationRules, $item['validation']['value'], ['id' => '']) !!}
                                                                    </label>
                                                                </div>
                                                                <div class="columns large-3">
                                                                    <label for="">
                                                                        Custom Attributes
                                                                        <span class="icon icon-Help top" data-tooltip title="Look tips for more information. One per line. placeholder=>Custom attributes"></span>
                                                                        {!! Form::textarea($item['attributes']['name'], $item['attributes']['value'], ['rows' => 4, 'cols' => '30', 'id' => '']) !!}
                                                                    </label>


                                                                </div>
                                                                <div class="columns large-3">
                                                                    <label for="">
                                                                        Value
                                                                        <span class="icon icon-Help top" data-tooltip title="Look tips for more information"></span>
                                                                        {!! Form::textarea($item['field_value']['name'], $item['field_value']['value'], ['rows' => 4, 'cols' => '30', 'id' => '']) !!}
                                                                    </label>

                                                                    {!! Form::input('hidden', $item['weight']['name'], $item['weight']['value'], ['required'=>'required', 'min'=>'1']) !!}
                                                                </div>
                                                                <div class="columns large-6">
                                                                    <label for="">
                                                                        Error message
                                                                        <span class="icon icon-Help top" data-tooltip title="Validation error messag. HTML allowed"></span>
                                                                        {!! Form::input('text', $item['validation_msg']['name'], $item['validation_msg']['value'], []) !!}
                                                                    </label>
                                                                </div>
                                                                <div class="columns large-6">
                                                                    <label for="">
                                                                        Actions<br>
                                                                        <a id="btn_delete_{!! $item['weight']['value'] !!}" class="button alert">Delete Item</a>
                                                                    </label>
                                                                </div>

                                                                <div class="columns large-12 use-as-switches">
                                                                    <label for="">Use as Reply-To Address</label>
                                                                    <div class="switch tiny">
                                                                        {!! Form::checkbox($item['use_reply_to']['name'], '1', $item['use_reply_to']['value'], ['class' => 'switch-input', 'id' => $item['use_reply_to']['name'], 'disabled' => 'disabled' ]) !!}
                                                                        <label class="switch-paddle" for="{{$item['use_reply_to']['name']}}"></label>
                                                                    </div>
                                                                    <label for="">Use as Email From Address <span class="icon icon-Help top" data-tooltip title="Selecting this checkbox will overwrite the email from address in the bottom"></span></label>
                                                                    <div class="switch tiny">
                                                                        {!! Form::checkbox($item['use_email_from']['name'], '1', $item['use_email_from']['value'], ['class' => 'switch-input', 'id' => $item['use_email_from']['name'], 'disabled' => 'disabled']) !!}
                                                                        <label class="switch-paddle" for="{{$item['use_email_from']['name']}}"></label>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ol>
                                        <div class="columns large-12">
                                            <div class="float-right">                              
                                                <a class="button alert add-menu-item">Add New Field</a>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div class="columns">                  
                                    <label for="use_custom_form">Use Custom Template                      
                                    </label>                    
                                    <div class="switch tiny">
                                        {!! Form::checkbox('use_custom_form', 1, FALSE, ['class' => 'switch-input', 'id' => 'use_custom_form']) !!}
                                        <label class="switch-paddle" for="use_custom_form">
                                            <span class="show-for-sr">
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="columns">
                                    <label for="custom_form">Custom template
                                        <a data-open="customTmpl" class="button hollow float-right customTmpl">Generate template</a>
                                    </label>
                                    <br>
                                    {!! \Editor::set('custom_form', old('custom_form'), ['rows' => 15, 'id' => 'custom_form']) !!}
                                    <hr>
                                </div>
                                <div class="columns medium-4">
                                    <label for="email_check">Should this form email its results?                      
                                        <div class="switch tiny">
                                            {!! Form::checkbox('email_check', 1, FALSE, ['class' => 'switch-input', 'id' => 'email_check']) !!}
                                            <label class="switch-paddle" for="email_check">
                                                <span class="show-for-sr">
                                                </span>
                                            </label>
                                        </div>
                                    </label>                    
                                </div>                  
                                <div class="columns medium-4">
                                    @if ($errors->get('email_from'))
                                    <label for="email_from">Email from (your@yourhost.com)<span class="form-error is-visible">{{ $errors->get('email_from')[0] }}</span>
                                        {!! Form::input('text', 'email_from', old('email_from'), ['id' => 'email_from']) !!}
                                    </label>
                                    @else
                                    <label for="email_from">Email from (your@yourhost.com)
                                        {!! Form::input('text', 'email_from', old('email_from'), ['id' => 'email_from']) !!}
                                    </label> 
                                    @endif
                                </div>
                                <div class="columns medium-4 end">
                                    <label for="emails">Form Email Recipients, Comma Separated
                                        {!! Form::input('text', 'emails', old('emails'), ['id' => 'emails']) !!}
                                    </label>                    
                                </div>
                                <div class="columns medium-12 end">
                                    <label for="subject">Email subject
                                        {!! Form::input('text', 'subject', old('subject'), ['id' => 'subject']) !!}
                                    </label>                    
                                </div>
                            </div>
                        </div>
                        <div class="columns large-3">
                            <aside>
                                <ul class="accordion" data-accordion>
                                    <li class="accordion-item is-active" data-accordion-item>
                                         <a href="#" class="accordion-title">Submit Messages</a>
                                        <div class="accordion-content" data-tab-content>
                                            <p>Successful Submit Message</p>
                                            {!! Form::textarea('message', old('message'), ['rows' => 5, 'id' => 'message']) !!}
                                            <p>Error Submit Message</p>
                                            {!! Form::textarea('error_message', old('error_message'), ['rows' => 5, 'id' => 'error_message']) !!}
                                        </div>
                                    </li>

                                    <li class="accordion-item " data-accordion-item>
                                        <a href="#" class="accordion-title">Custom Form Attributes</a>
                                        <div class="accordion-content" data-tab-content>
                                            {!! Form::textarea('custom_attributes', old('custom_attributes'), ['rows' => 3, 'id' => 'custom_attributes']) !!}
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">Google Analytics</a>
                                        <div class="accordion-content" data-tab-content>
                                            <p>Use Google Analytics</p>
                                            <div class="switch tiny">
                                                {!! Form::checkbox('ga', 1, TRUE, ['class' => 'switch-input', 'id' => 'gaSwitch']) !!}
                                                <label class="switch-paddle" for="gaSwitch">
                                                    <span class="show-for-sr">
                                                        Cache Enabled
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">Captcha</a>
                                        <div class="accordion-content" data-tab-content>
                                            <div class="row">
                                                <div class="columns small-6">
                                                    <label for="captchaSwitch">Use Captcha</label>
                                                    <div class="switch tiny">
                                                        {!! Form::checkbox('captcha', 1, FALSE, ['class' => 'switch-input', 'id' => 'captchaSwitch']) !!}
                                                        <label class="switch-paddle" for="captchaSwitch">
                                                            <span class="show-for-sr">
                                                                Use Captcha
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="columns small-6">
                                                    <label for="select_captcha">Select captcha</label>
                                                    {!! Form::select('select_captcha', $aCaptcha, NULL, ['id' => 'select_captcha']) !!}
                                                </div>
                                                <div class="columns">
                                                <p class="help-text">
                                                    <i class="fa fa-exclamation-triangle"></i> You cant use both - invisible and recaptcha on single page. <br>
                                                    Forms with normal submit execute InvisibleReCaptcha on form submit.
                                                    Forms with ajax submit execute InvisibleReCaptcha on form hover.
                                                </p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">Before form text</a>
                                        <div class="accordion-content" data-tab-content>                        
                                            {!! Form::textarea('before_form_text', old('before_form_text'), ['rows' => 5, 'id'=>'before_form_text']) !!}
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">After form text</a>
                                        <div class="accordion-content" data-tab-content>                        
                                            {!! Form::textarea('after_form_text', old('after_form_text'), ['rows' => 5, 'id'=>'after_form_text']) !!}
                                        </div>
                                    </li>
                                    <li class="accordion-item" data-accordion-item>
                                        <a href="#" class="accordion-title">Front End Validation</a>
                                        <div class="accordion-content" data-tab-content>
                                            <label>JS Validation
                                            <span class="icon icon-Help top" data-tooltip title="If on, the validation messages will appear on input focusout"></span></label>
                                            <div class="switch tiny">
                                                {!! Form::checkbox('use_front_end_validation', '1', FALSE, ['class' => 'switch-input', 'id' => 'use_front_end_validation']) !!}
                                                <label class="switch-paddle" for="use_front_end_validation">
                                                    <span class="show-for-sr">
                                                        Cache Enabled
                                                    </span>
                                                </label>
                                            </div>  
                                            <label for="error_wrapper_class">
                                            Error message container class
                                            <span class="icon icon-Help top" data-tooltip title="Class of the element where the error wiil appear."></span>
                                            {!! Form::input('text', 'error_wrapper_class', old('error_wrapper_class'), ['id' => 'error_wrapper_class']) !!}
                                            </label>
                                            <label for="input_error_class">
                                            Invalid Field Class 
                                            <span class="icon icon-Help top" data-tooltip title="Class to be added to the parent element of the invalid field."></span>
                                            {!! Form::input('text', 'input_error_class', old('input_error_class'), ['id' => 'input_error_class']) !!}
                                            </label>
                                            kbd>has-error</kbd> as INVALID FIELD CLASS and <kbd>form-error</kbd> as a ERROR MESSAGE CONTAINER CLASS</p>
                                            <code>&lt;div class=&quot;small-12 columns&quot;&gt;<br />&nbsp; &lt;label for=&quot;&quot;&gt;name<br />&nbsp; &nbsp; &lt;span class=&quot;form-error&quot;&gt; { !!name!! }&lt;/span&gt;<br />&nbsp; &nbsp; { {name} }<br />&nbsp; &lt;/label&gt;<br />&lt;/div&gt;</code>

                                        </div>
                                    </li>
                                </ul>
                            </aside>
                        </div>
                    </div>
                </div>
                <div class="tabs-panel" id="panel2">
                    <div class="columns large-12">
                        <h4>States</h4>
                        <table style="width:100%">
                            @foreach ($aStates as $arr)                   

                            <tr>
                                @foreach($arr as $code => $state)
                                <td>{{$code}}=>{{$state}}</td>                      
                                @endforeach
                            </tr>

                            @endforeach       
                        </table>
                    </div>
                    <div class="row">
                        <div class="columns large-12">
                            <h4>Countries</h4>
                            <table style="width:100%">
                                @foreach ($aCountries as $arr)  
                                <tr>
                                    @foreach($arr as $code => $country)
                                    <td>{{$code}}=>{{$country}}</td>                      
                                    @endforeach
                                </tr>
                                @endforeach       
                            </table>
                        </div>
                    </div>                        
                </div>
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
          <div class="reveal" id="customTmpl" data-reveal data-overlay="true" data-v-offset="30">
              <h5>Get Your Custom Template</h5>
              <div class="errors"></div>
              <ul class="tabs" data-tabs id="example-tabs">
                  <li class="tabs-title is-active"><a href="#panelBasic" aria-selected="true">Basic Template</a></li>
                  <li class="tabs-title"><a href="#panelFoundation">Foundation Template</a></li>
                  <li class="tabs-title"><a href="#panelBootstrap">Bootstrap Template</a></li>
              </ul>
              <div class="tabs-content" data-tabs-content="example-tabs">
                  <div class="tabs-panel is-active" id="panelBasic">
                      <textarea readonly rows="15"></textarea>
                  </div>
                  <div class="tabs-panel" id="panelFoundation">
                    <textarea readonly rows="15"></textarea>
                </div>
                <div class="tabs-panel" id="panelBootstrap">
                    <textarea readonly rows="15"></textarea>
                </div>
            </div>
            <br>
            <a class="button alert copy-tmpl">Copy to Clipboard <span class="text">Copied</span></a>
            <button class="close-button" data-close aria-label="Close reveal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
</footer>
@stop