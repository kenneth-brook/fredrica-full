<?php $weight = 'weight' ?>
<script>
    $(document).ready(function () {
        /*DEFAULT TABLE OPTIONS*/

        var weight = '{{$weight}}';
        var defaultOptions{{ $table_id }} = {
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
                { targets: 'no-sort', orderable: false }
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
            ],
            drawCallback: function( ) {
                $('#{{ $table_id }} [data-atl-checkbox]').removeClass('reOrdableItem fa fa-bars');
                $('#{{ $table_id }} .select-all').show();
            }
        }; 

        /*REORDABLE TABLE OPTIONS*/
        var reordableOptions{{ $table_id }} = jQuery.extend({}, defaultOptions{{ $table_id }})         
        reordableOptions{{ $table_id }}.rowReorder = {
            update: false,
            dataSrc : weight,
            selector: 'tr'
        };

        reordableOptions{{ $table_id }}.columnDefs =  [

            @foreach($columns as $k => $column)
            {className: "{{ $column['class-td'] }}", "targets": [{{ $k }}], orderable: false},
            @if ($column['key'] == 'checkbox')
            {className: "fa fa-bars move", "targets": [{{ $k }}]},
            @endif
            @endforeach
            { targets: weight, orderable: true }
        ];

        reordableOptions{{ $table_id }}.order = [
            @foreach($columns as $k => $column)
            @if ($column['key'] == $weight)
            [{{ $k }}, 'asc']
            @endif
            @endforeach
        ];
        reordableOptions{{ $table_id }}.drawCallback = function( ) {
            $('#{{ $table_id }} [data-atl-checkbox]').addClass('reOrdableItem fa fa-bars');
            $('#{{ $table_id }} .select-all').hide();
        }
        reordableOptions{{ $table_id }}.columns = [
            @foreach($columns as $column)
            {"data": "{{ $column['key'] }}"},
            @endforeach
        ];
        
        

        /*INIT TABLE*/
        var atlTable{{ $table_id }} = $('#{{ $table_id }}').DataTable(defaultOptions{{ $table_id }});

        /*EVENTS*/
        atlTable{{ $table_id }}.on( 'draw.dt', function () {
            atlantisUtilities.atlCheckbox()
            $('.dataTable').foundation();  
        });
        $('.search-in-table[data-table-id="{{ $table_id }}"]').on('keyup', function (ev) {
            atlTable{{ $table_id }}.search($(this).val()).draw();
        });

        $('.show-count[data-table-id="{{ $table_id }}"]').on( 'change', function() {
            atlTable{{ $table_id }}.page.len($(this).val()).draw();
        });


        $('#switchReorder-{{ $table_id }}').on('change', function () {

            atlTable{{ $table_id }}.destroy();
            var data = {
                _token : '{{ csrf_token() }}',
                namespaceClass : '{{ $namespaceClass }}',
                newWeightValues : []
            }

            if($(this).is(':checked')) {
                atlTable{{ $table_id }} = $('#{{ $table_id }}').DataTable(reordableOptions{{ $table_id }});

                atlTable{{ $table_id }}.off('row-reorder');

                atlTable{{ $table_id }}.on('row-reorder', function ( e, diff, edit ) {
                    data.newWeightValues = [];
                    console.log(data.newWeightValues);
                    for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                        data.newWeightValues[i] = {
                            itemId : diff[i].node.id,
                            oldWeight : diff[i].oldData,
                            newWeight : diff[i].newData
                        }
                    }
                    $.ajax({
                        url:"{{ url('/') }}/datatable-proccessing/reorder-data",
                        data:data,
                        type: 'post',
                        success : function (data) {
                            
                            atlTable{{ $table_id }}.clear().draw('page');
                        }
                    })

                });
                $('.bulk-action[data-table-id="{{ $table_id }}"] select.bulk').attr('disabled', 'disabled');
                $('.search-in-table[data-table-id="{{ $table_id }}"]').attr('disabled', 'disabled');
            }
            else {
                atlTable{{ $table_id }} = $('#{{ $table_id }}').DataTable(defaultOptions{{ $table_id }});
                $('.bulk-action[data-table-id="{{ $table_id }}"] select.bulk').removeAttr('disabled');
                $('.search-in-table[data-table-id="{{ $table_id }}"]').removeAttr('disabled');
            }
        })
    });
/*$('#{{ $table_id }}').on('draw.dt', function () {
  
  
           if ($('#{{ $table_id }}').data('tabs')) {
            console.log('order');
               var rows = table.rows().data();
               var ord = new Array();
               for (var i = 0, ien = rows.length; i < ien; i++) {
                   ord[i] = rows[i].DT_RowId;
               }
               
               post_order(ord, $('#{{ $table_id }}').data('tabs'));
           }
         });*/
</script>