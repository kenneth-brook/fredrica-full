<?php

namespace Module\CustomFields\Models;

use Atlantis\Models\Base;

class CustomFieldsValues extends Base
{

    protected $table = "customfields_values";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'page_id',
        'version_id',
        'field_id',
        'field_value',
        'lang'
    ];

}