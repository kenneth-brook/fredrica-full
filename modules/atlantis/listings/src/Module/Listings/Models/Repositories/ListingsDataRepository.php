<?php

namespace Module\Listings\Models\Repositories;

use Module\Listings\Models\Listings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ListingsDataRepository
{
    /**
     * @return mixed
     */
    public function getAllListingsList()
    {
        $model = DB::table('listings');
        return $model->get();
    }


}