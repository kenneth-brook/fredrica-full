
<div class="row">
    <div class="columns">
        @if(count($all_categories))
        <label for="select field">Select Field Group
            <select id="select-category">
                @foreach($all_categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                @endforeach
            </select>
        </label>
        @endif
    </div>
</div>
<div class="row">
    <div class="columns">
        <input type="button" id="selectBtn" value="Select Group"  class="button alert">
    </div>
</div>
<div class="row">
    <div class="columns">
     <hr />
    </div>
</div>
<div class="row">
    <div class="columns" id="field-container">

    </div>
</div>