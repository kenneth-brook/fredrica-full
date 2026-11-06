<?php

namespace Module\Blog\Models;

use Atlantis\Models\Base as Base;

class BlogConfig extends Base {

  protected $table = "blog_config";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'config_key',
      'config_value'
  ];

}
