<?php

namespace Module\CustomFields;

use Module\CustomFields\Controllers\CustomFieldsController;

class CustomFieldsBuilder implements \Atlantis\Helpers\Interfaces\AdminTabInterface
{

    public static function getTabName()
    {
        return trans("Custom Fields");
    }

    public static function getContent($mode, $page_id, $version_id)
    {
        return CustomFieldsController::getAdminTabContent($mode, $page_id, $version_id);
    }

    public static function getAllFields()
    {

    }

    /**
     * @param $aData
     * @param $page_id
     * @param $page_version_id
     */
    public static function getCustomFieldsData($aData, $page_id, $page_version_id)
    {
        CustomFieldsController::addPageCustomFieldsData($aData, $page_id, $page_version_id);
    }

    /**
     * @param $aData
     * @param $page_id
     * @param $page_version_id
     */
    public static function updateCustomFieldsData($aData, $page_id, $page_version_id)
    {
        CustomFieldsController::updatePageCustomFields($aData, $page_id, $page_version_id);
    }
    /**
     * @param $page_id
     * @param $version_id
     * @return array
     */
    public static function getPageCustomFields($page_id, $version_id)
    {
        return CustomFieldsController::getCustomFields($page_id, $version_id);
    }

}