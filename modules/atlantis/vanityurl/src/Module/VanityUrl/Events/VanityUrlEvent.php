<?php

namespace Module\VanityUrl\Events;

/*
 * Event: VanityUrl
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Queue\SerializesModels;

class VanityUrlEvent extends \Illuminate\Support\Facades\Event {

  use SerializesModels;

  public function subscribe($events) {

    $events->listen('page.prediscovery', 'Module\VanityUrl\Controllers\VanityUrlController@redirect');
  }

}
