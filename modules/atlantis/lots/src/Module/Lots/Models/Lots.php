<?php

namespace Module\Lots\Models;

use Atlantis\Models\Base;

class Lots extends Base {

  protected $table = "lots";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

    protected $fillable = [
      'lot_number',
      'sold',
      'location',
      'price',
      'acreage',
      'sitemap',
      'map_category',
      'lat',
      'lng',
      'description',
      'view_orientation',
      'frontage',
      'lot_number_ext',
      'imgs',
      'video'
    ];
}