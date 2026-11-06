<?php

namespace Module\Blog\Controllers\Admin;

use Module\Blog\Models\Blog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EntriesDataTable implements \Atlantis\Helpers\Interfaces\DataTableInterface {

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
            'title' => 'Title',
            'class-th' => '',
            'class-td' => 'name',
            'key' => 'title',
            'order' => [
                'sorting' => FALSE,
                'order' => 'ASC'
            ]
        ],
        [
            'title' => 'Status',
            'class-th' => '',
            'class-td' => 'status',
            'key' => 'status',
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
        'url' => 'admin/modules/blog/bulk-action',
        'actions' => [
            [
                'name' => 'Delete',
                'key' => 'bulk_delete'
            ]
        ]
    ];
  }

  public function getData(\Illuminate\Http\Request $request) {

    $model = DB::table('blog');

    /*
     * SEARCH
     */
    if (isset($request->get('search')['value']) && !empty($request->get('search')['value'])) {
      $search = $request->get('search')['value'];

      $model->where('id', 'LIKE', '%' . $search . '%');
      $model->orWhere('title', 'LIKE', '%' . $search . '%');
      $model->orWhere('status', 'LIKE', '%' . $search . '%');
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
          'title' => $this->nameTd($obj),
          'status' => $this->statusTd($obj),
          'updated_at' => $obj->updated_at
      ];
    }

    return response()->json([
                'drow' => $request->get('draw'),
                'recordsTotal' => Blog::count(),
                'recordsFiltered' => $count,
                'data' => $data
    ]);
  }



  private function statusTd($obj) {

    $status =  $obj->status;

    if( $obj->status == 'published')
    {
      $status = '<span data-status="active">published</span>';
    }

    if( $obj->status == 'draft')
    {
      $status = '<span data-status="dev">draft</span>';
    }

    if(!is_null($obj->expire_date) && $obj->expire_date <= Carbon::now())
    {
      $status = '<span data-status="disabled">expired</span>';
    }

    if( isset($obj->posted_date) && $obj->posted_date > Carbon::now())
    {
      $status = '&nbsp;&nbsp;&nbsp;<small class="fa fa-clock-o primary"></small> scheduled post for ' . $obj->posted_date;
    }

    return $status;

  }
  
  private function nameTd($obj) {

    return '<span class="tags hidden">tags</span>
                    <a class="item" href="'.url('/').'/admin/modules/blog/edit/' . $obj->id . '">' . $obj->title . '</a>
                    <span class="actions">
                      <a data-tooltip title="Edit Entry" href="'.url('/').'/admin/modules/blog/edit/' . $obj->id . '" class="icon icon-Edit top"></a> 
                      <a data-open="deleteEntry' . $obj->id . '" data-tooltip aria-haspopup="true" data-disable-hover="false" tabindex="1" title="Delete Entry" class="icon icon-Delete top "></a>
                    </span>' .
            \Atlantis\Helpers\Modal::set('deleteEntry' . $obj->id, 'Delete Entry', 'Are you sure you want to delete ' . $obj->title, 'Delete', url('/').'/admin/modules/blog/delete/' . $obj->id);
  }
  
  /**
   * Add class to <table></table> tag
   * 
   */
  public function tableClass() {
    return NULL;
  }

}
