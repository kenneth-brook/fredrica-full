<?php

namespace Atlantis\Helpers\DataTable;

/**
 * 
 *
 * @author gellezzz
 */
class DataTableReorder implements \Atlantis\Helpers\DataTable\DataTableReorderInterface {

  public function bulkActions() {
    return array();
  }

  public function columns() {
    return array();
  }

  public function getData(\Illuminate\Http\Request $request) {

    return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
    ]);
  }

  /**
   * Add class to <table></table> tag
   *
   */
  public function tableClass() {
    return NULL;
  }

  public function rowsReorder(\Illuminate\Http\Request $request) {
    return response()->json(['success' => 'OK', 'request' => $request->all()]);
  }

}
