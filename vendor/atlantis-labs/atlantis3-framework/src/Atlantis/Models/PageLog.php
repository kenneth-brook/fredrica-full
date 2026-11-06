<?php

namespace Atlantis\Models;

class PageLog extends Base
{

    protected $table = "pages_log";
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_id',
        'page_id',
        'pattern_id'
    ];
}