<?php

namespace Module\UltimateGallery\Controllers;

/*
 * Controller: UltimateGallery
 * @Atlantis CMS
 * v 1.0
 */

use App\Http\Controllers\Controller;
use Module\UltimateGallery\Models\Repositories\UltimateGalleryRepository;

class UltimateGalleryController extends Controller
{

	use \Module\UltimateGallery\Traits\UltimateGalleryTrait;

	public function build($aParams = NULL)
	{	
		
		$aData = [];
		if (isset($aParams[0])) {

			$gallery_id = $aParams[0];
			$gallery = UltimateGalleryRepository::get($gallery_id);
			if (is_null($gallery)){
				
				return '<code>Invalid Gallery ID @ UltimateGalleryController::build</code>';
			}
		}
		else{
			return '<code>Invalid Gallery ID @ UltimateGalleryController::build</code>';
		}
		$aData['instance'] = 'u_gallery_' . rand();
		$aData['gallery'] = $gallery;
		$aData['images'] = [];

		if (isset($gallery['imgs']) && $gallery['imgs'] != '' ){
            $gallery['imgs'] = explode(',', $gallery['imgs']);
        }
        else {
        	$gallery['imgs'] = [];
        }

        $aData['images'] = array_filter(array_map(function ($id){
        	$image = \MediaTools::getImage($id);
 
        	if (!is_null($image)){

        		$image->tags = \Atlantis\Models\Repositories\TagRepository::getTagsByResourceID('media', $id)->pluck("tag")->all();
        		
        		return $image;
        	}

        }, $gallery['imgs']));


		$view = isset($gallery['view']) && !empty($gallery['view']) ? $gallery['view'] : 'default';
		return \View::make('ultimategallery::'.$view, $aData);

	}

}
