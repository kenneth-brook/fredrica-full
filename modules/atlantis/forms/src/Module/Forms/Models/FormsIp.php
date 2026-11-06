<?php

namespace Module\Forms\Models;

use Atlantis\Models\Base as Base;

class FormsIp extends Base {

  protected $table = "forms_ip";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'ip',
      'form_id',
      'banned'
  ];

}
