<?php

namespace Module\Listings\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListingsDataTable extends \Atlantis\Helpers\DataTable\DataTableReorder
{

    public function __construct()
    {
        if (\Auth::check() === false) {
            return response()->json([]);
        }
    }

    public function columns()
    {
        return [
            [
                'title' => '<span class="fa fa-check-square-o"></span>',
                'class-th' => 'checkbox no-sort',
                'class-td' => 'checkbox',
                'key' => 'checkbox',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            [
                'title' => 'ID',
                'class-th' => '', // class for <th>
                'class-td' => 'id', // class for <td>
                'key' => 'id', // db column name
                'order' => [
                    'sorting' => FALSE, // only one column have TRUE
                    'order' => 'desc'
                ]
            ],            
            [
                'title' => 'Title',
                'class-th' => '', // class for <th>
                'class-td' => 'name', // class for <td>
                'key' => 'title', // db column name
                'order' => [
                    'sorting' => FALSE, // only one column have TRUE
                    'order' => 'desc'
                ]
            ],
            [
                'title' => 'Type',
                'class-th' => 'template-class',
                'class-td' => 'name',
                'key' => 'type',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
             [
                'title' => 'Price',
                'class-th' => 'template-class',
                'class-td' => 'name',
                'key' => 'updated_at',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
             [
                'title' => 'Weight',
                'class-th' => '',
                'class-td' => 'template-class',
                'key' => 'weight',
                'order' => [
                    'sorting' => TRUE,
                    'order' => 'asc'
                ]
            ]
        ];
    }

    /**
     * Fill array or return empty.
     *
     * @return array
     */
    public function bulkActions()
    {
        return [
            'url' => 'admin/modules/listings/bulk-action',
            'actions' => [
                [
                    'name' => 'Delete',
                    'key' => 'bulk_delete'
                ]
            ]
        ];
    }
    
    public function rowsReorder(Request $request) {

        foreach ($request->get('newWeightValues') as $new) {

            $newWeight = $new['newWeight'];

            $model = DB::table('listings')->where('id', '=', intval($new['itemId']));
            $model->update(['weight' => intval($newWeight)]);
        }

        return response()->json(['success' => $model]);
    }

    public function getData(Request $request)
    {
        $model = DB::table('listings');
        /*
         * SEARCH
         */
        if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
            $search = $request->get('search')['value'];

            $model->where('id', 'LIKE', '%' . $search . '%');
            $model->orWhere('title', 'LIKE', '%' . $search . '%');
            $model->orWhere('description', 'LIKE', '%' . $search . '%');
            $model->orWhere('price', 'LIKE', '%' . $search . '%');
            $model->orWhere('details', 'LIKE', '%' . $search . '%');
            $model->orWhere('architect', 'LIKE', '%' . $search . '%');
            $model->orWhere('type', 'LIKE', '%' . $search . '%');
            $model->orWhere('builder', 'LIKE', '%' . $search . '%');
        }

        /*
         * Count filtered data without LIMIT and OFFSET
         */
        $modelWhitoutOffset = $model;
        $count = $modelWhitoutOffset->count();

        /*
         * OFFSET and LIMIT
         */
        $model->take($request->get('length'));
        $model->skip($request->get('start'));

        /*
         * ORDER BY
         */
        if (isset($request->get('order')[0]['column']) && isset($request->get('order')[0]['dir'])) {

            $column = $request->get('order')[0]['column'];
            $dir = $request->get('order')[0]['dir'];
            $columns = $request->get('columns');

            $model->orderBy($columns[$column]['data'], $dir);
        }

        /*
         * Get filtered data
         */
        $modelWithOffset = $model->get();

        $data = array();

        foreach ($modelWithOffset as $k => $obj) {

            $data[$k] = [
                'DT_RowId' => $obj->id,
                'checkbox' => '<span data-atl-checkbox>' . \Form::checkbox($obj->id, NULL, FALSE, ['data-id' => $obj->id]) . '</span>',
                'id' => $obj->id,
                'title' => $this->nameTd($obj),
                'type' => $obj->type,
                'updated_at' => $obj->price,
                'weight' => $obj->weight
            ];
        }

        return response()->json([
            'drow' => $request->get('draw'),
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data
        ]);
    }

    private function nameTd($obj)
    {

        return '<a class="item" href="/admin/modules/listings/edit-listings/' . $obj->id . '">' . $obj->title . '</a>
                    <span class="actions">
                      <a data-tooltip title="Edit Listings" href="/admin/modules/listings/edit-listings/' . $obj->id . '" class="icon icon-Edit top"></a> 
                      <a data-open="deleteMenu' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Delete Listings" class="icon icon-Delete top "></a>
                    </span>' .
            \Atlantis\Helpers\Modal::set('deleteMenu' . $obj->id, 'Delete Listings', 'Are you sure you want to delete Listing #' . $obj->title, 'Delete', '/admin/modules/listings/remove-listings/' . $obj->id);
    }

    /**
     * Add class to <table></table> tag
     *
     */
    public function tableClass()
    {
        return NULL;
    }
}
