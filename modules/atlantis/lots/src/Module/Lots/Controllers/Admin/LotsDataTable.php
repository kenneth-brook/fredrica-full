<?php

namespace Module\Lots\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LotsDataTable implements \Atlantis\Helpers\Interfaces\DataTableInterface
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
                'title' => '#',
                'class-th' => '', // class for <th>
                'class-td' => 'id', // class for <td>
                'key' => 'lot_number', // db column name
                'order' => [
                    'sorting' => TRUE, // only one column have TRUE
                    'order' => 'asc'
                ]
            ],
            [
                'title' => 'Location',
                'class-th' => 'template-class',
                'class-td' => 'name',
                'key' => 'location',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            [
                'title' => 'Price',
                'class-th' => '',
                'class-td' => 'template-class',
                'key' => 'price',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            [
                'title' => 'Gross Acres',
                'class-th' => '',
                'class-td' => 'template-class',
                'key' => 'acreage',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            [
                'title' => 'Sitemap PDF',
                'class-th' => '',
                'class-td' => 'template-class',
                'key' => 'sitemap',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
            // [
            //     'title' => 'Order',
            //     'class-th' => '',
            //     'class-td' => 'id',
            //     'key' => 'weight',
            //     'order' => [
            //         'sorting' => True,
            //         'order' => 'ASC'
            //     ]
            // ]
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
            'url' => 'admin/modules/lots/bulk-action',
            'actions' => [
                [
                    'name' => 'Delete',
                    'key' => 'bulk_delete'
                ]
            ]
        ];
    }

    public function getData(Request $request)
    {
        $model = DB::table('lots');
        /*
         * SEARCH
         */
        if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
            $search = $request->get('search')['value'];

            $model->where('id', 'LIKE', '%' . $search . '%');
            $model->orWhere('location', 'LIKE', '%' . $search . '%');
            $model->orWhere('acreage', 'LIKE', '%' . $search . '%');
            $model->orWhere('price', 'LIKE', '%' . $search . '%');
            $model->orWhere('sitemap', 'LIKE', '%' . $search . '%');
            $model->orWhere('description', 'LIKE', '%' . $search . '%');
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

            $sold = $obj->sold ? '[SOLD]' : '';

            $data[$k] = [
                'checkbox' => '<span data-atl-checkbox>' . \Form::checkbox($obj->id, NULL, FALSE, ['data-id' => $obj->id]) . '</span>',
                'id' => $obj->id,
                'lot_number' => $obj->lot_number,
                'location' => $this->nameTd($obj),
                'price' => $obj->price.' '.$sold,
                'acreage' => $obj->acreage,
                'sitemap' => $obj->sitemap ? '<a target="_blank" href="/admin/media/media-edit/'.$obj->sitemap.'">View/edit sitemap</a>' : ''
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

        return '<span class="tags hidden">tags</span>
                    <a class="item" href="/admin/modules/lots/edit-lots/' . $obj->id . '">' . $obj->location . '</a>
                    <span class="actions">
                      <a data-tooltip title="Edit Lots" href="/admin/modules/lots/edit-lots/' . $obj->id . '" class="icon icon-Edit top"></a> 
                      <a data-open="deleteMenu' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Delete Lots" class="icon icon-Delete top "></a>
                    </span>' .
            \Atlantis\Helpers\Modal::set('deleteMenu' . $obj->id, 'Delete Lots', 'Are you sure you want to delete Lot #' . $obj->lot_number, 'Delete', '/admin/modules/lots/remove-lots/' . $obj->id);
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
