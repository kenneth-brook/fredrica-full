<?php

namespace Module\Blog\Models;

use Atlantis\Models\Base as Base;

class Blog extends Base
{

    protected $table = "blog";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'blurb',
        'user_id',
        'url',
        'nickname',
        'allow_comments',
        'use_blurb',
        'body_words',
        'posted_date',
        'expire_date',
        'status',
        'gallery_id',
        'description',
        'seo_title',
        'meta_description',
        'imgs'
    ];

    public function categories()
    {
        return $this->hasMany('Module\Blog\Models\CategoriesLu', 'blog_id', 'id');
    }

    public function tags()
    {   
        
        return \Atlantis\Models\Tag::where('resource', '=', 'blog')->where('resource_id', '=', $this->id)->get();
    }
    public function relatedPosts()
    {   
        
        return $this->hasMany('Module\Blog\Models\BlogRelatedPosts', 'blog_id', 'id');
    }
}
