{!! $form->submit_with_ajax || $form->hide_form_upon_success || $form->use_front_end_validation ?  \Atlantis\Helpers\Assets::registerScript('modules/atlantis/forms/src/Module/Forms/Assets/ajax_submit.js', 6000 ) : "" !!}
{!! $form->submit_with_ajax || $form->hide_form_upon_success ?   \Atlantis\Helpers\Assets::registerScript('https://code.jquery.com/jquery-1.12.4.min.js', 9)  : "" !!}

<div class="msg-container" id="msg-form-{{ $escaped_name }}">
@if (Session::has('success'))
    {{-- show success message --}}
    <div class="success">
        <span data-form-name="{{$form->name}}" class="success-msg">{{ !Session::get('success') == '' ? Session::get('success') : 'Form sent successfully.' }}</span>
    </div>
@endif
@if ($errors->all())
    
    <div class="fail">
    @if ($errors->has('flood_control'))
        {{$errors->first('flood_control')}}
    @else
        {{ !empty($form->error_message) ? $form->error_message : 'Some of the form fields were not completed, please review your form and submit again.'}}
    @endif
    </div>
@endif
</div>

@if (!(Session::has("success") && $form->hide_form_upon_success))
<div class="form-wrapper" id="{{ $escaped_name }}">
    @if (!empty($form->before_form_text))
        <div class="form-text-before">
            {{ $form->before_form_text }}
        </div>
    @endif
    
    {!! Form::open(array_merge(['url' => $url,
        'data-hide-after-submit' => $form->hide_form_upon_success ? 1 : 0,
        'data-use-ajax' => $form->submit_with_ajax ? 1 : 0,
        'data-is-custom-form' => 0,
        'data-invisible-captcha' => $form->captcha == 1 && $form->captcha_namespace == 'Module\Forms\Captcha\InvisibleReCaptcha' ? 1 : 0,
        'data-use_front_end_validation' => $form->use_front_end_validation ? 1 : 0,
        'class' => $form->form_class, 'name' => $form->name, 'id' => 'form-' . $escaped_name], $custom_form_attributes)) !!}
    <input type="hidden" name="form_id" value="{{ $form->id }}">
    {{-- build form items --}}
    @foreach($items as $item)
        
        {!! $item !!}
    @endforeach
    {{-- Captcha --}}
    @if($captcha != NULL)
        <div class="captcha {{$errors->first('captcha') ? 'has-error' : '' }}">
            <span class="error-msg a3-error-wrap">{{$errors->first('captcha')}}</span>
            {!! $captcha !!}
        </div>
    @endif
    {{-- submit button --}}
    <div class="submit">
        {!! $submit_button !!}
    </div>
    {!! Form::close() !!}
    @if (!empty($form->after_form_text))
        <div class="form-text-after">
            {{ $form->after_form_text }}
        </div>
    @endif
</div>

@endif
{{-- dd($patt_func_params) --}}
<style>
form.ajax-loading{
  opacity: .5;
}
form.ajax-loading:after{
     content: '\021BB';
    font-weight: bold;
    display: inline-block;
    text-align: center;
    font-size: 5rem;
    -webkit-animation: spin 1s linear infinite;
    -moz-animation: spin 1s linear infinite;
    animation: spin 1s linear infinite;
}
.a3-error-wrap{
  color: red;
}


@-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
@-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }

</style>