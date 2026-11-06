<?php

namespace Module\Listings\Models\Repositories;

use Module\Listings\Models\Listings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ListingsRepository {

	public function validateListings($aValidateData = array())
	{
		$rules_create = [

			'title' => 'Max:255',
			'url'  => 'Max:255',
			'type'  => 'Max:255',
			'listing_type'  => 'Max:255',
			'price'  => 'Max:255',
			'furnished'  => 'Max:255',
			'meta_desc'  => 'Max:255',
			'meta_key'  => 'Max:255',
			'heated_sq_ft'  => 'Max:255',
			'acres'  => 'Max:255',
			'bedrooms'  => 'numeric',
			'full_baths'  => 'numeric',
			'half_baths'  => 'numeric',
			'architect'  => 'Max:255',
			'builder'  => 'Max:255',
			'lot_type'  => 'Max:255',
			'video'  => 'Max:255',
			'map_category'  => 'Max:255',
			'lat'  => 'Max:255',
			'lng'  => 'Max:255',
			'home_site_number'  => 'Max:255',
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

	public function createListings($data  = array()) {

		if (isset($data['furnished'])) {
			$data['furnished'] = 1;
		} else {
			$data['furnished'] = 0;
		}
		if (isset($data['active'])) {
			$data['active'] = 1;
		} else {
			$data['active'] = 0;
		}
		Listings::create($data);
	}

	public static function getListings($iId = NULL)
	{
		$bListings = Listings::where('id', '=', $iId)->exists();
		
		if ($bListings) {

			return Listings::where('id', '=', $iId)->get();
			
		} else {
			
			return Listings::where('type', '=', 'Standard')->where('active', '=', 1)->orderBy('weight', 'ASC')->paginate(15);
			
		}
	}
	public static function getMaxWeight()
	{
		return Listings::max('weight');
	}

	public static function getAlfordLaneListings($iId = NULL)
	{
		$bListings = Listings::where('id', '=', $iId)->exists();
		
		if ($bListings) {

			return Listings::where('id', '=', $iId)->get();
			
		} else {
			
			return Listings::where('type', '=', 'Alford Lane Homes')->orderBy('weight', 'ASC');
			
		}
	}

	public function removeListings($iId)
	{
		$bListings = Listings::where('id', '=', $iId)->exists();
		if ($bListings) {
			Listings::where('id', '=', $iId)->delete();
		} else {
			return $bListings;
		}
	}

	public function updateListings($iId, $aListings = array())
	{	
		//dd($aListings);
		$model = Listings::find($iId);
		if ($model != NULL) {
			if (!isset($aListings['brochure'])) {
				$aListings['brochure'] = NULL;
			}
			if (!isset($aListings['floor_plan'])) {
				$aListings['floor_plan'] = NULL;
			}
			if (!isset($aListings['finishes'])) {
				$aListings['finishes'] = NULL;
			}
			/*if (!isset($aListings['images'])) {
				$aListings['images'] = [];
			}*/

			if (isset($aListings['furnished'])) {
				$aListings['furnished'] = 1;
			} else {
				$aListings['furnished'] = 0;
			}
			if (isset($aListings['active'])) {
				$aListings['active'] = 1;
			} else {
				$aListings['active'] = 0;
			}
			$model->update($aListings);
		}
	}

	public static  function getRandomListings($limit = 1){ 
	 return Listings::where('active', 1)->orderByRaw('RAND()')->take($limit)->get(); 
	}


}