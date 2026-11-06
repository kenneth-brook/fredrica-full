<?php

namespace Module\Forms\Models\Repositories;

use Module\Forms\Models\FormsConfig;

class FormsConfigRepository {

  public static function getAll() {

    return FormsConfig::all();
  }
  
  public static function getConfig() {
    $model = FormsConfig::all();
    
    $aConfig = array();
    
    foreach ($model as $m) {
      $aConfig[$m->config_key] = unserialize($m->config_value);
    }
    
    return $aConfig;
  }
  
  public static function getConfigKey($key) {
    
    $model = FormsConfig::where('config_key', '=', $key)->get()->first();
    
    if ($model == NULL) {
      return NULL;
    } else {
      return unserialize($model->config_value);
    }
  }

  public function addConfig($key, $value) {

    $model = FormsConfig::firstOrNew(['config_key' => $key]);

    $model->config_key = $key;
    $model->config_value = serialize($value);

    if (isset($model->id)) {
      $model->update();
    } else {
      $model->save();
    }
  }

  public function updateConfig($data) {

    foreach ($data as $key => $value) {
      if ($key != '_token' && $key != '_update_config') {
        $this->addConfig($key, $value);
      }
    }
  }

}
