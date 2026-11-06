<?php

namespace Module\VanityUrl\Models\Repositories;

use Module\VanityUrl\Models\VanityUrl;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Atlantis\Helpers\Cache\AtlantisCache;

class VanityUrlRepository
{

    public function validationCreate($data, $cat_id = NULL)
    {

        /**
         *  Validation rules for create
         *
         * @var array
         */
        $rules_create = [
            'source_url' => 'required|unique:vanityurl,source_url,' . $cat_id,
            'dest_url' => 'required'
        ];

        $messages = [
            'required' => trans('admin::validation.required'),
            'unique' => trans('admin::validation.unique')
        ];

        $validator = Validator::make($data, $rules_create, $messages);

        return $validator;
    }

    public function add($data)
    {

        $model = VanityUrl::create($data);
        AtlantisCache::clearAll();
        return $model->id;
    }

    public function updateURL($id, $data)
    {

        $model = VanityUrl::find($id);
        if ($model != NULL) {
            $model->update($data);
            AtlantisCache::clearAll();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function get($id)
    {
        return VanityUrl::find($id);
    }

    public static function deleteURL($id)
    {

        $model = VanityUrl::find($id);
        if ($model != NULL) {
            $model->delete();
            AtlantisCache::clearAll();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function findSourceURL($url)
    {


        //return VanityUrl::select(DB::raw("dest_url, IF ('$url' REGEXP CONCAT('^', REPLACE(source_url, '.*','(.*)'),'$'),1,0) as check_value" ))
        // ->having("check_value","=",1)->limit(1)->first();

        $q = AtlantisCache::rememberQuery('vanityurlFindUrl' , [$url], function() use ($url)  {

            return DB::select(DB::raw("SELECT dest_url FROM vanityurl WHERE source_url = :url OR :url1 LIKE CONCAT(REPLACE(source_url, ':any', ''), '%') AND source_url LIKE CONCAT('%',':any') "),
                array('url' => $url, "url1" => $url)
            );
        });

        if ($q && count($q)) {
            return $q[0];
        }


    }

}
