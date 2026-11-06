<?php

namespace Module\Forms\Models;

use Atlantis\Models\Base as Base;

class FormsConfig extends Base {

  protected $table = "forms_config";

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
