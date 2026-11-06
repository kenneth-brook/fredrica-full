<?php

namespace Module\VanityUrl\Controllers\Admin;

use Module\VanityUrl\Models\VanityUrl;
use Illuminate\Support\Facades\DB;

class VanityDataTable implements \Atlantis\Helpers\Interfaces\DataTableInterface {

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
            'title' => 'Source Url',
            'class-th' => '',
            'class-td' => 'name',
            'key' => 'source_url',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Destination Url',
            'class-th' => '',
            'class-td' => '',
            'key' => 'dest_url',
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
        'url' => 'admin/modules/vanityurl/bulk-action',
        'actions' => [
            [
                'name' => 'Delete',
                'key' => 'bulk_delete'
            ]
        ]
    ];
  }

  public function getData(\Illuminate\Http\Request $request) {

    $model = DB::table('vanityurl');

    /*
     * SEARCH
     */
    if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
      $search = $request->get('search')['value'];

      $model->where('id', 'LIKE', '%' . $search . '%');
      $model->orWhere('source_url', 'LIKE', '%' . $search . '%');
      $model->orWhere('dest_url', 'LIKE', '%' . $search . '%');
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
          'source_url' => $this->nameTd($obj),
          'dest_url' => $obj->dest_url,
          'updated_at' => $obj->updated_at
      ];
    }

    return response()->json([
                'drow' => $request->get('draw'),
                'recordsTotal' => VanityUrl::count(),
                'recordsFiltered' => $count,
                'data' => $data
    ]);
  }

  private function nameTd($obj) {

    $delIcon = '<a data-open="deleteUrl' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Delete Url" class="icon icon-Delete top "></a>';

    return '<span class="tags hidden">tags</span>
                    <a class="item" href="'.url('/').'/admin/modules/vanityurl/edit/' . $obj->id . '">' . $obj->source_url . '</a>
                    <span class="actions">
                      <a data-tooltip title="Edit User" href="'.url('/').'/admin/modules/vanityurl/edit/' . $obj->id . '" class="icon icon-Edit top"></a> ' .
            $delIcon . '</span>' .
            \Atlantis\Helpers\Modal::set('deleteUrl' . $obj->id, 'Delete User', 'Are you sure you want to delete ' . $obj->source_url . ' -> ' . $obj->dest_url , 'Delete', url('/').'/admin/modules/vanityurl/delete/' . $obj->id);
  }

  /**
   * Add class to <table></table> tag
   * 
   */
  public function tableClass() {
    return NULL;
  }

}
