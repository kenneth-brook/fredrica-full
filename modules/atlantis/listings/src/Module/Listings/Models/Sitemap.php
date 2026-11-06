<?php

namespace Module\Listings\Models;

use Atlantis\Models\Base;
//use Atlantis\Models\Repositories\PageRepository;
use Module\Listings\Models\Repositories\ListingsRepository;

class Sitemap extends Base {

  public static function get() {
     
     /**
     * DO all operations here , need to return an array with urls and updated dates for all public views
     * 
     *  return [
                 [
                  'url' => 'test/url',
                  'updated_at' => '2016-08-16 15:20:30'
                 ],
                 [
                  'url' => 'test/url1',
                  'updated_at' => '2016-08-16 15:20:58'
                 ]
                ];
     */
   
    $allListings = Listings::where('active', 1)->get();
    
    $aListings = array();

    foreach ($allListings as $k => $entry) {      

      $entry->url = ltrim($entry->url, '/');

      $aListings[$k]['url'] = url('st-simons-homes-for-sale/' . $entry->url);  
      $aListings[$k]['updated_at'] = $entry->updated_at;
    }

    //dd($aListings);

    return $aListings;
   }
}