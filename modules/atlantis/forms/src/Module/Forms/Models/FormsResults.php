<?php

namespace Module\Forms\Models;

use Atlantis\Models\Base as Base;

class FormsResults extends Base {

  protected $table = "forms_results";
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'form_id',
      'set_id',
      'field_label',
      'field_name',
      'field_value',
      'post_url',
      'IP'
  ];
}
