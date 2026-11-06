<?php

namespace Module\Blog\Models;

use Module\Relativity\Models\TagRelation;
use Module\Blog\Models\Blog;
use Module\Blog\Models\Repositories\BlogConfigRepository;
use Atlantis\Models\Repositories\PageRepository;

class Relativity extends \Atlantis\Models\Base {

  public static function getByTags($tags = array()) {

    $pageId = BlogConfigRepository::getConfigKey('single_url');
    $page = PageRepository::getPage($pageId);
    
    if (is_null($page)){
        return;
    } 

    $pageUrl = str_replace("/:any", "" , $page->url);

    $aBlogs = [];
    $blogIds = TagRelation::select('relationable_id')->where('relationable_type', '=' , 'Module\Blog\Models\Blog')->whereIn('tag_id', $tags)->pluck('relationable_id')->toArray();

    $blogs = Blog::whereIn('id', $blogIds)->get();
    
    foreach ($blogs as $k => $b) {
        //dd($b);
        $aBlogs[$k]['type'] = 'Blog';
        $aBlogs[$k]['id'] = $b->id;
        $aBlogs[$k]['title'] = $b->title;
        $aBlogs[$k]['url'] = $pageUrl.'/'.$b->url;
        $aBlogs[$k]['body'] = $b->blurb != '' ? $b->blurb : $b->body;
        $aBlogs[$k]['image'] = $b->imgs;
        $aBlogs[$k]['object'] = $b;

    }
    return $aBlogs;
   /* return [
        [	
        	'type' => 'Blog',
        	'id' => 'id',
        	'title' => 'title',
            'body' => 'body',
        	'url' => 'url',
        	'image' => 'image',
        	'object' => 'object'
        ]
        
    ];*/
  }

}
