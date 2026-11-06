<?php

namespace Module\Lots\Models;

use Atlantis\Models\Base;
use Module\Lots\Models\Repositories\LotsRepository;

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
     
    $allLots = Lots::where("sold", "!=", "1")->get();
   
    $aLots = array();

    foreach ($allLots as $k => $lot) {

      $patterns = array(" ", ";", "@", "?", ":", "=", "&", "<", ">", '"', "#", "[", "]", "%", "{", "}", "|", "^", "~", "/", "\\", "`", "$", "'");
      $url = str_replace($patterns, "-", $lot->location) . "-" . $lot->lot_number; //. "-" . $lot->id;      

      $aLots[$k]['url'] = url('st-simons-home-sites/' .  $url);  
      $aLots[$k]['updated_at'] = $lot->updated_at;
    }
   
    //dd($aLots);
    return $aLots;
   }
}