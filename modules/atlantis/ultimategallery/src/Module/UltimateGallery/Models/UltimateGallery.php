<?php

namespace Module\UltimateGallery\Models;

use Atlantis\Models\Base;

class UltimateGallery extends Base {

  protected $table = "ultimategallery";

  /**
   * The attributes that are mass assignable.
   *
   * @var array*/

    protected $fillable = [
    'name',
    'description',
    'gallery_id',
    'view',
    'options',
    'imgs',
    'css'
    ];
}