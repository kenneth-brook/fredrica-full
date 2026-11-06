<?php

namespace Module\CustomFields\Events;

/*
 * Event: CustomFields
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Queue\SerializesModels;

class CustomFieldsEvent extends \Illuminate\Support\Facades\Event
{

    use SerializesModels;

    public function test()
    {

        $t = \App::make('Transport');

        $t->setEventValue('demo_event', array('name' => 'test', 'weight' => '10'));

    }

    public function handle($event)
    {

    }

    public function subscribe($events)
    {

        $events->listen('page.edited', 'Module\CustomFields\Controllers\CustomFieldsController@pageEdited');
        $events->listen('page.created', 'Module\CustomFields\Controllers\CustomFieldsController@pageCreated');

    }

}
