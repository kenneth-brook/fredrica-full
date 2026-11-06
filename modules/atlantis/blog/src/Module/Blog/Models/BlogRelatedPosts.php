<?php

namespace Module\Blog\Models;

use Atlantis\Models\Base as Base;

class BlogRelatedPosts extends Base
{

    protected $table = "blog_related_posts";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'blog_id',
        'related_blog_id'
    ];

    public function post()
    {
        return $this->belongsTo('Module\Blog\Models\Blog', 'id', 'blog_id');
    }
}
