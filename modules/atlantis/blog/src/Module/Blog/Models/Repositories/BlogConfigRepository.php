<?php

namespace Module\Blog\Models\Repositories;

use Module\Blog\Models\BlogConfig;

class BlogConfigRepository {

  public static function getAll() {

    return BlogConfig::all();
  }
  
  public static function getConfig() {
    $model = BlogConfig::all();
    
    $aConfig = array();
    
    foreach ($model as $m) {
      $aConfig[$m->config_key] = unserialize($m->config_value);
    }
    
    return $aConfig;
  }
  
  public static function getConfigKey($key) {
    
    $model = BlogConfig::where('config_key', '=', $key)->get()->first();
    
    if ($model == NULL) {
      return NULL;
    } else {
      return unserialize($model->config_value);
    }
  }

  public function addConfig($key, $value) {

    $model = BlogConfig::firstOrNew(['config_key' => $key]);

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
