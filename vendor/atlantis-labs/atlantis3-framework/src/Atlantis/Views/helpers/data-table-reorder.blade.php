<a class="show-filter-bth hide-for-medium button" data-toggle="filter-{{ $table_id }}"><i class="fa fa-filter" aria-hidden="true"></i></a>
<div class="list-filter table-filter show-for-medium" id="filter-{{ $table_id }}" data-toggler=".show-for-medium">
  <div class="switch float-left">
    <input class="switch-input" id="switchReorder-{{ $table_id }}" type="checkbox" name="">
    <label class="switch-paddle float-left" for="switchReorder-{{ $table_id }}">
      <span class="show-for-sr">@lang('admin::views.Reorder')</span>
    </label>
    <label class="float-left" for="switchReorder-{{ $table_id }}">&nbsp;&nbsp;@lang('admin::views.Reorder')</label>
  </div>

  @if (!empty($aBulkActions))
  {!! Form::open(array('url' => $bulk_action_url, 'class' => 'bulk-action form_bulk_action', 'data-table-id' => $table_id)) !!}
    

    <select name="action" class="bulk">
      @foreach($aBulkActions as $action)
      <option value="{{ $action['key'] }}">{{ $action['name'] }}</option>
      @endforeach
    </select>
    <input type="hidden" name="bulk_action_ids">
    <input type="submit" value="Apply" class="button alert apply disabled form_bulk_button">
  {!! Form::close() !!}
  @endif

  {!! Form::select(NULL, $lengthMenu, $admin_items_per_page, ['class' => 'show-count', 'data-table-id' => $table_id]) !!} 

  <div class="search icon icon-Search">
    <input type="text" class="search-in-table" data-table-id="{{$table_id}}">
  </div>
</div>
<table class="dataTable {{ $tableClass }}" id="{{ $table_id }}">
  <thead>
    <tr>
      @foreach($columns as $column)
      <th class="{!! $column['class-th'] !!}">{!! $column['title'] !!}</th>
      @endforeach
    </tr>
  </thead>       
</table>