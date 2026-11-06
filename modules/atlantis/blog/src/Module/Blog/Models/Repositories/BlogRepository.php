<?php

namespace Module\Blog\Models\Repositories;

use Atlantis\Helpers\Cache\AtlantisCache;
use Atlantis\Models\Repositories\PageRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Module\Blog\Models\Blog;
use Module\Blog\Models\BlogRelatedPosts;
use Module\Blog\Models\Categories;
use Module\Blog\Models\CategoriesLu;

/**
 * Class BlogRepository
 * @package Module\Blog\Models\Repositories
 */
class BlogRepository {

	public static $_POSTED_DATE_FORMAT_VIEW = 'm/d/Y';

	/**
	 * @param $data
	 * @param null $id
	 * @return mixed
	 */
	public function validationCreate($data, $id = NULL) {

		$rules_create = [
			'title' => 'required',
			'url' => 'required|unique:blog,url,' . $id . '|valid_url',
			'nickname' => 'required',
			'posted_date' => 'date_format:' . self::$_POSTED_DATE_FORMAT_VIEW,
			'expire_date' => 'date_format:' . self::$_POSTED_DATE_FORMAT_VIEW,
		];

		$messages = [
			'required' => trans('blog::validation.required'),
			'unique' => trans('blog::validation.unique'),
			'valid_url' => trans('blog::validation.valid_url'),
			'date_format' => trans('blog::validation.date_format'),
		];

		return Validator::make($data, $rules_create, $messages);
	}

	/**
	 * @param $data
	 * @return mixed
	 */
	public function validationCreateCategory($data) {
		$rules_create = [
			'category_name' => 'required|unique:blog_categories,category_name',
		];

		$messages = [
			'required' => trans('blog::validation.required'),
			'unique' => trans('blog::validation.unique'),
		];

		return Validator::make($data, $rules_create, $messages);
	}

	/**
	 * @param $data
	 * @param $id
	 * @return mixed
	 */
	public function validationEditCategory($data, $id) {
		$rules_create = [
			'category_name' => 'required|unique:blog_categories,category_name,' . $id,
		];

		$messages = [
			'required' => trans('blog::validation.required'),
			'unique' => trans('blog::validation.unique'),
		];

		return Validator::make($data, $rules_create, $messages);
	}

	/**
	 * @param $data
	 * @return mixed
	 */
	public function validationEdit($data) {

		return Validator::make($data, $this->rules_create);
	}

	/**
	 * @param $data
	 * @return mixed
	 */
	public function add($data) {

		if (empty($data['posted_date'])) {
			$data['posted_date'] = NULL;
		} else {
			$data['posted_date'] = Carbon::createFromFormat(self::$_POSTED_DATE_FORMAT_VIEW . ' H:i:s', $data['posted_date'] . ' 00:00:01')->toDateTimeString();
		}

		if (empty($data['expire_date'])) {
			$data['expire_date'] = NULL;
		} else {
			$data['expire_date'] = Carbon::createFromFormat(self::$_POSTED_DATE_FORMAT_VIEW . ' H:i:s', $data['expire_date'] . ' 00:00:01')->toDateTimeString();
		}

		$model = Blog::create($data);

		$eventData = [
			'model' => $model,
			'data' => $data,
		];
		\Event::fire('relativity.model.created', [$eventData]);

		\Atlantis\Models\Repositories\TagRepository::addTagsWithDelimiter(',', $data['tags'], $model->id, 'blog');

		return $model->id;
	}

	/**

	 * @param $id
	 * @param $data
	 * @return bool
	 */
	public function edit($id, $data) {

		if (empty($data['posted_date'])) {
			$data['posted_date'] = NULL;
		} else {
			$data['posted_date'] = Carbon::createFromFormat(self::$_POSTED_DATE_FORMAT_VIEW . ' H:i:s', $data['posted_date'] . ' 00:00:01')->toDateTimeString();
		}
		if (empty($data['expire_date'])) {
			$data['expire_date'] = NULL;
		} else {
			$data['expire_date'] = Carbon::createFromFormat(self::$_POSTED_DATE_FORMAT_VIEW . ' H:i:s', $data['expire_date'] . ' 00:00:01')->toDateTimeString();
		}

		if (!isset($data['use_blurb'])) {
			$data['use_blurb'] = 0;
		}

		$model = Blog::findOrNew($id);

		if ($model != NULL) {

			$model->update($data);

			$eventData = [
				'model' => $model,
				'data' => $data,
			];
			\Event::fire('relativity.model.updated', [$eventData]);

			\Atlantis\Models\Repositories\TagRepository::updateTagsWithDelimiter(',', $data['tags'], $model->id, 'blog');

			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function getAll() {

		//return Blog::all();
		return Blog::paginate(BlogConfigRepository::getConfigKey('per_page'));
	}

	/**
	 * @return mixed
	 */
	public static function getAllPublished() {
		$tag = \Request::input('tag');

		$author = \Request::input('author');

		$search = \Request::input('search');

		$posts = Blog::where('status', '=', 'published')
			->whereRaw('(expire_date is NULL or expire_date > "' . Carbon::now() . '") and posted_date <= "' . Carbon::now() . '"');

		if (!is_null($tag)) {
			$tag = array($tag);
			$posts->whereIn('id', function ($query) use ($tag) {
				$query->select('resource_id')
					->from('tags')
					->whereIn('tag', $tag)
					->where('resource', '=', 'blog');
			});
		}
		if (!is_null($author)) {
			$posts->where('nickname', '=', $author);
		}
		if (!is_null($search)) {
			$posts->where('body', 'LIKE', '%' . $search . '%');
			$posts->orWhere('title', 'LIKE', '%' . $search . '%');
			$posts->orWhere('blurb', 'LIKE', '%' . $search . '%');
			$posts->orWhere('nickname', 'LIKE', '%' . $search . '%');
		}
		$posts = $posts->orderBy('posted_date', 'DESC')

			->paginate(BlogConfigRepository::getConfigKey('per_page'));

		return $posts;
	}

	/**
	 * @return mixed
	 */
	public static function getAllArchive($month, $year) {

		$oBlogs = Blog::where('status', '=', 'published');
		//$oBlogs = Blog::select("*")->orderBy("posted_date","DESC");

		if ($year) {
			$oBlogs = $oBlogs->whereYear("posted_date", "=", $year);
		}
		if ($month) {
			$oBlogs = $oBlogs->whereMonth("posted_date", "=", $month);
		}
		$oBlogs->whereRaw('(expire_date is NULL or expire_date > "' . Carbon::now() . '") and posted_date <= "' . Carbon::now() . '"');
		return $oBlogs->paginate(BlogConfigRepository::getConfigKey('per_page'));
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public static function get($id) {

		return Blog::find($id);
	}

	/**
	 * @param $id
	 * @return bool
	 */
	public static function deleteEntry($id) {

		$model = Blog::findOrNew($id);

		if ($model != NULL) {

			\Event::fire('relativity.model.deleted', [$model]);

			$model->delete();

			\Atlantis\Models\Repositories\TagRepository::deleteTag('blog', $id);

			BlogRelatedPosts::where('blog_id', '=', $id)->orWhere('related_blog_id', '=', $id)->delete();

			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * @return mixed
	 */
	public static function getRecentShort($amount) {
		$data['recent'] = Blog::where('status', 'published')

			->whereRaw('(expire_date is NULL or expire_date > "' . Carbon::now() . '") and posted_date <= "' . Carbon::now() . '"')
			->orderBy('posted_date', 'DESC')
			->take($amount)
			->get();

		$data['anchor_url'] = str_replace("/:any", "", PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'))->url);

		return \View::make('blog::recent-short', $data);

	}

	/**
	 * @return mixed
	 */
	public function getArchivesForMonth() {
		$month = request()->get('month');

		$data = [];

		$parsedMonth = strtotime("01-" . $month);

		$data['list'] = null;

		$data['month'] = $month;

		$data['anchor_url'] = AtlantisCache::rememberQuery('blogAnchorURL', [$month], function () {
			return BlogConfigRepository::getConfigKey('anchor_url') . "/";
		});

		if ($parsedMonth) {
			$data['list'] = AtlantisCache::rememberQuery('getMonthArchive', [$month], function () use ($month) {
				return $list = \DB::table('blog')
					->select('*')
					->where(\DB::raw('DATE_FORMAT(posted_date,\'%m-%Y\')'), $month)
					->where('status', 'published')
					->orderBy('posted_date', 'DESC')
					->get();
			});

		}

		return \View::make('blog::archives-list', $data);

	}

	/**
	 * @return mixed
	 */
	public static function getMakeArchivesList() {

		$data['list'] = AtlantisCache::rememberQuery('getArchivesList', ['getArchivesList'], function () {
			return $list = \DB::table('blog')
				->select(\DB::raw("year(posted_date) AS year,month(posted_date) AS month"))
				->where('status', 'published')
				->whereRaw('(expire_date is NULL or expire_date > "' . Carbon::now() . '") and posted_date <= "' . Carbon::now() . '"')
				->groupBy('year')
				->groupBy('month')
				->orderBy('year', 'DESC')
				->orderBy('month', 'DESC')
				->get();
		});
		return $data['list'];

	}

	/**
	 * <div data-pattern-func="module:blog@allOrdered"></div>
	 */
	public function allOrdered() {

		$oBlogs = AtlantisCache::rememberQuery('getAllBlogsOrdered', 'getAllBlogsOrdered', function () {
			return Blog::where('status', 'published')
				->orderBy('posted_date', 'DESC')
				->get();
		});

		$featuredImages = \MediaTools::getFeaturedImages($oBlogs);

		$aParams = array();

		$aParams['oBlogs'] = $oBlogs;
		$aParams['featuredImages'] = $featuredImages;
		$aParams['anchor_url'] = BlogConfigRepository::getConfigKey('anchor_url');

		return view('blog::blog-list', $aParams);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public static function getCategories() {
		return Categories::all();
	}

	/**
	 * @param $data
	 */
	public static function addCategory($data) {
		Categories::create($data);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public static function getCategory($id = null) {
		if (!is_null($id)) {
			return Categories::find($id);
		} else {
			return Categories::all();
		}
	}

	/**
	 * @param $id
	 * @param $data
	 */
	public static function updateCategory($id, $data) {
		Categories::find($id)->update($data);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public static function deleteCategory($id) {
		return Categories::find($id)->delete();

	}

	/**
	 * @param $category_id
	 */
	public static function deleteCategoryLuCategoryId($category_id) {
		CategoriesLu::where('category_id', $category_id)->delete();
	}

	/**
	 * @param $blog_id
	 */
	public static function deleteCategoryLuBlogId($blog_id) {
		CategoriesLu::where('blog_id', $blog_id)->delete();
	}

	/**
	 * @param $data
	 */
	public static function addCategoryLu($data) {
		CategoriesLu::create($data);
	}

	/**
	 * @param $blog_id
	 */
	public static function deleteRelatedBlogs($blog_id) {
		BlogRelatedPosts::where('blog_id', $blog_id)->delete();
	}

	/**
	 * @param $data
	 */
	public static function addRelatedBlogs($data) {
		BlogRelatedPosts::create($data);
	}

	/**
	 * @param $cat_id array of Ids or integer
	 * @param null $limit
	 * @return mixed
	 */
	public static function getBlogsByCategoryId($cat_id = null, $limit = null, $rand = true) {

		return AtlantisCache::rememberQuery('getBlogsByCategoryId', [$cat_id], function () use ($cat_id, $limit, $rand) {

			$result = Blog::whereHas('categories', function ($q) use ($cat_id) {
				if (is_array($cat_id)) {

					$q->whereIn('category_id', $cat_id);

				} else {

					$q->where('category_id', $cat_id);

				}

			})->where('status', 'published')
				->whereRaw('(expire_date is NULL or expire_date > "' . Carbon::now() . '") and posted_date <= "' . Carbon::now() . '"');

			if (!is_null($limit)) {

				$result->take($limit);
			}

			if (!$rand) {
				$result->orderBy('posted_date', 'DESC');

			} else {

				$result->orderBy(\DB::raw('RAND()'));
			}

			return $result->paginate(BlogConfigRepository::getConfigKey('per_page'));

		});
	}

	/**
	 * @param $category
	 * @return mixed
	 */
	public static function getByCategoryName($category) {

		$cat = Categories::where('category_name', '=', $category)->first();

		if (!is_null($cat)) {
			return BlogRepository::getBlogsByCategoryId($cat->id);
		}

	}

	/**
	 * @param $category
	 * @return mixed
	 */
	public static function getPreChoosenRelatedPosts($blog_id) {

		$ids = BlogRelatedPosts::select('related_blog_id')->where('blog_id', '=', $blog_id)->get()->toArray();

		if (empty($ids)) {
			return false;
		}
		return AtlantisCache::rememberQuery('get3PreChoosenRelatedPosts', [$ids], function () use ($ids) {

			$result = Blog::whereIn('id', $ids)->where('status', 'published')
				->whereRaw('(expire_date is NULL or expire_date > "' . Carbon::now() . '") and posted_date <= "' . Carbon::now() . '"');
			return $result->get();

		});

	}

}
