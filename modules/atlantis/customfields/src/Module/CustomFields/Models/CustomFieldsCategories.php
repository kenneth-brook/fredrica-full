<?php

namespace Module\CustomFields\Models;

use Atlantis\Models\Base;

class CustomFieldsCategories extends Base
{

    protected $table = "customfields_categories";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'category_name',
        'template'
    ];


    public function fields()
    {
        return $this->hasMany('Module\CustomFields\Models\CustomFields', 'category_id', 'id');
    }

}