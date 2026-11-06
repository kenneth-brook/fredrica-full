<div class="reveal" id="cloneForm{{$obj->id}}" data-reveal>

    {!! Form::open(array('url' => 'admin/modules/forms/clone-form/' . $obj->id, 'data-abide' => '', 'novalidate'=> '')) !!}
            {!!  csrf_field() !!}
        <h1>Clone Form</h1>
        <hr>
        <input type='hidden' name='form_id' value='{{$obj->id}}'> 
        <label for="clone-name">Clone Name
            <input type='text' name='clone-name' value='{{$obj->name}}-Clone' required="">
        </label>    

        <hr>
        <input type='submit' name='submit-clone' value='Clone' class='button danger'>
    {!! Form::close() !!}

</div>