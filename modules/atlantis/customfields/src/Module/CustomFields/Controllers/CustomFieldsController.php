<?php

namespace Module\CustomFields\Controllers;

/*
 * Controller: CustomFields
 * @Atlantis CMS
 * v 1.0
 */

// TODO : Make new db field to hold field-safe version of the field
// change that into all the views
// append all the views under the custom[] in post
// finish save, finish load
// create categories and assign the fields to a category
// then make a place to assocciate category to theme template

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Module\CustomFields\Models\Repositories\CustomFieldsRepository;

class CustomFieldsController extends Controller
{

    use \Module\CustomFields\Traits\CustomFieldsTrait;

    public function postGetCategoryFields(Request $request)
    {
        $getFields = CustomFieldsRepository::getCategoryFields($request->cat_id);

        $result = '';

        foreach($getFields as $getField)
        {

            switch ($getField->field_type)
            {
                case "single_line":
                    $result .= self::makeSingleLine($getField, '', '');
                    break;
                case "multi_line":
                    $result .= self::makeMultiLine($getField, '', '');
                    break;
                case "image":
                    $result .= self::makeImageSelector($getField, '', '');
                    break;
                case "datepicker":
                    $result .= self::makeDatepicker($getField, '', '');
                    break;
                case "gallery":
                    $result .= self::makeGalleryBrowser($getField, '', '');
                    break;
            }
        }


        return response()->json(['status' => $result], 200);
    }

    public function pageCreated($id, $data)
    {
        if(isset($data['custom_hidden']) && count($data['custom_hidden']))
        {
            $fields = CustomFieldsRepository::getFieldsById($data['custom_hidden'],$data['language']);

            if(count($fields))
            {
                foreach($fields as $f)
                {
                    if($f->field_type == "single_line" && !empty($data[$f->field_slur]))
                    {
                        CustomFieldsRepository::saveFieldValue($id, 1, $f->id, $data[$f->field_slur], $data['language']);
                    }
                    if($f->field_type == "multi_line" && !empty($data[$f->field_slur]))
                    {
                        CustomFieldsRepository::saveFieldValue($id, 1, $f->id, $data[$f->field_slur], $data['language']);
                    }
                    if($f->field_type == "image" && !empty($data['imgs']))
                    {
                        CustomFieldsRepository::saveFieldValue($id, 1, $f->id, $data['imgs'][0], $data['language']);
                    }
                    if($f->field_type == "gallery" && !empty($data['gallery_id']))
                    {
                        CustomFieldsRepository::saveFieldValue($id, 1, $f->id, $data['gallery_id'], $data['language']);
                    }
                    if($f->field_type == "datepicker" && !empty($data['datepicker']))
                    {
                        CustomFieldsRepository::saveFieldValue($id, 1, $f->id, $data['datepicker'], $data['language']);
                    }
                }
            }
        }
    }

    //handle the page.edited event
    public function pageEdited($id, $data, $version_id)
    {
        if(isset($data['custom_hidden']) && count($data['custom_hidden']))
        {
            $fields = CustomFieldsRepository::getFieldsById($data['custom_hidden'],$data['language']);
            if(count($fields))
            {
                foreach($fields as $f)
                {
                    if($f->field_type == "single_line" && !empty($data[$f->field_slur]))
                    {
                        CustomFieldsRepository::saveFieldValue($id, $version_id, $f->id, $data[$f->field_slur], $data['language']);
                    }
                    if($f->field_type == "multi_line" && !empty($data[$f->field_slur]))
                    {
                        CustomFieldsRepository::saveFieldValue($id, $version_id, $f->id, $data[$f->field_slur], $data['language']);
                    }
                    if($f->field_type == "image" && !empty($data['imgs']))
                    {
                        CustomFieldsRepository::saveFieldValue($id, $version_id, $f->id, $data['imgs'][0], $data['language']);
                    }
                    if($f->field_type == "gallery" && !empty($data['gallery_id']))
                    {
                        CustomFieldsRepository::saveFieldValue($id, $version_id, $f->id, $data['gallery_id'], $data['language']);
                    }
                    if($f->field_type == "datepicker" && !empty($data['datepicker']))
                    {
                        CustomFieldsRepository::saveFieldValue($id, $version_id, $f->id, $data['datepicker'], $data['language']);
                    }
                }
            }
        }

    }

    public static function getAdminTabContent($mode, $page_id, $version_id)
    {
        if($mode == "add")
        {
            return self::addAdminTabContent($page_id, $version_id);
        }
        elseif($mode == "edit")
        {
            return self::editAdminTabContent($page_id, $version_id);
        }
    }

    /**
     * @param $aData
     * @param $page_id
     * @param $page_version_id
     */
    public static function addPageCustomFieldsData($aData, $page_id, $page_version_id)
    {
        if (is_array($aData['custom_hidden']) && !empty($aData['custom_hidden']) && !empty($aData['custom_hidden_id']) && is_array($aData['custom_hidden_id'])) {
            foreach ($aData['custom_hidden'] as $aDatum => $va) {

                foreach ($aData['custom_hidden_id'] as $aDatum_hidden => $value) {

                    if ($aDatum == $aDatum_hidden) {
                        $data[] = array(
                            'value' => $va,
                            'customfield_id' => $value,
                            'language' => $aData['language']
                        );
                    }
                }
            }
            CustomFieldsRepository::addCustomFieldsPageData($data, $page_id, $page_version_id);
        }
    }

    /**
     * @param $aData
     * @param $page_id
     * @param $page_version_id
     */
    public static function updatePageCustomFields($aData, $page_id, $page_version_id)
    {

            if (is_array($aData['custom_hidden']) && !empty($aData['custom_hidden']) && !empty($aData['custom_hidden_id']) && is_array($aData['custom_hidden_id'])) {
            foreach ($aData['custom_hidden'] as $aDatum => $va) {

                foreach ($aData['custom_hidden_id'] as $aDatum_hidden => $value) {

                    if ($aDatum == $aDatum_hidden) {
                        $data[] = array(
                            'value' => $va,
                            'customfield_id' => $value,
                            'language' => $aData['language']
                        );
                    }
                }
            }
            CustomFieldsRepository::updateCustomFieldsPageData($data, $page_id, $page_version_id);
        }
    }


    /**
     * @param $page_id
     * @param $version_id
     * @return array
     */
    public static function getCustomFields($page_id, $version_id)
    {
        $fields = CustomFieldsRepository::getCustomFieldsPageData($page_id, $version_id);
        return $fields;
    }


    public static function addAdminTabContent()
    {
        \Atlantis\Helpers\Assets::registerScript('/modules/atlantis/customfields/src/Module/CustomFields/Assets/js/customfields-admin.js', 100);

        $data = [];
        $data['all_categories'] = CustomFieldsRepository::getAllCategories();

        return view("customfields::add-page", $data)->render();
    }

    public static function editAdminTabContent($page_id, $version_id)
    {
        $all_fields = CustomFieldsRepository::getFieldsByPageAndVersion($page_id,$version_id);
        $result = '';

        foreach ($all_fields as $field)
        {
            switch ($field->field_type)
            {
                case "single_line":
                    $result .= self::makeSingleLine($field, $page_id, $version_id);
                    break;
                case "multi_line":
                    $result .= self::makeMultiLine($field, $page_id, $version_id);
                    break;
                case "image":
                    $result .= self::makeImageSelector($field, $page_id, $version_id);
                    break;
                case "gallery":
                    $result .= self::makeGalleryBrowser($field, $page_id, $version_id);
                    break;
                case "datepicker":
                    $result .= self::makeDatepicker($field, $page_id, $version_id);
                    break;
            }
        }
        return $result;
    }

    public static function makeSingleLine($field, $page_id = null, $version_id = null)
    {

        $data['field'] = $field;

        if(!is_null($page_id) && !is_null($version_id))
        {
            $data['value'] = $field->content($page_id, $version_id);
        }
        else
        {
            $data['value'] = [];
        }

        return view("customfields::single-line", $data)->render();
    }

    public static function makeMultiLine($field, $page_id = null, $version_id = null)
    {
        $data['field'] = $field;

        if(!is_null($page_id) && !is_null($version_id))
        {
            $data['value'] = $field->content($page_id, $version_id);
        }
        else
        {
            $data['value'] = [];
        }

        return view("customfields::multi-line", $data);
    }

    public static function makeImageSelector($field, $page_id = null, $version_id = null)
    {
        $data['field'] = $field;

        if(!is_null($page_id) && !is_null($version_id))
        {
            $data['value'] = $field->content($page_id, $version_id);

            if(!empty($data['value']->field_value))
            {
                $data['img_array'] = [$data['value']->field_value];
            }
            else
            {
                $data['img_array'] = [];
            }
        }
        else
        {
            $data['value'] = null;
            $data['img_array'] = null;
        }



        return view("customfields::single-image", $data)->render();
    }

    public static function makeGalleryBrowser($field, $page_id, $version_id)
    {
        $data['field'] = $field;
        $data['value'] = $field->content($page_id, $version_id);

        return view("customfields::gallery", $data)->render();
    }

    public static function makeDatepicker($field, $page_id, $version_id)
    {
        $data['field'] = $field;
        $data['value'] = $field->content($page_id, $version_id);

        return view("customfields::datepicker", $data)->render();
    }
}
