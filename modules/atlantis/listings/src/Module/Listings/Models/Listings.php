<?php

namespace Module\Listings\Models;

use Atlantis\Models\Base;

class Listings extends Base {

  protected $table = "listings";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

    protected $fillable = [
      'title',
      'active',
      'weight',
      'url',
      'type',
      'listing_type',
      'description',
      'details',
      'price',
      'furnished',
      'meta_desc',
      'meta_key',
      'heated_sq_ft',
      'acres',
      'bedrooms',
      'full_baths',
      'half_baths',
      'architect',
      'builder',
      'lot_type',
      'video',
      'you_tube',
      'map_category',
      'lat',
      'lng',
      'home_site_number',
      'floor_plan',
      'images',
      'brochure',
      'finishes'

    ];
}