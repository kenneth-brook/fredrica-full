<?php

namespace Atlantis\Helpers\DataTable;

interface DataTableReorderInterface {

  public function tableClass();

  public function columns();

  public function bulkActions();

  public function getData(\Illuminate\Http\Request $request);
  
  public function rowsReorder(\Illuminate\Http\Request $request);
}
