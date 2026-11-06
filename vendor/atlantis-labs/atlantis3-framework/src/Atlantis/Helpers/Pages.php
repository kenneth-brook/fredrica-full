<?php

namespace Atlantis\Helpers;

use Atlantis\Models\Repositories\PageRepository as PageRepository;

class Pages extends PageRepository
{
    public static $status = '1';
    public static $styles = null;
    public static $scripts = null;
    public static $path = null;
    public static $language = 'en';
    public static $tags = null;
    public static $cache = '1';

    /**
     * @param $data
     * @return array|mixed|void
     */
    public static function create($data)
    {
        $oPages = new Pages();
        $pageData = $oPages->validateData($data);

        if (isset($pageData['successes']) === true) {
            $iPageId = $oPages->createPage($pageData['data']);
            //On successes return Page Id
            return $iPageId;
        } else {
            if (isset($pageData['error'])) {
                return abort(404, $pageData['error']);
            } else {
                return $pageData;
            }
        }
    }

    /**
     * @param $id
     * @param $data
     */
    public static function update($id, $data)
    {
        $oPages = new Pages();
        $pageData = $oPages->validateData($data);

        if (isset($pageData['successes']) === true) {
            //IF successes update page
            $oPageRepository = new PageRepository();
            $oPageRepository->updatePage($id, $data);
        } else {
            if (isset($pageData['error'])) {
                return abort(404, $pageData['error']);
            } else {
                foreach ($pageData as $pageDatum => $val) {
                    return abort(404, $val['0']);
                }
            }
        }
    }

    /**
     * @param $id
     */
    public static function delete($id)
    {
        PageRepository::deletePage($id);
    }

    /**
     * DEPRECATED 
     * @param $iModuleId
     * @param null $iPageId
     * @param null $iPatternId
     */
    public static function savePageLog($iModuleId, $iPageId = null, $iPatternId = null)
    {
        $aPageData = array();
        $aPageData['module_id'] = $iModuleId;
        $aPageData['page_id'] = $iPageId;
        $aPageData['pattern_id'] = $iPatternId;

        $oPageRepository = new PageRepository();
        $oPageRepository->addPageLog($aPageData);
    }

    /**
     * @param $iModuleId
     * @return mixed
     */
    public static function pageExists($iModuleId)
    {
        $oPageRepository = new PageRepository();
        $data = $oPageRepository->getPageLogExists($iModuleId);
        return $data;
    }

    /**
     * @param $data
     * @return array
     */
    public function validateData($data)
    {
        $aTemplates = \Atlantis\Helpers\Tools::getTemplates();

        $data['status'] = isset($data['status']) ? $data['status'] : self::$status;
        $data['styles'] = isset($data['styles']) ? $data['styles'] : self::$styles;
        $data['scripts'] = isset($data['scripts']) ? $data['scripts'] : self::$scripts;
        $data['path'] = isset($data['path']) ? $data['path'] : self::$path;
        $data['language'] = isset($data['language']) ? $data['language'] : self::$language;
        $data['tags'] = isset($data['tags']) ? $data['tags'] : self::$tags;
        $data['cache'] = isset($data['cache']) ? $data['cache'] : self::$cache;
        $data['template'] = isset($data['template']) ? $data['template'] : $aTemplates['default'];
        $data['mobile_template'] = $aTemplates['default'];

        $oPageRepository = new PageRepository();
        $aData = array();

        if (!array_key_exists('name', $data)) {
            $aData['error'] = 'Array key name is required';
        }

        if (!array_key_exists('url', $data)) {
            $aData['error'] = 'Array key url is required';
        }

        if (!array_key_exists('template', $data)) {
            $aData['error'] = 'Array key template is required';
        }
        //is the user admin
        if (!array_key_exists('author', $data)) {
            $aData['error'] = 'Array key author name is required';
        }

        if (!array_key_exists('user', $data)) {
            $aData['error'] = 'Array key user id name is required';
        }


        if (empty($aData['error'])) {
            //Validate data
            $aValidateData = $oPageRepository->validationCreate($data);
            if (!$aValidateData->fails()) {
                //Return valid data
                $aData['successes'] = true;
                $aData['data'] = $data;
                return $aData;
            } else {
                //Validation Errors
                return $aValidateData->errors()->messages();
//                return $aValidateData;
            }
        } else {
            //Show Error
            return $aData;
        }
    }

    /**
     * @return mixed
     */
    public static function getAll()
    {
        $oPageRepository = new PageRepository();
        return $oPageRepository->getAllPages();
    }
}
