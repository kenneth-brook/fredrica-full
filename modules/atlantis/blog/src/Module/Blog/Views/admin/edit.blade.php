@extends('atlantis-admin::admin-shell')

@section('scripts')
    @parent
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/foundation-datepicker.min.js') !!}
    {!! Html::script('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/js/plugins/tagsInput/jquery.tagsinput.min.js') !!}
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
                    <h1 class="huge page-title">Edit Entry</h1>
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
            {!! Form::open(array('url' => url('/').'/admin/modules/blog/edit/' . $oBlog->id, 'data-abide' => '', 'novalidate'=> '')) !!}
            <div class="row">
                <div class="columns">
                    <div class="float-right">
                        <div class="buttons">
                            <a href="{{url('/')}}/admin/modules/blog" class="back button tiny top primary" title="Go to Blog list"
                               data-tooltip>
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
                            <a href="#panel1" aria-selected="true">{{ $oBlog->title }}</a>
                            <span class="actions">
                                <a data-tooltip title="Preview Post" target="blank" href="{{ $previewUrl }}" class="icon icon-Export top" role="tab"></a>
                            </span>
                        </li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">

                            <div class="row">
                                <div class="columns large-9">
                                    <div class="row">
                                        <div class="columns medium-4">
                                            @if ($errors->get('title'))
                                                <label for="title" class="is-invalid-label"><span
                                                            class="form-error is-visible">{{ $errors->get('title')[0] }}</span>
                                                    {!! Form::input('text', 'title', old('title', $oBlog->title), ['class' => 'is-invalid-input', 'id'=>'title']) !!}
                                                </label>
                                            @else
                                                <label for="title">Title <span class="form-error">is required.</span>
                                                    {!! Form::input('text', 'title', old('title', $oBlog->title), ['required'=>'required', 'id'=>'title']) !!}
                                                </label>
                                            @endif
                                        </div>
                                        <div class="columns medium-4">
                                            @if ($errors->get('url'))
                                                <label for="url" class="is-invalid-label"><span
                                                            class="form-error is-visible">{{ $errors->get('url')[0] }}</span>
                                                    {!! Form::input('text', 'url', old('url', $oBlog->url), ['class' => 'is-invalid-input', 'id'=>'url']) !!}
                                                </label>
                                            @else
                                                <label for="url">Entry Url <span class="form-error">is required.</span>
                                                    {!! Form::input('text', 'url', old('url', $oBlog->url), ['required'=>'required', 'id'=>'url']) !!}
                                                </label>
                                            @endif
                                        </div>
                                        <div class="columns medium-2">

                                            @if ($errors->get('posted_date'))
                                            <label for="posted_date" class="is-invalid-label"><span
                                                class="form-error is-visible ">{{ $errors->get('posted_date')[0] }}</span>
                                                <span class="fa fa-calendar dtp-wrapper">
                                                    {!! Form::input('text', 'posted_date', old('posted_date', $posted_date), ['class' => 'is-invalid-input dtp', 'id'=>'posted_date']) !!}
                                                </span>
                                            </label>
                                            @else
                                            <label for="posted_date">Posted Date
                                                <span class="icon icon-Help top" data-tooltip title="Post entry publish date. Set future date for scheduled post."></span>
                                                <span class="fa fa-calendar dtp-wrapper">
                                                    {!! Form::input('text', 'posted_date', old('posted_date', $posted_date), ['class' => 'dtp', 'id'=>'posted_date']) !!}
                                                </span>
                                            </label>
                                            @endif
                                        </div>
                                        <div class="columns medium-2">
                                            <label for="expire_date" {{ $errors->get('expire_date') ?  'class="is-invalid-label"' : '' }} >
                                                Expire Date
                                                <span class="icon icon-Help top" data-tooltip title="Post expiration date. Leave blank for no expiration"></span>
                                                @if ($errors->get('expire_date'))
                                                <span class="form-error is-visible ">{{ $errors->get('expire_date')[0] }}
                                                </span>
                                                @endif
                                                <span class="fa fa-calendar dtp-wrapper">
                                                    {!! Form::input('text', 'expire_date', $expire_date, ['class' => 'dtp', 'id'=>'expire_date']) !!}
                                                </span>
                                            </label>
                                        </div>

                                        <div class="columns medium-4">
                                            @if ($errors->get('nickname'))
                                                <label for="nickname" class="is-invalid-label"><span
                                                            class="form-error is-visible">{{ $errors->get('nickname')[0] }}</span>
                                                    {!! Form::input('text', 'nickname', old('nickname', $oBlog->nickname), ['class' => 'is-invalid-input', 'id'=>'nickname']) !!}
                                                </label>
                                            @else
                                                <label for="nickname">Nickname <span
                                                            class="form-error">is required.</span>
                                                    {!! Form::input('text', 'nickname', old('nickname', $oBlog->nickname), ['required'=>'required', 'id'=>'nickname']) !!}
                                                </label>
                                            @endif
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="allow_comments">Comments
                                                {!! Form::select('allow_comments', $allow_comments_dropdown, $oBlog->allow_comments, ['id' => 'form-allow_comments']) !!}
                                            </label>
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="status">Status
                                                {!! Form::select('status', $status_dropdown, $oBlog->status, ['id' => 'form-status']) !!}
                                            </label>
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="">Tags
                                                {!! Form::input('text', 'tags', old('tags', $tags), ['class' => 'inputtags']) !!}
                                            </label>
                                        </div>
                                        <div class="columns medium-4">
                                            <label for="seo_title">Seo Title
                                                <span class="icon icon-Help top" data-tooltip
                                                      title="This title appears between the <title> tags"></span>
                                                {!! Form::input('text', 'seo_title', old('seo_title', $oBlog->seo_title), ['id'=>'seo_title']) !!}
                                            </label>
                                        </div>

                                        <div class="columns medium-4">
                                            <label for="meta_description">Meta Description
                                                <small id="meta_description_info">255 characters left</small>
                                                <span class="icon icon-Help top" data-tooltip
                                                      title="If you leave this field blank, the global description will be used."></span>
                                                {!! Form::input('text', 'meta_description', old('meta_description', $oBlog->meta_description), ['id'=>'meta_description']) !!}
                                            </label>
                                        </div>


                                        <div class="columns end">
                                            <label for="custom_form">Body
                                                {!! \Editor::set('body', old('body', $oBlog->body), ['rows' => 15, 'id' => 'custom_form']) !!}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="columns large-3">

                                        <ul class="accordion" data-accordion>
                                            <li class="accordion-item is-active" data-accordion-item>
                                                <a href="#" class="accordion-title">Add Custom Blurb</a>
                                                <div class="accordion-content" data-tab-content>
                                                    {!! Form::textarea('blurb', old('blurb', $oBlog->blurb), ['rows' => 5, 'id' => 'blurb']) !!}
                                                    <p>Use Blurb</p>
                                                    <div class="switch tiny">
                                                        {!! Form::checkbox('use_blurb', 1, $oBlog->use_blurb, ['class' => 'switch-input', 'id' => 'useBlurbSwitch']) !!}
                                                        <label class="switch-paddle" for="useBlurbSwitch">
                            <span class="show-for-sr">
                            </span>
                                                        </label>
                                                    </div>
                                                    <p>or set how many words you want to use from body</p>
                                                    {!! Form::input('text', 'body_words', old('body_words', $oBlog->body_words), []) !!}
                                                </div>
                                            </li>

                                            <li class="accordion-item" data-accordion-item>
                                                <a href="#" class="accordion-title">Categories</a>
                                                <div class="accordion-content" data-tab-content>
                                                    @if(count($categories))
                                                        <ul>
                                                            @foreach($categories as $c)
                                                                @if(in_array($c->id, $category_idx) && count($category_idx))
                                                                    <li>{!! Form::checkbox('category['.$c->id.']', $c->category_name, TRUE) !!}  {!! $c->category_name !!}</li>
                                                                @else
                                                                    <li>{!! Form::checkbox('category['.$c->id.']', $c->category_name) !!}  {!! $c->category_name !!}</li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            </li>

                                            <li class="accordion-item" data-accordion-item>
                                                <a href="#" class="accordion-title">Description</a>
                                                <div class="accordion-content" data-tab-content>
                                                    {!! Form::textarea('description', old('description', $oBlog->description), ['rows' => 5, 'id' => 'description']) !!}
                                                </div>
                                            </li>
                                            <li class="accordion-item" data-accordion-item>
                                                <a href="#" class="accordion-title">Preview Image</a>
                                                <div class="accordion-content" data-tab-content>
                                                    {!! \MediaTools::createImageSelector(NULL, FALSE, isset($oBlog->imgs) ? [$oBlog->imgs] : ['1','2']) !!}
                                                </div>
                                            </li>
                                            <li class="accordion-item" data-accordion-item>
                                            <a href="#" class="accordion-title">Related Posts</a>
                                            <div class="accordion-content" data-tab-content>

                                                <p>Select related post. If NONE - related posts will be matched by category. Use ctrl to select multiple.</p>
                                                {!! Form::select('relatedIds[]', [0 => 'NONE'] + $posts, $related,
                                                ['size' => 15, 'multiple' => 'multiple']) !!}

                                            </div>
                                        </li>
                                        </ul>
                                        @if(trait_exists('\Module\Relativity\Traits\RelativityTrait'))
                                            {!! \Module\Relativity\Controllers\RelativityController::theVocabulariesBySlugs(['blog'], get_class($oBlog),  $oBlog->id) !!}
                                        @endif

                                </div>
                            </div>
                            {!! \MediaTools::createGallerySelector($oBlog->gallery_id) !!}
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </section>
    </main>
    <footer>
        @include("blog-admin::admin.includes.bulb")
        <div class="row">
            <div class="columns">
            </div>
        </div>
    </footer>
@stop
