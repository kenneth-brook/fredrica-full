<?php

namespace Module\Site\Controllers\Modules;

use App\Http\Controllers\Controller;
use Module\Blog\Models\Blog;
use Module\Blog\Models\Repositories\BlogConfigRepository;
use Atlantis\Models\Repositories\PageRepository;
use Atlantis\Models\Tag;

/**
 * TestModuleController
 * exemplary front-end controller
 * This controller is used to overrides module controller
 */
class BlogController extends Controller {

  /**
   * overrides function from: \Module\TestModule\Controllers\TestModuleController
   * @param type $aParams
   * @return type
   */
  public static function getAllPublishedDESC()
  {
    $oBlogs = Blog::where('status', '=', 'published')->orderBy('posted_date', 'DECS')->paginate(BlogConfigRepository::getConfigKey('per_page'));

    $featuredImages = \MediaTools::getFeaturedImages($oBlogs);

    $aParams = array();
    $aParams['oBlogs'] = $oBlogs;
    $aParams['featuredImages'] = $featuredImages;

    $single_url = PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'));

    if ($single_url->url == "/")
    {
      $aParams['single_url'] = '/';
    } else
    {
      $aParams['single_url'] = '/' . $single_url->url;
    }

    $aParams['single_url'] = str_replace(":any", "",$aParams['single_url']);

    $aParams['datetime_format'] = BlogConfigRepository::getConfigKey('datetime_format');

        //$aParams['paginator'] = '';

    return view('blog::blog-list', $aParams);
  }

  public static function getAllPublishedByTag($params)
  {

    $tag = $params && is_array($params) &&
    array_key_exists(0, $params) && array_key_exists(0, $params[0]) ? $params[0][0] : '';

    $itemsInCategory = Tag::where("resource", "=", "blog")->where("tag", "=", $tag)->orderBy("updated_at", "DESC")->get();
    $blogIdsInCategory = array();

    foreach ($itemsInCategory as $itemInCategory) {

      array_push($blogIdsInCategory, $itemInCategory['resource_id']);
    }



    $oBlogs = Blog::where('status', '=', 'published')->whereIn("id", $blogIdsInCategory)->orderBy('posted_date', 'DECS')->paginate(BlogConfigRepository::getConfigKey('per_page'));

    $featuredImages = \MediaTools::getFeaturedImages($oBlogs);

    $aParams = array();
    $aParams['oBlogs'] = $oBlogs;
    $aParams['featuredImages'] = $featuredImages;

    $single_url = PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'));

    if ($single_url->url == "/")
    {
      $aParams['single_url'] = '/';
    } else
    {
      $aParams['single_url'] = '/' . $single_url->url;
    }

    $aParams['single_url'] = str_replace(":any", "",$aParams['single_url']);

    $aParams['datetime_format'] = BlogConfigRepository::getConfigKey('datetime_format');

        //$aParams['paginator'] = '';

    return view('blog::blog-list', $aParams);
  }

   public static function getArchive($params)
  {

    $count = $params && is_array($params) &&
    array_key_exists(0, $params) && array_key_exists(0, $params[0]) ? $params[0][0] : 20;

    $oBlogs = Blog::where('status', '=', 'published')->orderBy('posted_date', 'DECS')->take($count)->get();
    
    $aParams['oBlogs'] = $oBlogs;

    return view('blog::archive', $aParams);
  }

}
