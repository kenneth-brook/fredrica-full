<?php

namespace Module\Blog\Models;

use Atlantis\Models\Base as Base;

class Categories extends Base
{

    protected $table = "blog_categories";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_name'
    ];

    public function categories_lu()
    {
        return $this->hasMany('Module\Blog\Models\CategoriesLu', 'category_id', 'id');
    }

}
