<?php

namespace Module\UltimateGallery\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminModulesController;
use Module\UltimateGallery\Models\UltimateGallery;
use Module\UltimateGallery\Models\Repositories\UltimateGalleryRepository;
use Atlantis\Helpers\Themes\ThemeTools;
use Atlantis\Helpers\Iterator;
use Atlantis\Helpers\MediTools;
use Illuminate\Http\Request;

class UltimateGalleryAdminController extends AdminModulesController {

	public function __construct() {
		parent::__construct(\Config::get('ultimategallery.setup'));
	}

	public function getIndex() {

		$data['model'] = UltimateGallery::get();

        if (\Session::get('info') != NULL)
        {
            $data['msgInfo'] = \Session::get('info');
        }

        if (\Session::get('success') != NULL)
        {
            $data['msgSuccess'] = \Session::get('success');
        }


        if (\Session::get('error') != NULL)
        {
            $data['msgError'] = \Session::get('error');
        }
 	
		return view("ultimategallery-admin::admin/index", $data);

	}

	public function getAdd() {

		$aData['aViews'] = $this->getViews();
		if ( \Session::get('info') != NULL)
		{
			$aData['msgInfo'] = \Session::get('info');
		}

		if (\Session::get('success') != NULL)
		{
			$aData['msgSuccess'] = \Session::get('success');
		}

		if (\Session::get('error') != NULL)
		{
			$aData['msgError'] = \Session::get('error');
		}

		return view("ultimategallery-admin::admin/add", $aData);

	}

	public function postAdd(Request $request) {

		$data = $request->all();

		if (isset($data['imgs']) && is_array($data['imgs'])){
            $data['imgs'] = implode(',', $data['imgs']);
        }

		$repo = new UltimateGalleryRepository();

		$validator = $repo->validateOnCreate($data);

		
		if (!$validator->fails())
		{
			$repo::add($data);

			if ($request->get('_save_close'))
			{
				\Session::flash('success', 'Gallery was created');

				return redirect('admin/modules/ultimategallery');

			}
			elseif ($request->get('_update'))
			{
				\Session::flash('success', 'Gallery created');

				return redirect()->back();
			}
		}
		else
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}

	}

	public function getEdit(Request $request, $id) {

		$aData = UltimateGalleryRepository::get($id);
		$aData['aViews'] = $this->getViews();

		if (isset($aData['imgs']) && $aData['imgs'] != '' ){
            $aData['imgs'] = explode(',', $aData['imgs']);
        }
        else {
        	$aData['imgs'] = [];
        }


		if ( \Session::get('info') != NULL)
		{
			$aData['msgInfo'] = \Session::get('info');
		}

		if (\Session::get('success') != NULL)
		{
			$aData['msgSuccess'] = \Session::get('success');
		}

		if (\Session::get('error') != NULL)
		{
			$aData['msgError'] = \Session::get('error');
		}

		return view("ultimategallery-admin::admin/edit", $aData);

	}

	public function postEdit(Request $request, $id) {

			
		$data = $request->all();

		$repo = new UltimateGalleryRepository();

		if (isset($data['imgs']) && is_array($data['imgs'])){
            $data['imgs'] = implode(',', $data['imgs']);
        }

		$validator = $repo->validateOnCreate($data);

		
		if (!$validator->fails())
		{
			$repo::update($id , $data);

			if ($request->get('_save_close'))
			{
				\Session::flash('success', 'Gallery was updated');

				return redirect('admin/modules/ultimategallery');

			}
			elseif ($request->get('_update'))
			{
				\Session::flash('success', 'Gallery updated');

				return redirect()->back();
			}
		}
		else
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}
	}

	public function getDelete(Request $request, $id) {


		$deleted = UltimateGalleryRepository::delete($id);
     	
     	\Session::flash('success', 'Gallery '.$id.' Deleted');

      	return redirect()->back();
	}

    
     private static function getViews()
     {

     	$aT= array();

     	if (ThemeTools::haveActiveTheme()) {


     		$theme_path_views = '/' . config('atlantis.theme_path') . "/modules/ultimategallery/views/";

     		if(file_exists(base_path().$theme_path_views)){

     			$aTemp = Iterator::getFiles($theme_path_views, "WITHOUT EXT", TRUE, FALSE);

     		}   
     		if(empty($aTemp)){
     			$aTemp = Iterator::getFiles("/modules/atlantis/ultimategallery/src/Module/UltimateGallery/Views/", "WITHOUT EXT", FALSE , FALSE);
     		}
     		foreach ($aTemp as $temp) {

     			$aElem = explode("/", $temp);

     			if (!in_array("disabled", $aElem)) {

     				$stripTemp = str_replace('.blade', '', $temp);

     				$aT[$stripTemp] = $stripTemp;
     			}
     		}
     	}
     	return $aT;
     }

 }
