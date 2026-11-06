<?php

namespace Module\Blog\Controllers\Admin;

use Atlantis\Helpers\Tools;
use Atlantis\Models\Repositories\PageRepository;
use Module\Blog\Models\Repositories\BlogRepository;
use Module\Blog\Models\Categories;
use Illuminate\Http\Request;
use Module\Blog\Models\Blog;
use Atlantis\Controllers\Admin\AdminModulesController;
use Module\Blog\Models\Repositories\BlogConfigRepository;

class BlogAdminController extends AdminModulesController
{

    public $status = [
        'published' => 'publish',
        'draft' => 'draft'
    ];
    public $allow_comments = [
        1 => 'allow',
        0 => 'disallow'
    ];

    public function __construct()
    {
        $this->dom = new \DOMDocument();
        
        parent::__construct(\Config::get('blog.setup'));
    }

    /*
     * Show list
     *
     * modules/blog
     *
     * Responds to requests to GET
     */

    public function getIndex($id = null)
    {

        $aData = array();

        if (\Session::get('info') != NULL)
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

        $aData['config'] = BlogConfigRepository::getConfig();
        
        /*use PageRepository::getAllPages() for elder A3 versions */
        if (method_exists('Atlantis\Models\Repositories\PageRepository', 'getAllActive')) {
            $pages = PageRepository::getAllActive();
        }
        else{
            $pages = PageRepository::gellAll();
        }

        $aData['pages_list'] = [];
        $aData['pages_list'][] = '';

        foreach($pages as $p)
        {
            $aData['pages_list'][$p->id] = $p->name;
        }

        $aData['datetime_formats'] = [
            '%B %Y' => 'January 2017',
            '%B %e, %Y' => 'January 1, 2017' ,
            '%b %e, %Y' => 'Jan 1, 2017',
            '%A, %B %e, %Y' => 'Monday, Jan 1, 2017' ,
            '%m/%d/%Y' => '01/01/2017 (m/d/y)' ,
            '%m, %Y' => '01, 2017 (m/y)'
        ];

        
        if (method_exists('Atlantis\Helpers\Tools', 'getCommentEngines')) {
            $aData['comment_engines'] = ['' => ''] + Tools::getCommentEngines();
        }
        else{
            $aData['comment_engines'] = array();;
        }

        //BlogRepository::getBlogsByCategoryId(7,1);

        return view('blog-admin::admin/list', $aData);
    }

    /*
     * Show add blog view
     *
     * modules/blog/add
     *
     * Responds to requests to GET
     */

    public function getAdd()
    {

        $aData = array();

        if (\Session::get('info') != NULL)
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

        $aData['status_dropdown'] = $this->status;
        $aData['allow_comments_dropdown'] = $this->allow_comments;
        $aData['nickname'] = auth()->user()->name;
        $aData['posts'] = Blog::select('title', 'id')->get()->pluck('title', 'id')->all();


        $aData['categories'] = BlogRepository::getCategories();

        return view('blog-admin::admin/add', $aData);
    }

    /*
     * Save new blog entry
     *
     * modules/blog/add
     *
     * Responds to requests to POST
     */

    public function postAdd(Request $request)
    {

        $blogDB = new BlogRepository();
        
        $validator = $blogDB->validationCreate($request->all());

        if (!$validator->fails())
        {

            $aData = $request->all();
            $aData['user_id'] = auth()->user()->id;
            
            if(isset($aData['imgs']))
            {
                $aData['imgs'] = implode(',', $aData['imgs']);
            }
            else{
                $aData['imgs'] = 0; 
            }



            $id = $blogDB->add($aData);

           if(isset($aData['relatedIds']) && $aData['relatedIds'][0] != 0)
            {
                foreach ($aData['relatedIds'] as $key => $r) {
                    BlogRepository::addRelatedBlogs(['blog_id'=> $id, 'related_blog_id' => $r]);
                }
            }
            //see if they selected any categories and add them to the lookup table

            if(isset($aData['category']))
            {
                foreach($aData['category'] as $cat_id => $name)
                {
                    BlogRepository::addCategoryLu([
                        'category_id' => $cat_id,
                        'blog_id' => $id
                    ]);
                }
            }

            \Session::flash('success', 'Entry ' . $aData['title'] . ' was created');

            if ($request->get('_update'))
            {
                return redirect('admin/modules/blog/edit/' . $id);
            } else
            {
                return redirect('admin/modules/blog');
            }

        } else
        {
            return redirect('admin/modules/blog/add')->withErrors($validator)->withInput();
        }
    }

    /*
     * Show edit blog view
     *
     * modules/blog/edit/{id}
     *
     * Responds to requests to GET
     */

    public function getEdit($id = null)
    {

        $oBlog = BlogRepository::get($id);


        $aData = array();

        if (\Session::get('info') != NULL)
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

        $aTags = array();

        $tags = \Atlantis\Models\Repositories\TagRepository::getTagsByResourceID('blog', $id);

        foreach ($tags as $tag)
        {
            $aTags[] = $tag->tag;
        }

        $this_categories = $oBlog->categories;

        $cat_idx = [];

        if(count($this_categories))
        {
            foreach($this_categories as $cats_lu)
            {
                $cat_idx[] = $cats_lu->category_id;
            }
        }

        $aData['category_idx'] = $cat_idx;

        $aData['categories'] = BlogRepository::getCategories();

        $aData['posted_date'] = \Atlantis\Helpers\Tools::getExpirationDateForView($oBlog->posted_date);
        $aData['expire_date'] = \Atlantis\Helpers\Tools::getExpirationDateForView($oBlog->expire_date);
        $aData['tags'] = implode(',', $aTags);
        $aData['status_dropdown'] = $this->status;
        $aData['allow_comments_dropdown'] = $this->allow_comments;
        $aData['oBlog'] = $oBlog;
        $aData['posts'] = Blog::select('title', 'id')->get()->pluck('title', 'id')->all();
        $aData['related'] = $oBlog->relatedPosts()->get()->pluck('related_blog_id')->all();

        if(!is_null(BlogConfigRepository::getConfigKey('single_url')))
        {      
            $page = PageRepository::getPage(BlogConfigRepository::getConfigKey('single_url'));
            
            if (is_null($page))
            {
                \Session::flash('error', 'The page selected for Single_url was deleted, please select another one into the module config.');
                $singleUrl = '';
            }
            else
            {
                $singleUrl = str_replace("/:any", "", $page->url);
            }

        }
        else{
            
            \Session::flash('error', 'Please enter single_url into module config');
            $singleUrl = '';
        }

        $aData['previewUrl'] = url($singleUrl . '/' . $oBlog->url.'?preview=1');

        return view('blog-admin::admin/edit', $aData);
    }

    /*
     * Edit blog entry
     *
     * modules/blog/edit/{id}
     *
     * Responds to requests to POST
     */

    public function postEdit($id = null, Request $request)
    {

        $blogDB = new BlogRepository();

        $validator = $blogDB->validationCreate($request->all(), $id);

        if (!$validator->fails())
        {
            $aData = $request->all();
            
            if(isset($aData['imgs']))
            {
                $aData['imgs'] = implode(',', $aData['imgs']);
            }
            else{
                $aData['imgs'] = 0; 
            }
            
            $blogDB->edit($id, $aData);

            BlogRepository::deleteRelatedBlogs($id);
            if(isset($aData['relatedIds']) && $aData['relatedIds'][0] != 0)
            {   
                
                foreach ($aData['relatedIds'] as $key => $r) {
                    BlogRepository::addRelatedBlogs(['blog_id'=> $id, 'related_blog_id' => $r]);
                }
            }

            $this_categories = $request->get('category');

            if(!is_null($this_categories) && count($this_categories))
            {

                BlogRepository::deleteCategoryLuBlogId($id);

                foreach($this_categories as $cat_id => $name)
                {
                    BlogRepository::addCategoryLu([
                        'category_id' => $cat_id,
                        'blog_id' => $id
                    ]);
                }
            }

            \Session::flash('success', 'Entry ' . $request->get('title') . ' was edited');

            if ($request->get('_update'))
            {
                return redirect('admin/modules/blog/edit/' . $id);
            } else
            {
                return redirect('admin/modules/blog');
            }

            return redirect('admin/modules/blog')->with('success', 'Success');
        }
        else
        {
            return redirect('admin/modules/blog/edit/' . $id)->withErrors($validator)->withInput();
        }
    }

    /*
     * Delete blog entry
     *
     * modules/blog/delete/{id}
     *
     * Responds to requests to GET
     */

    public function getDelete($id = null)
    {

        if (BlogRepository::deleteEntry($id))
        {
            return redirect('admin/modules/blog')->with('success', 'Entry was deleted');
        }
        else
        {
            return redirect('admin/modules/blog')->with('error', 'Invalid ID');
        }
    }

    public function postBulkAction(Request $request)
    {

        if ($request->get('bulk_action_ids') != NULL)
        {

            $aIDs = explode(',', $request->get('bulk_action_ids'));

            if ($request->get('action') == 'bulk_delete')
            {

                foreach ($aIDs as $id)
                {
                    BlogRepository::deleteEntry($id);
                }
                \Session::flash('success', 'Entries was deleted');
            }
        }

        return redirect()->back();
    }

    public function postUpdateConfig(Request $request)
    {
        $model = new BlogConfigRepository();
        $model->updateConfig($request->all());

        return redirect()->back()->with('success', 'Config was updated');
    }

    public function getCategories(Request $request)
    {
        $aData = array();

        if (\Session::get('info') != NULL)
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

        return view('blog-admin::admin/categories-list', $aData);
    }


    public function getCategoryAdd(Request $request)
    {
        return view('blog-admin::admin/category-add');
    }

    public function postCategoryAdd(Request $request)
    {
        $data = $request->all();

        $repo = new BlogRepository();

        $validation = $repo->validationCreateCategory($data);

        if(!$validation->fails())
        {
            $repo->addCategory($data);

            \Session::flash('success', 'Category was created successfully.');

            return redirect('admin/modules/blog/categories');
        }
        else
        {
            return redirect()->back()->withErrors($validation)->withInput();
        }

    }

    public function getCategoryEdit($id, Request $request)
    {
        $data['model'] = BlogRepository::getCategory($id);

        return view('blog-admin::admin/category-edit', $data);
    }

    public function postCategoryEdit($id, Request $request)
    {
        $data = $request->all();

        $repo = new BlogRepository();

        $validation = $repo->validationEditCategory($data, $id);

        if(!$validation->fails())
        {
            $repo->updateCategory($id, $data);

            \Session::flash('success', 'Category was edited successfully.');

            return redirect('admin/modules/blog/categories');
        }
        else
        {
            return redirect()->back()->withErrors($validation)->withInput();
        }

    }

    public function getCategoryDelete($id)
    {
        //we need to remove all the related to this category blog entries in the lookup table
        BlogRepository::deleteCategoryLuCategoryId($id);

        BlogRepository::deleteCategory($id);

        return redirect('admin/modules/blog/categories')->with('success', 'Category was deleted.');

    }

    public function postBulkActionCategory(Request $request)
    {

        if ($request->get('bulk_action_ids') != NULL)
        {

            $aIDs = explode(',', $request->get('bulk_action_ids'));

            if ($request->get('action') == 'bulk_delete')
            {

                foreach ($aIDs as $id)
                {
                    BlogRepository::deleteCategoryLuCategoryId($id);

                    BlogRepository::deleteCategory($id);
                }

                \Session::flash('success', 'Multiple categories was deleted.');
            }
        }

        return redirect()->back();
    }

    public $WpImportStatus = array(
       
       'categories' => [
            'total' => 0,
            'saved' => 0,
            'failed' => 0
       ],
       'posts' => [
            'total' => 0,
            'saved' => 0,
            'failed' => 0
       ]
    );

    public function getWpImportStatus(Request $request)
    {
        return $this->WpImportStatus;
    }

    public function postWpImport(Request $request)
    {   
        try {
            $posts = [];
            $post = [];
            $categories = [];

            $xml = $request->file('wpXml');
            $this->dom->load($xml);

            $xpath = new \DOMXPath($this->dom);

            $catItems = $xpath->query("//item/category[@domain='category']");
            $postItems = $xpath->query("//item[wp:post_type='post']");
            $base_blog_url = $xpath->query("//channel/wp:base_site_url")->item(0)->nodeValue;
            $base_site_url = $xpath->query("//channel/wp:base_blog_url")->item(0)->nodeValue;

            foreach ($catItems as $c)
            {
                $categories[] = $c->getAttribute('nicename');
            }

            foreach ($postItems as $i)
            {

                $post['category'] = [];
                $post['tags'] = [];
                foreach ($i->childNodes as $node)
                {
                    switch ($node->nodeName) {

                        case 'link':

                        $post['url'] = rtrim(str_replace(url($base_blog_url.'/'),"",$node->nodeValue),'/');
                        break;

                        case 'title':
                        $post['title'] = $node->nodeValue;
                        break;

                        case 'wp:status':
                        $post['status'] = $node->nodeValue == 'publish' ? 'published' : 'draft';
                        break;

                        case 'wp:post_date':
                        $post['posted_date'] = date('m/d/Y', strtotime($node->nodeValue));
                        break;

                        case 'description':
                        $post['description'] = $node->nodeValue;
                        break;

                        case 'content:encoded':
                        $post['body'] = $node->nodeValue;
                        break;

                        case 'excerpt:encoded':
                        $post['blurb'] = $node->nodeValue;
                        break;

                        case 'wp:comment_status':
                        $post['allow_comments'] = $node->nodeValue == 'open' ? 1 : 0;
                        break;

                        case 'dc:creator':
                        $post['nickname'] = $node->nodeValue;
                        break;

                        case 'category':

                        if ($node->getAttribute('domain') == 'category'){
                            $post['category'][] = $node->getAttribute('nicename');
                        }
                        if ($node->getAttribute('domain') == 'post_tag'){

                            $post['tags'][] = $node->getAttribute('nicename');
                        }                        
                        break;
                    }
                }

                $posts[] = $post;
            }

            $data['base_site_url'] = $base_site_url;
            $data['base_blog_url'] = $base_blog_url;
            $data['posts'] = $posts;
            $data['categories'] = array_unique($categories);

            self::saveWpImportData($data);

            return view('blog-admin::admin/includes/import-statis-partial', $this->WpImportStatus);
        }
        catch(Exception $e){
            
            return view('blog-admin::admin/includes/import-statis-partial', $this->WpImportStatus);
            return $e;
        }

    }

    public function saveWpImportData($data)
    {
        /*SAVE CATEORIES FIRST*/
        $repo = new BlogRepository();
        if($data['categories'])
        {

            $this->WpImportStatus['categories']['total'] = count($data['categories']);

            foreach ($data['categories'] as $key => $c) 
            {
                $validation = $repo->validationCreateCategory(['category_name' => $c]);

                if(!$validation->fails())
                {
                    $repo->addCategory(['category_name' => $c]);

                    $this->WpImportStatus['categories']['saved']++;
                }
                else
                {   
                    $this->WpImportStatus['categories']['failed']++;   
                }
            }
        }

        /*SAVE POSTS*/
        if($data['posts'])
        {

            $this->WpImportStatus['posts']['total'] = count($data['posts']);

            foreach ($data['posts'] as $key => $p) 
            {
                
                $validator = $repo->validationCreate($p);
                
                if (!$validator->fails())
                {

                    if (isset($p['tags'])) {
                        $p['tags'] = implode(',' , $p['tags']);
                    }

                    $p['user_id'] = auth()->user()->id;
                    $p['body_words'] = 10;

                    $id = $repo->add($p);

                    $this->WpImportStatus['posts']['saved']++;
                    //dd($p['category'],!empty($p['category']));

                    if(isset($p['category']) && !empty($p['category']))
                    {
                        foreach($p['category'] as $catName)
                        {
                            $cat_id = Categories::where('category_name', '=', $catName)->first()->id;
                            if(!is_null($cat_id)) {

                                BlogRepository::addCategoryLu([
                                    'category_id' => $cat_id,
                                    'blog_id' => $id
                                ]);
                            }
                        }
                    }

                } else
                {   
                    
                    $this->WpImportStatus['posts']['failed']++;

                }
            }
            
        }

    }

}
