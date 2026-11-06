<a class="show-filter-bth hide-for-medium button" data-toggle="filter-{{ $table_id }}"><i class="fa fa-filter" aria-hidden="true"></i></a>
<div class="list-filter table-filter show-for-medium" id="filter-{{ $table_id }}" data-toggler=".show-for-medium">
  @if (!empty($aBulkActions))
  {!! Form::open(array('url' => $bulk_action_url, 'class' => 'bulk-action form_bulk_action', 'data-table-id' => $table_id)) !!}
  
  <select name="action" class="bulk">
      @foreach($aBulkActions as $action)
      <option value="{{ $action['key'] }}">{{ $action['name'] }}</option>
      @endforeach
  </select>
  <input type="hidden" name="bulk_action_ids">
  <input type="submit" value="@lang('admin::views.Apply')" class="button alert apply disabled form_bulk_button">
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


<script type="text/javascript">
/****
*
* Override atlantisUtilities.reflow.datatables each time when table is generated dynmicly.
*
*/
if (typeof atlantisUtilities != 'undefined') {

    atlantisUtilities.reflow.tablesToReflow.push('{{ $table_id }}');


    atlantisUtilities.reflow.datatables = function() {
        if (typeof $.fn.DataTables == 'function') {
            initAsyncLoadedTable();
        } else {            
            $.getScript('vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Assets/DataTables/media/js/jquery.dataTables.min.js', function(data, textStatus, jqxhr) {
                initAsyncLoadedTable();
            });
        }

        function initAsyncLoadedTable() {
            $.each(atlantisUtilities.reflow.tablesToReflow ,function (k, table) {
                
                var atlTable = $('#'+table).not('.dataTable').DataTable({
					language: {
						"decimal": "",
						"emptyTable": "@lang('admin::views.No data available in table')",
						"info": "@lang('admin::views.Showing _START_ to _END_ of _TOTAL_ entries')",
						"infoEmpty": "@lang('admin::views.Showing 0 to 0 of 0 entries')",
						"infoFiltered": "@lang('admin::views.(filtered from _MAX_ total entries)')",
						"infoPostFix": "",
						"thousands": ",",
						"lengthMenu": "@lang('admin::views.Show _MENU_ entries')",
						"loadingRecords": "@lang('admin::views.Loading...')",
						"processing": "@lang('admin::views.Processing...')",
						"search": "@lang('admin::views.Search:')",
						"zeroRecords": "@lang('admin::views.No matching records found')",
						"paginate": {
							"first": "@lang('admin::views.First')",
							"last": "@lang('admin::views.Last')",
							"next": "@lang('admin::views.Next')",
							"previous": "@lang('admin::views.Previous')"
						},
						"aria": {
							"sortAscending": "@lang('admin::views.: activate to sort column ascending')",
							"sortDescending": "@lang('admin::views.: activate to sort column descending')"
						}
					},
                    dom: '<"top"iflp<"clear">>rt<"bottom"iflp<"clear">>',
                    pageLength: {!!$admin_items_per_page!!},
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ $url }}",
                        "type": "POST",
                        "data": {
                            "_token": "{{ csrf_token() }}",
                            "namespaceClass": "{{ $namespaceClass }}",
                            @foreach($postParams as $post_key => $post_value)
                            "{{ $post_key }}": "{{ $post_value }}",
                            @endforeach
                        }
                    },
                    columns: [
                    @foreach($columns as $column) { "data": "{{ $column['key'] }}" },
                    @endforeach
                    ],
                    columnDefs: [
                    @foreach($columns as $k => $column)
                    { className: "{{ $column['class-td'] }}", "targets": [{{ $k }}] },
                    @endforeach 
                    { targets: 'no-sort', orderable: false }
                    ],
                    autoWidth: false,
                    searching: true,
                    info: false,
                    order: [
                    @foreach($columns as $k => $column)
                    @if($column['order']['sorting'] == TRUE)
                    [{{ $k }}, "{{ strtolower($column['order']['order']) }}"]
                    @endif
                    @endforeach
                    ]
                });

                atlTable.on('draw.dt', function() {
                    atlantisUtilities.init('atlCheckbox');
                    $('.dataTable').foundation();
                });

                $('.search-in-table[data-table-id="'+table+'"]').on('keyup', function(ev) {
                    atlTable.search($(this).val()).draw();
                });

                $('.show-count[data-table-id="'+table+'"]').on('change', function() {
                    atlTable.page.len($(this).val()).draw();
                });
            })
        }
    }
}

</script>