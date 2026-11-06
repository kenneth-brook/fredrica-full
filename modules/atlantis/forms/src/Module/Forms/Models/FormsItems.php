<?php

namespace Module\Forms\Models;

use Atlantis\Models\Base as Base;

class FormsItems extends Base {

  protected $table = "forms_items";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'form_id',
      'label',
      'field_type',
      'field_name',
      'validation',
      'attributes',
      'validation_msg',
      'field_value',
      'weight',
      'use_reply_to',
      'use_email_from'
  ];

}
