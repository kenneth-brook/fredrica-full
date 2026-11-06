<?php

namespace Module\CustomFields\Models\Repositories;

use Module\CustomFields\Models\CustomFields;
use Illuminate\Support\Facades\Validator;
use Module\CustomFields\Models\CustomFieldsCategories;
use Module\CustomFields\Models\CustomFieldsValues;

class CustomFieldsRepository
{

    public function validateOnCreate($data)
    {

        $rules_create = [
            'field_label' => 'required|unique:customfields,field_label',
            'category_id' => 'required'
        ];

        $messages = [
            'required' => trans('customfields::validation.required'),
            'unique' => trans('customfields::validation.unique'),
        ];

        $validator = Validator::make($data, $rules_create, $messages);

        $validator = $this->addReplacers($validator);

        return $validator;
    }

    public function validateOnCreateCategory($data)
    {

        $rules_create = [
            'category_name' => 'required|unique:customfields_categories,category_name',
        ];

        $messages = [
            'required' => trans('customfields::validation.required'),
            'unique' => trans('customfields::validation.unique'),
        ];

        $validator = Validator::make($data, $rules_create, $messages);

        return $validator;
    }

    public function validateOnUpdate($id, $data)
    {

        $rules_create = [
            'field_label' => 'required|unique:customfields,field_label,' . $id,
            'category_id' => 'required'
        ];

        $messages = [
            'required' => trans('customfields::validation.required'),
            'unique' => trans('customfields::validation.unique'),
        ];

        $validator = Validator::make($data, $rules_create, $messages);

        $validator = $this->addReplacers($validator);

        return $validator;
    }

    public function validateOnUpdateCategory($id, $data)
    {

        $rules_create = [
            'category_name' => 'required|unique:customfields_categories,category_name,' . $id,
        ];

        $messages = [
            'required' => trans('customfields::validation.required'),
            'unique' => trans('customfields::validation.unique'),
        ];

        $validator = Validator::make($data, $rules_create, $messages);

        return $validator;
    }

    public function addReplacers(\Illuminate\Validation\Validator $validator)
    {

        $validator->addReplacer('required', function ($message, $attribute, $rule, $parameters)
        {

            if ($attribute == 'category_id')
            {
                return str_replace("category id", 'category', $message);
            }

            return $message;
        });

        return $validator;
    }


    public static function add($data)
    {
        CustomFields::create($data);
    }

    public static function addCategory($data)
    {
        CustomFieldsCategories::create($data);
    }

    public static function getAll()
    {
        return CustomFields::all();
    }


    public static function get($id)
    {
	return CustomFields::find($id);
    }

    public static function getCategory($id)
    {
        return \Atlantis\Helpers\Cache\AtlantisCache::rememberQuery('cf-getCategory',[$id], function () use ($id){
            return CustomFieldsCategories::find($id);
        });
    }

    public static function update($id, $data)
    {
        CustomFields::find($id)->update($data);

        \Atlantis\Helpers\Cache\AtlantisCache::clearAll();
    }

    public static function updateCategory($id, $data)
    {
        CustomFieldsCategories::find($id)->update($data);

        \Atlantis\Helpers\Cache\AtlantisCache::clearAll();
    }

    public static function delete($id)
    {
        CustomFieldsValues::where('field_id', $id)->delete();
        CustomFields::find($id)->delete();
        \Atlantis\Helpers\Cache\AtlantisCache::clearAll();
    }

    public static function deleteCategory($id)
    {
        CustomFields::where('category_id', $id)->update(['category_id' => null]);

        CustomFieldsCategories::find($id)->delete();
        \Atlantis\Helpers\Cache\AtlantisCache::clearAll();
    }

    public static function getAllCategories()
    {
        return \Atlantis\Helpers\Cache\AtlantisCache::rememberQuery('cf-getAllCategories',[], function (){
            return CustomFieldsCategories::all();
        });

    }

    /**
     * @param $aData
     * @param $page_id
     */
    public static function addCustomFieldsPageData($aData, $page_id, $page_version_id)
    {
        foreach ($aData as $aDatum => $val) {

            CustomFieldsValues::create([
                "page_id" => $page_id,
                "version_id" => $page_version_id,
                "field_id" => $val['customfield_id'],
                "field_value" => $val['value'],
                "lang" => $val['language']
            ]);
        }
    }


    public static function updateCustomFieldsPageData($aData, $page_id, $page_version_id)
    {
        foreach ($aData as $aDatum => $val) {

            CustomFieldsValues::where('page_id', $page_id)
                ->where('version_id', $page_version_id)
                ->update([
                        "page_id" => $page_id,
                        "version_id" => $page_version_id,
                        "field_id" => $val['customfield_id'],
                        "field_value" => $val['value'],
                        "lang" => $val['language']]
                );
        }

        \Atlantis\Helpers\Cache\AtlantisCache::clearAll();
    }

    public static function getCustomFieldsPageData($page_id, $version_id)
    {

        return \Atlantis\Helpers\Cache\AtlantisCache::rememberQuery('cf-getCustomFieldsPageData', [$page_id, $version_id], function () use ($page_id, $version_id) {

            $model = \DB::table('customfields_values');
            $model->select([
                'customfields_values.field_value',
                'customfields.field_slur',
                'customfields.field_type'
            ]);
            $model->join('customfields', 'customfields_values.field_id', '=', 'customfields.id');

            $model->where('customfields_values.page_id', $page_id);
            $model->where('customfields_values.version_id', $version_id);
            $rez = $model->get();

            $cFields = collect($rez)->keyBy('field_slur', 'field_value');

            foreach ($cFields as $k => $item) {

                if ($item->field_type == 'gallery'):
                    $gallery_id = $item->field_value;
                    $cFields[$k] = \MediaTools::getImagesByGallery($gallery_id);
                elseif ($item->field_type == 'image') :
                    $image_id = $item->field_value;
                    $cFields[$k] = \MediaTools::getImage($image_id);
                else:
                    $cFields[$k] = $item->field_value;
                endif;
            }
            return $cFields;
        });
    }


    public static function getCategoryFields($cat_id)
    {
        return \Atlantis\Helpers\Cache\AtlantisCache::rememberQuery('cf-getCategoryFields', [$cat_id], function () use ($cat_id) {
             return  CustomFields::where('category_id', $cat_id)->get();
        });
    }

    public static function getFieldsById(array $idx, $lang)
    {
        //return CustomFields::whereIn("id", $idx)->where("lang", $lang)->get();
        return CustomFields::whereHas('value', function ($query) use ($idx, $lang) {
            $query->where('lang', $lang);

        })->whereIn("id", $idx)->get();

    }

    public static function getFieldsByPageAndVersion($page_id, $version_id)
    {

        return CustomFields::whereHas('value', function($query) use($page_id,$version_id)
        {
            $query->where('page_id', $page_id);
            $query->where('version_id', $version_id);

        })->get();
    }

    public static function saveFieldValue($page_id, $version_id, $field_id, $field_value, $lang)
    {
        CustomFieldsValues::create([
            "page_id" => $page_id,
            "version_id" => $version_id,
            "field_id" => $field_id,
            "field_value" => $field_value,
            "lang" => $lang
        ]);
    }

}