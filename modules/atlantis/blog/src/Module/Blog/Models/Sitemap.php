<?php

namespace Module\Blog\Models;

use Atlantis\Models\Repositories\PageRepository;
use Module\Blog\Models\Repositories\BlogConfigRepository;

class Sitemap extends \Atlantis\Models\Base {

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
    
    $allBlogs = Blog::where('status', '=', 'published')
      ->whereRaw('(expire_date is NULL or expire_date > "' . \Carbon\Carbon::now() . '") and posted_date <= "' . \Carbon\Carbon::now() . '"')->get();

    

    $single_url = PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'));

    if ($single_url->url == "/")
    {
      $single_url = '/';
    } else
    {
      $single_url = '/' . $single_url->url;
    }

    $single_url = str_replace(":any", "", $single_url);

    $aRes = array();

    foreach ($allBlogs as $k => $entry) {

      $entry->url = ltrim($entry->url, '/');

      $aRes[$k]['url'] = $single_url . $entry->url;
      $aRes[$k]['updated_at'] = $entry->updated_at;
      
    }
    //dd($aRes);
    return $aRes;
  }

}
