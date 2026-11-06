<?php

namespace Module\Forms\Controllers\Admin;

use Module\Forms\Models\Forms;
use Illuminate\Support\Facades\DB;

class FormsDataTable implements \Atlantis\Helpers\Interfaces\DataTableInterface {

    public function __construct() {

        if (\Auth::check() === false) {

            return response()->json([]);
        }
    }

    public function columns() {

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
                    'sorting' => TRUE, // only one column have TRUE
                    'order' => 'desc'
                ]
            ],
                [
                'title' => 'Name',
                'class-th' => '',
                'class-td' => 'name',
                'key' => 'name',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
                [
                'title' => 'Pattern function',
                'class-th' => '',
                'class-td' => 'func',
                'key' => 'func',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ],
                [
                'title' => 'Updated at',
                'class-th' => '',
                'class-td' => 'template-class',
                'key' => 'updated_at',
                'order' => [
                    'sorting' => FALSE,
                    'order' => 'ASC'
                ]
            ]
        ];
    }

    /**
     * Fill array or return empty.
     * 
     * @return array
     */
    public function bulkActions() {

        return [
            'url' => 'admin/modules/forms/bulk-action',
            'actions' => [
                    [
                    'name' => 'Delete',
                    'key' => 'bulk_delete'
                ]
            ]
        ];
    }

    public function getData(\Illuminate\Http\Request $request) {

        $model = DB::table('forms');

        /*
         * SEARCH
         */
        if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
            $search = $request->get('search')['value'];

            $model->where('id', 'LIKE', '%' . $search . '%');
            $model->orWhere('name', 'LIKE', '%' . $search . '%');
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

            if ($columns[$column]['data'] == 'func') {
                $model->orderBy('id', $dir);
            } else {
                $model->orderBy($columns[$column]['data'], $dir);
            }
        }

        /*
         * Get filtered data
         */
        $modelWithOffset = $model->get();

        $data = array();

        foreach ($modelWithOffset as $k => $obj) {

            $data[$k] = [
                'checkbox' => '<span data-atl-checkbox>' . \Form::checkbox($obj->id, NULL, FALSE, ['data-id' => $obj->id]) . '</span>',
                'id' => $obj->id,
                'name' => $this->nameTd($obj),
                'func' => htmlentities('<div data-pattern-func="module:forms@build-' . $obj->id . '"></div>'),
                'updated_at' => $obj->updated_at
            ];
        }

        return response()->json([
                    'drow' => $request->get('draw'),
                    'recordsTotal' => Forms::count(),
                    'recordsFiltered' => $count,
                    'data' => $data
        ]);
    }

    private function nameTd($obj) {

         $cloneModal = view("forms-admin::admin.modals.clone")->with("obj", $obj)->render();
         
        return '<span class="tags hidden">tags</span>
                    <a class="item" href="'.url('/').'/admin/modules/forms/edit/' . $obj->id . '">' . $obj->name . '</a>
                    <span class="actions">
                    <a data-tooltip title="Export Form" href="'.url('/').'/admin/modules/forms/export/' . $obj->id . '" class="icon icon-Export top"></a> 
                      <a data-tooltip title="Edit Form" href="'.url('/').'/admin/modules/forms/edit/' . $obj->id . '" class="icon icon-Edit top"></a> 
                      <a data-open="deleteForm' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Delete Form" class="icon icon-Delete top "></a>
<a data-open="cloneForm'.$obj->id.'" data-tooltip title="Clone Form" data-clone-form="' . $obj->id . '" class="icon icon-Files top" aria-controls="cloneForm' . $obj->id . '"  tabindex=""></a>                   
</span>' .
                \Atlantis\Helpers\Modal::set('deleteForm' . $obj->id, 'Delete Form', 'Are you sure you want to delete ' . $obj->name, 'Delete', ''.url('/').'/admin/modules/forms/delete/' . $obj->id) .
               $cloneModal;
          }

    /**
     * Add class to <table></table> tag
     * 
     */
    public function tableClass() {
        return NULL;
    }

}
