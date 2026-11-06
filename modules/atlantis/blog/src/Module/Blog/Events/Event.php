<?php

namespace Module\Blog\Events;

use Illuminate\Queue\SerializesModels;

class Event extends \Illuminate\Support\Facades\Event {

  use SerializesModels;

  public function subscribe($events) {

    $events->listen('page.title', 'Module\Blog\Events\Event@pageTitle');
    $events->listen('page.meta_description', 'Module\Blog\Events\Event@pageDescription');
  }

  public function pageTitle() {

    $title = \Module\Blog\Controllers\BlogController::$title;

    if ($title != NULL || !empty($title)) {
      \App::make('Transport')->setEventValue("page.title", array("title" => $title, "weight" => 10), TRUE);
    }
  }

  public function pageDescription() {

    $description = \Module\Blog\Controllers\BlogController::$description;

    if ($description != NULL || !empty($description)) {
      \App::make('Transport')->setEventValue("page.meta_description", array("title" => $description, "weight" => 10), TRUE);
    }
  }

}
