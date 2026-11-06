<?php

namespace Module\CustomFields\Models;

use Atlantis\Models\Base;

class CustomFields extends Base
{

    protected $table = "customfields";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'field_label',
        'field_type',
        'category_id',
        'field_slur'
    ];

    public function value()
    {
        return $this->hasOne('Module\CustomFields\Models\CustomFieldsValues', 'field_id', 'id');
    }


    public function content($page_id, $version_id)
    {
        return $this->hasOne('Module\CustomFields\Models\CustomFieldsValues', 'field_id', 'id')
            ->where('page_id', $page_id)
            ->where('version_id', $version_id)
            ->first();
    }

    public function category()
    {
        return $this->hasOne('Module\CustomFields\Models\CustomFieldsCategories', 'id', 'category_id');
    }

}