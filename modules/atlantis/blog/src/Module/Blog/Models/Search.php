<?php

namespace Module\Blog\Models;

class Search extends \Atlantis\Models\Base {

  public static function get($search) {

    //DO all operations here , need to return an array with  url / name keypair

    $anchorUrl = Repositories\BlogConfigRepository::getConfigKey('anchor_url');

    return [
        $anchorUrl . '/demo-page1' => 'blog demo1',
        $anchorUrl . '/demo-page2' => 'blog demo2'
    ];
  }

}
