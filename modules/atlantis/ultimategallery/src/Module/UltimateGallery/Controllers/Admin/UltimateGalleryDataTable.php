<?php

namespace Module\UltimateGallery\Controllers\Admin;

use Module\UltimateGallery\Models\UltimateGallery;
use Illuminate\Support\Facades\DB;

class UltimateGalleryDataTable implements \Atlantis\Helpers\Interfaces\DataTableInterface {

  public function __construct() {

    if (\Auth::check() === false) {

      return response()->json([]);
    }
  }

  public function columns() {

    return [
        [
            'title' => '<span class="fa fa-check-square-o select-all"></span>',
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
            'class-td' => 'template-class',
            'key' => 'name',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ], 
        [
            'title' => 'Description',
            'class-th' => '',
            'class-td' => 'name',
            'key' => 'description',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'View',
            'class-th' => '',
            'class-td' => 'template-class',
            'key' => 'view',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Pattern function',
            'class-th' => '',
            'class-td' => '',
            'key' => 'pattFunc',
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
        'url' => 'admin/modules/ultimategallery/bulk-action',
        'actions' => [
            [
                'name' => 'Delete',
                'key' => 'bulk_delete'
            ]
        ]
    ];
  }

  public function getData(\Illuminate\Http\Request $request) {

    $model = DB::table('ultimategallery');

    /*
     * SEARCH
     */
    if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
      $search = $request->get('search')['value'];

      $model->where('id', 'LIKE', '%' . $search . '%');
      $model->orWhere('name', 'LIKE', '%' . $search . '%');
      $model->orWhere('description', 'LIKE', '%' . $search . '%');
      $model->orWhere('view', 'LIKE', '%' . $search . '%');
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
          'checkbox' => '<span data-atl-checkbox>' . \Form::checkbox($obj->id, NULL, FALSE, ['data-id' => $obj->id]) . '</span>',
          'id' => $obj->id,
          'name' => $this->nameTd($obj),
          'view' => $obj->view,
          'pattFunc' => $this->pattFuncTd($obj),
          'description' => $obj->description,
      ];
    }

    return response()->json([
                'drow' => $request->get('draw'),
                'recordsTotal' => UltimateGallery::count(),
                'recordsFiltered' => $count,
                'data' => $data
    ]);
  }

  private function pattFuncTd($obj) {
    return htmlentities('<div data-pattern-func="module:UltimateGallery@build-'.$obj->id.'"></div>');
  }

  private function nameTd($obj) {

    return '<span class="tags hidden">tags</span>
                    <a class="item" href="'.url('/').'/admin/modules/ultimategallery/edit/' . $obj->id . '">' . $obj->name . '</a>
                    <span class="actions">
                      <a data-tooltip title="Edit Entry" href="'.url('/').'/admin/modules/ultimategallery/edit/' . $obj->id . '" class="icon icon-Edit top"></a> 
                      <a data-open="deleteEntry' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Delete Entry" class="icon icon-Delete top "></a>
                    </span>' .
            \Atlantis\Helpers\Modal::set('deleteEntry' . $obj->id, 'Delete Entry', 'Are you sure you want to delete ' . $obj->name, 'Delete', url('/').'/admin/modules/ultimategallery/delete/' . $obj->id);
  }
  
  /**
   * Add class to <table></table> tag
   * 
   */
  public function tableClass() {
    return NULL;
  }

}
