<script>
    $(document).ready(function () {
        var atlTable{{ $table_id }} = $('#{{ $table_id }}').DataTable({
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
            pageLength: {!! $admin_items_per_page !!},
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ $url }}",
                "type": "POST",
                "data": {
                    "_token" : "{{ csrf_token() }}",
                    "namespaceClass": "{{ $namespaceClass }}",
                    @foreach($postParams as $post_key => $post_value)
                    "{{ $post_key }}": "{{ $post_value }}",
                    @endforeach
                },
                //success: function() {
                //atlantisUtilities.init("atlCheckbox");
                //}
            },
            columns: [
                    @foreach($columns as $column)
                {"data": "{{ $column['key'] }}"},
                @endforeach
            ],
            columnDefs: [
                    @foreach($columns as $k => $column)
                {className: "{{ $column['class-td'] }}", "targets": [{{ $k }}]},
                    @endforeach
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                },
                {
                    "targets": [4],
                    "visible": true,
                    "sortable": false
                },
                {
                    "targets": [2],
                    "visible": false,
                    "searchable": true
                },
            ],
            autoWidth: false,
            searching: true,
            info: false,
            order: [
                    @foreach($columns as $k => $column)
                    @if ($column['order']['sorting'] == TRUE)
                [{{ $k }}, "{{ strtolower($column['order']['order']) }}"]
                @endif
                @endforeach
            ]
        });

        atlTable{{ $table_id }}.on( 'draw.dt', function () {
            atlantisUtilities.init('atlCheckbox');
            $('.dataTable').foundation();
        });

        $('.search-in-table[data-table-id="{{ $table_id }}"]').on('keyup', function (ev) {
            atlTable{{ $table_id }}.search($(this).val()).draw();
        });

        $('.show-count[data-table-id="{{ $table_id }}"]').on( 'change', function() {
            atlTable{{ $table_id }}.page.len($(this).val()).draw();
        });

    });
</script>