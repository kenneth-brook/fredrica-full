<?php

namespace Atlantis\Helpers\Interfaces;

interface AdminTabInterface
{
	/*
	* $mode: edit or add
	* $type: page, pattern, media, gallery
	* $id: model Id 
	* $version_id: version_id optional
	* $lang: model language optional
	*/

    public static function getContent($mode, $type, $id, $version_id, $lang);

    public static function getTabName();

    
}
