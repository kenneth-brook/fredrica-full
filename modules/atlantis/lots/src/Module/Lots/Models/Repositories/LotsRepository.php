<?php

namespace Module\Lots\Models\Repositories;

use Module\Lots\Models\Lots;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LotsRepository {

	public function validateLots($aValidateData = array())
	{
		$rules_create = [
			'lot_number' => 'Max:255',
			'sold' => 'Max:255',
			'location' => 'Max:255',
			'price' => 'Max:255',
			'acreage' => 'Max:255',
			'sitemap' => 'Max:255',
			'map_category' => 'Max:255',
			'lat' => 'Max:255',
			'lng' => 'Max:255',
			'view_orientation' => 'Max:255',
			'frontage' => 'Max:255',
			'lot_number_ext' => 'Max:255'
		];

		$messages = [
			'required' => trans('admin::validation.required'),
			'unique' => trans('admin::validation.unique'),
			'numeric' => trans('admin::validation.numeric'),
			'url' => trans('admin::validation.url'),
			'min' => trans('admin::validation.min'),
			'max' => trans('admin::validation.max'),
		];

		$validator = Validator::make($aValidateData, $rules_create, $messages);

		return $validator;
	}

	public function createLots($data  = array()) {

		if (isset($data['sold'])) {
			$data['sold'] = 1;
		} else {
			$data['sold'] = 0;
		}
		Lots::create($data);
	}


	public function getLotsById($id)
        {
	
             return Lots::where("id", "=", $id)->first();


        }

	public static function getLots($iId = NULL)
	{
		$bLots = Lots::where('lot_number', '=', $iId)->exists();
		
		if ($bLots) {

			return Lots::where('lot_number', '=', $iId)->get();
			
		} else {
			
			return Lots::where("sold", "!=", "1")->orderBy('lot_number')->paginate(10);
			
		}
	}

	public function removeLots($iId)
	{
		$bLots = Lots::where('id', '=', $iId)->exists();
		if ($bLots) {
			Lots::where('id', '=', $iId)->delete();
		} else {
			return $bLots;
		}
	}

	public function updateLots($iId, $aLots = array())
	{
		$model = Lots::find($iId);
		if ($model != NULL) {
			if (isset($aLots['sold'])) {
				$aLots['sold'] = 1;
			} else {
				$aLots['sold'] = 0;
			}


			if (!isset($aLots['sitemap'])) {
				$aLots['sitemap'] = '';
			}
			if (!isset($aLots['imgs'])) {
				$aLots['imgs'] = '';
			}
			$model->update($aLots);
		}
	}
	public static function getRandomLots($limit = 1){ 
	 return Lots::where("sold", "!=", "1")->orderByRaw('RAND()')->take($limit)->get(); 
	}


}
