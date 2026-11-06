<?php

namespace Module\Blog\Seed;

class BlogSeeder extends \Illuminate\Database\Seeder {
  
  public function run() {
    
     $setup = require( base_path(). '/modules/atlantis/blog/src/Module/Blog/Setup/Setup.php');
    
     //check for the module with the same name
    $result = \DB::table("modules")
            ->where("name", "=", "Blog")->first();
    
    if (is_null($result)) {
      
      \DB::table("modules")
              ->insert([
                  'name' => $setup['name'],
                  'author' => $setup['author'],
                  'version' => $setup['version'],
                  'namespace' => $setup['moduleNamespace'],
                  'path' => $setup['path'],
                  'provider' => $setup['provider'],
                  'extra' => serialize($setup['extra']),
                  'adminURL' => $setup['adminURL'],
                  'icon' => $setup['icon'],
                  'active' => 1,
                  'description' => $setup['description'],
                  'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                  'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
              ]);      
    }
    
    
    $data = $this->getConfigData();

    foreach ($data as $row) {
      \DB::table('blog_config')->insert($row);
    }    
 }
 
 private function getConfigData() {

    return [
        [
            'id' => 1,
            'config_key' => 'anchor_url',
            'config_value' => serialize('/blog'),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ],
        [
            'id' => 2,
            'config_key' => 'datetime_format',
            'config_value' => serialize('%A, %B %e, %Y'),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ],
        [
            'id' => 3,
            'config_key' => 'per_page',
            'config_value' => serialize('5'),
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]
    ];
  }
 
}