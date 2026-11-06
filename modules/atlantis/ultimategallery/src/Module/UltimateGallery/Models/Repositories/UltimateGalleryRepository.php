<?php

namespace Module\UltimateGallery\Models\Repositories;

use Module\UltimateGallery\Models\UltimateGallery;
use Illuminate\Support\Facades\Validator;
use Atlantis\Helpers\Cache\AtlantisCache;

class UltimateGalleryRepository {

 public function validateOnCreate($data)
    {

        $rules_create = [
            'name' => 'required',
        ];

        $messages = [
            'required' => 'Name is Required'
        ];

        $validator = Validator::make($data, $rules_create, $messages);

        return $validator;
    }

    public static function add($data)
    {

    
        UltimateGallery::create($data);
    }

    public static function update($id, $data)
    {

        UltimateGallery::find($id)->update($data);
        AtlantisCache::clearAll();
    }

    public static function get($id)
    {
        return UltimateGallery::find($id);
    }

    public static function getAll()
    {
        return UltimateGallery::all();
    }

    public static function delete($id)
    {
        $deleted = UltimateGallery::find($id)->delete();
        AtlantisCache::clearAll();
        return $deleted;
    }



}