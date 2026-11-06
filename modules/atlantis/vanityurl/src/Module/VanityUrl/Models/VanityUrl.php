<?php

namespace Module\VanityUrl\Models;

use Atlantis\Models\Base;

class VanityUrl extends Base {

  protected $table = "vanityurl";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

    protected $fillable = [
    'source_url',
    'dest_url'
    ];
   
}