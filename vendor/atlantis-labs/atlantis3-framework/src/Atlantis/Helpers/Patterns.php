<?php

namespace Atlantis\Helpers;

use Atlantis\Models\Repositories\PatternRepository as PatternRepository;

class Patterns extends PatternRepository
{

    public static $language = 'en';
    public static $tags = '';
    public static $status = 1;
    public static $weight = 1;
    public static $outputs = 'none';

    /**
     * @param $aData
     * @return array|mixed
     */
    public static function create($aData)
    {

        $aData['language'] = isset($aData['language']) ? $aData['language'] : self::$language;
        $aData['tags'] = isset($aData['tags']) ? $aData['tags'] : self::$tags;
        $aData['status'] = isset($aData['status']) ? $aData['status'] : self::$status;
        $aData['weight'] = isset($aData['weight']) ? $aData['weight'] : self::$weight;
        $aData['outputs'] = isset($aData['outputs']) ? $aData['outputs'] : self::$outputs;

        $data = self::validateData($aData);

        if (isset($data['successes']) === true) {
            $oPatternRepository = new PatternRepository();
            $patternId = $oPatternRepository->createPattern($data['data']);
            return $patternId;
        } else {
            return $data;
        }
    }

    /**
     * @param $data
     * @return array
     */
    public static function validateData($data)
    {
        $aData = array();

        $oPatternRepository = new PatternRepository();

        $validator = $oPatternRepository->validationCreate($data);

        if (!$validator->fails()) {
            $aData['successes'] = true;
            $aData['data'] = $data;
            return $aData;
        } else {
            return $validator->errors()->messages();
        }
    }

    /**
     * @param $id
     * @param $data
     * @return array
     */
    public static function update($id, $data)
    {
        $data = self::validateData($data);
        if (isset($data['successes']) === true) {
            $oPatternRepository = new PatternRepository();
            $oPatternRepository->updatePattern($id,$data['data']);
        } else {
            return $data;
        }
    }

    /**
     * @param $id
     */
    public static function delete($id)
    {
        PatternRepository::deletePattern($id);
    }
}
