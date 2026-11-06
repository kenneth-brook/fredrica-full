<?php

namespace Module\Lots\Models\Repositories;

use Module\Lots\Models\Lots;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LotsDataRepository
{
    /**
     * @return mixed
     */
    public function getAllLotsList()
    {
        $model = DB::table('lots');
        return $model->get();
    }


}