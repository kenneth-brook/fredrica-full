<?php

namespace Module\Blog\Controllers;

use App\Http\Controllers\Controller;
use Atlantis\Models\Repositories\PageRepository;
use Illuminate\Support\Facades\DB;
use Module\Blog\Models\Blog;
use Module\Blog\Models\Repositories\BlogConfigRepository;
use Module\Blog\Models\Repositories\BlogRepository;

class BlogController extends Controller {

	public static $title;
	public static $description;

	use \Module\Blog\Traits\BlogTrait;

	private static function anchorUrl() {
		if (!intval(BlogConfigRepository::getConfigKey('anchor_url'))) {
			return view('blog::config-alert', ['error' => 'anchor_url']);
		}

		$anchor_url = PageRepository::getPage(BlogConfigRepository::getConfigKey('anchor_url'))->url;

		return $anchor_url;
	}
	/**
	 * <div data-pattern-func="module:blog@single"></div>
	 */
	public function single() {

		$base_route = \Route::input('page');

		//this is dumb and needs to be replaced .. maybe change the config to have the anchor url be not connected to an actual page
		if (!intval(BlogConfigRepository::getConfigKey('single_url'))) {
			return view('blog::config-alert', ['error' => 'single_url']);
		}

		$page = str_replace("/:any", "", PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'))->url);

		$entry = Blog::where(DB::raw('CONCAT("' . $page . '/", url)'), $base_route)->first();

		if (is_null($entry)){

			return app('AtlantisRedirect')->set(redirect()->to('404?url=' . $base_route));

		}

		if ($entry->status == 'draft' && is_null(auth()->user())) {

			return app('AtlantisRedirect')->set(redirect()->to('404'));

		}
		
		if ($entry) {

			$data = array();

			$data['data'] = $entry;

			$data['anchor_url'] = self::anchorUrl();

			$data['comment_engine'] = BlogConfigRepository::getConfigKey('comment_engine');
			if ($entry->seo_title == '') {
				self::$title = $entry->title;
			} else {
				self::$title = $entry->seo_title;
			}
			self::$description = $entry->meta_description;

			$images = \MediaTools::getImagesByGallery($entry->gallery_id);
			$data['images'] = $images;

			$ogData = [
				'type' => 'article',
				'title' => $entry->title,
				'description' => $entry->description,
				'url' => url(BlogConfigRepository::getConfigKey('single_url') . '/' . $entry->url),
				'author' => $entry->nickname,
			];

			if (isset($images[0])) {
				$ogData['image'] = url($images[0]->original_filename);
			}

			\Atlantis\Helpers\Assets::registerContentWithKey('opengraph', $ogData);

			return view('blog::blog-single', $data);

		} else {

			return app('AtlantisRedirect')->set(redirect()->to('404'));
		}
	}

	/**
	 * <div data-pattern-func="module:blog@all"></div>
	 */
	public function all() {
		$oBlogs = BlogRepository::getAllPublished();

		$featuredImages = \MediaTools::getFeaturedImages($oBlogs);

		$aParams = array();
		$aParams['oBlogs'] = $oBlogs;
		$aParams['featuredImages'] = $featuredImages;
		if (!intval(BlogConfigRepository::getConfigKey('single_url'))) {
			return view('blog::config-alert', ['error' => 'single_url']);
		}

		$single_url = PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'));

		if ($single_url->url == "/") {
			$aParams['single_url'] = '/';
		} else {
			$aParams['single_url'] = '/' . $single_url->url;
		}

		$aParams['single_url'] = str_replace(":any", "", $aParams['single_url']);

		$aParams['datetime_format'] = BlogConfigRepository::getConfigKey('datetime_format');

		$aParams['anchor_url'] = self::anchorUrl();

		return view('blog::blog-list', $aParams);
	}

	/**
	 *   Show prev next blog post links
	 * <div data-pattern-func="module:blog@quicklinks"></div>
	 */
	public function quicklinks() {

		$base_route = \Route::input('page');

		if (!intval(BlogConfigRepository::getConfigKey('single_url'))) {
			return view('blog::config-alert', ['error' => 'single_url']);
		}

		$page = str_replace("/:any", "", PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'))->url);

		$entry = DB::table('blog')->where(DB::raw('CONCAT("' . $page . '/", url)'), $base_route)->first();

		$model = $entry;

		$data = [];

		$data['single_url'] = $page;

		if (count($model) && $model->posted_date) {
			$data['next'] = \DB::table('blog')->select('*')->where('posted_date', '>', $model->posted_date)->where('status', 'published')->orderBy('posted_date')->limit(1)->first();
			$data['prev'] = \DB::table('blog')->select('*')->where('posted_date', '<', $model->posted_date)->where('status', 'published')->orderBy('posted_date', 'DESC')->limit(1)->first();

			return view('blog::quick-links', $data);
		}

	}

	/*
       <div data-pattern-func="module:blog@recentshort-X"></div>
    */

	public function getRecentShort($params) {
		$amount = isset($params[0]) ? $params[0] : 3;
		return BlogRepository::getRecentShort($amount);

	}

	/*<div data-pattern-func="module:blog@archivePosts"></div>*/
	public function archivePosts() {
		$request = request();
		$fullUrl = $request->fullUrl();
		$fullUrlItems = explode("/", $fullUrl);
		if (count($fullUrlItems) >= 3) {
			$month = $fullUrlItems[count($fullUrlItems) - 1];
			if (!is_numeric($month) || (int) $month < 1 || (int) $month > 12) {
				$month = null;
			}
			$year = $fullUrlItems[count($fullUrlItems) - 2];
			if (!is_numeric($year) || (int) $year < 1 || (int) $year > 5000) {
				$year = null;
			}
		}

		$oBlogs = BlogRepository::getAllArchive($month, $year);

		$aParams = array();

		$aParams['oBlogs'] = $oBlogs;

		if (!intval(BlogConfigRepository::getConfigKey('single_url'))) {
			return view('blog::config-alert', ['error' => 'single_url']);
		}

		$single_url = PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'));

		if ($single_url->url == "/") {
			$aParams['single_url'] = '/';
		} else {
			$aParams['single_url'] = '/' . $single_url->url;
		}
		$aParams['month'] = $month;
		$aParams['year'] = $year;

		$aParams['single_url'] = str_replace(":any", "", $aParams['single_url']);

		$aParams['anchor_url'] = self::anchorUrl();

		$aParams['datetime_format'] = BlogConfigRepository::getConfigKey('datetime_format');

		return view('blog::archives-list', $aParams);

	}

	/**
	 * <div data-pattern-func="module:blog@archiveDatesList"></div>
	 * show list of available dates
	 */
	public function archiveDatesList() {
		$archiveAvailableDates = BlogRepository::getMakeArchivesList();

		$dates = array();
		$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		foreach ($archiveAvailableDates as $archiveAvailableDate) {
			if (!$archiveAvailableDate->month) {
				continue;
			}
			$dates[$archiveAvailableDate->year][$archiveAvailableDate->month] = $months[$archiveAvailableDate->month - 1];
		}

		if (!intval(BlogConfigRepository::getConfigKey('archives_url'))) {
			return view('blog::config-alert', ['error' => 'archives_url']);
		}
		$archive_url = PageRepository::getPage(BlogConfigRepository::getConfigKey('archives_url'));
		//dd($archive_url);

		if (is_null($archive_url) || $archive_url->url == "/") {
			$data['archive_url'] = '/';
		} else {
			$data['archive_url'] = '/' . $archive_url->url;
		}

		$data['archive_url'] = str_replace(":any", "", $data['archive_url']);

		$data['list'] = $dates;

		return view('blog::archives-dates-list', $data);

	}

	/*<div data-pattern-func="module:blog@showPostsByCategory"></div>*/
	/*<div data-pattern-func="module:blog@showPostsByCategory-1,3"></div>*/
	public function showPostsByCategory($params) {
		$request = request();
		$fullUrl = $request->url();
		$fullUrlItems = explode("/", $fullUrl);

		unset($params['attributes']);
		
		if(!empty($params)){
			$oBlogs = BlogRepository::getBlogsByCategoryId($params);
		}
		elseif (count($fullUrlItems) >= 2) {
			$category = $fullUrlItems[count($fullUrlItems) - 1];
			if (is_numeric($category)) {

				$oBlogs = BlogRepository::getBlogsByCategoryId($category);

			} else {

				$oBlogs = BlogRepository::getByCategoryName($category);

			}
		}

		$aParams = array();

		$aParams['oBlogs'] = $oBlogs;

		if (!intval(BlogConfigRepository::getConfigKey('single_url'))) {
			return view('blog::config-alert', ['error' => 'single_url']);
		}

		$single_url = PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'));

		if ($single_url->url == "/") {
			$aParams['single_url'] = '/';
		} else {
			$aParams['single_url'] = '/' . $single_url->url;
		}

		$aParams['single_url'] = str_replace(":any", "", $aParams['single_url']);

		$aParams['anchor_url'] = self::anchorUrl();

		$aParams['datetime_format'] = BlogConfigRepository::getConfigKey('datetime_format');
		return view('blog::blog-category', $aParams);

	}

	/**
	 * <div data-pattern-func="module:blog@categoriesList"></div>
	 * show list of available Categories
	 */
	public function categoriesList() {
		if (!intval(BlogConfigRepository::getConfigKey('categories_url'))) {
			return view('blog::config-alert', ['error' => 'categories_url']);
		}
		$cat_url = PageRepository::getPage(BlogConfigRepository::getConfigKey('categories_url'));
		if (is_null($cat_url) || $cat_url->url == "/") {
			$data['categories_url'] = '/';
		} else {
			$data['categories_url'] = '/' . $cat_url->url;
		}

		$data['categories_url'] = str_replace(":any", "", $data['categories_url']);

		$data['categories'] = BlogRepository::getCategory();

		return view('blog::categolies-list', $data);

	}

	/*
		    *
	*/
	public static function showRelatedPosts($categories = array(), $count = 3, $rand = TRUE) {
		$base_route = \Route::input('page');
		if (!intval(BlogConfigRepository::getConfigKey('single_url'))) {
			return view('blog::config-alert', ['error' => 'single_url']);
		}

		$page = str_replace("/:any", "", PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'))->url);

		$entry = Blog::where(DB::raw('CONCAT("' . $page . '/", url)'), $base_route)->first();

		/*Get if exist  manual selected Related posts else get by category*/
		$oBlogs = BlogRepository::getPreChoosenRelatedPosts($entry->id);
		if (!$oBlogs) {
			unset($categories['attributes']);
			if (empty($categories) && !is_null($entry)) {
				$categories = array_column($entry->categories()->get()->toArray(), 'category_id');
			}

			$oBlogs = BlogRepository::getBlogsByCategoryId($categories, $count, $rand)->take($count);

		}

		$aParams = array();
		$aParams['related'] = $oBlogs;

		$single_url = PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'));

		if ($single_url->url == "/") {
			$aParams['single_url'] = '/';
		} else {
			$aParams['single_url'] = '/' . $single_url->url;
		}

		$aParams['single_url'] = str_replace(":any", "", $aParams['single_url']);

		$aParams['datetime_format'] = BlogConfigRepository::getConfigKey('datetime_format');

		return view('blog::relatedPosts', $aParams);

	}
	/*
		    * <div data-pattern-func="module:blog@showTagCloud"></div>
	*/
	public static function showTagCloud() {
		$aParams = array();

		$tagsCount = \Atlantis\Models\Tag::where('resource', '=', 'blog')->count();

		$tags = \Atlantis\Models\Tag::select('tag', DB::raw('count(*) as total'))->where('resource', '=', 'blog')->groupBy('tag')->get()->all();

		foreach ($tags as $key => $t) {

			$t->density = floor((100 / $tagsCount) * $t->total);

			$aParams['tags'][] = $t;
		}

		$aParams['anchor_url'] = self::anchorUrl();

		if (empty($tags)) {
			return null;
		}
		return view('blog::tagCloud', $aParams);
	}

	/*
       <div data-pattern-func="module:blog@shareWidget" ></div>
       <div data-networks="fb,twitter,linkedin" data-pattern-func="module:blog@shareWidget"></div>
	*/
    public function shareWidget($aParams = array()) {
        $base_route = \Route::input('page');

		//this is dumb and needs to be replaced .. maybe change the config to have the anchor url be not connected to an actual page
		if (!intval(BlogConfigRepository::getConfigKey('single_url'))) {
			return view('blog::config-alert', ['error' => 'single_url']);
		}

		$page = str_replace("/:any", "", PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'))->url);

		$entry = Blog::where(DB::raw('CONCAT("' . $page . '/", url)'), $base_route)->first();
		if ($entry->status == 'draft' && is_null(auth()->user())) {

			return app('AtlantisRedirect')->set(redirect()->to('404'));

		}
		$data_share = array();

		if ($entry) {
			$data_share['data'] = $entry;		
		}
       
        $default = "fb,twitter,linkedin";
        $attributes = $aParams && array_key_exists('attributes', $aParams) ? $aParams['attributes'] : [];
        $all_soical = array_key_exists('data-networks', $attributes) ? $attributes['data-networks'] : $default;
        $all_soical = explode(',', $all_soical);
        foreach ($all_soical as $social) {
        	if($social == 'fb' || $social == 'facebook'){
        		$data_share['networks'][$social]['url'] = 'https://www.facebook.com/sharer/sharer.php?u=#url';
        		$data_share['networks'][$social]['class'] = 'fa fa-facebook fb';

        	}
        	if($social == 'ln' || $social == 'linkedin'){
        		$data_share['networks'][$social]['url'] = 'https://www.linkedin.com/shareArticle?mini=true&url=#url&title=#title';
        		$data_share['networks'][$social]['class'] = 'fa fa-linkedin ln';
        	}
        	if($social == 'tw' || $social == 'twitter'){
        		$data_share['networks'][$social]['url'] = 'https://twitter.com/share?text=#title&url=#url';
        		$data_share['networks'][$social]['class'] = 'fa fa-twitter tw';
        	}

        }

        //dd($all_soical);
        //dd($data_share);      

       
    	return view('blog::share-social-widget', $data_share);

    }

    /*
        <div data-pattern-func="module:blog@relatedPostsWidget"></div>

    */
    public static function relatedPostsWidget($aParams = array(), $rand = TRUE) {
		$base_route = \Route::input('page');
		if (!intval(BlogConfigRepository::getConfigKey('single_url'))) {
			return view('blog::config-alert', ['error' => 'single_url']);
		}

		$page = str_replace("/:any", "", PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'))->url);

		$entry = Blog::where(DB::raw('CONCAT("' . $page . '/", url)'), $base_route)->first();

		//get number of posts
	    $count = $aParams && array_key_exists(0, $aParams) ? $aParams[0] : 2;

		/*Get if exist  manual selected Related posts else get by category*/
		$oBlogs = BlogRepository::getPreChoosenRelatedPosts($entry->id);
	   
		$categories = array();
		if (!$oBlogs) {
			unset($categories['attributes']);
			if (empty($categories) && !is_null($entry)) {
				$categories = array_column($entry->categories()->get()->toArray(), 'category_id');
			}

			$oBlogs = BlogRepository::getBlogsByCategoryId($categories, $count, $rand)->take($count);

		}

		$aParams = array();
		$aParams['related'] = $oBlogs;

		$single_url = PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'));

		if ($single_url->url == "/") {
			$aParams['single_url'] = '/';
		} else {
			$aParams['single_url'] = '/' . $single_url->url;
		}

		$aParams['single_url'] = str_replace(":any", "", $aParams['single_url']);

		$aParams['datetime_format'] = BlogConfigRepository::getConfigKey('datetime_format');

		return view('blog::relatedPostsWidget', $aParams);

	}


}