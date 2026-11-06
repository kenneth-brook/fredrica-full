<?php

namespace Module\VanityUrl\Providers;

/*
 * Provider: VanityUrl
 * @Atlantis CMS
 * v 1.0
 */

class VanityUrlServiceProvider extends \Illuminate\Support\ServiceProvider
{

  public function register()
  {

    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Setup.php', "vanityurl.setup"
    );
    
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Config.php', "vanityurl.config"
    );
    
    $aConfig = \Config::get('vanityurl.config');
  
    if (isset($aConfig['appBind'])) {
      foreach ($aConfig['appBind'] as $key => $value) {
        $this->app->bind($key, $value);
      }
    }

    $subscriber = new \Module\VanityUrl\Events\VanityUrlEvent();

    \Event::subscribe($subscriber);   

    //routes for modules should be included in the register method to preceed the base routes

    include __DIR__ . '/../../../routes.php';

  }

  public function boot()
  {

    //  load assests if any
    // \Atlantis\Helpers\Assets::registerScript('https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js', 10);

    /**
     * To register search provider
     *
     * $t = \App::make('Transport');
     *
     *  $t->setEventValue("search.providers", [  'search' => 'Module\VanityUrl\Models\Search' , 'weight' => 10] );
     */

     
    $themeModViewPath = \Atlantis\Helpers\Themes\ThemeTools::getFullThemePath() . '/modules/vanityurl/views/';

    if (is_dir($themeModViewPath)) {
      $this->loadViewsFrom($themeModViewPath, 'vanityurl');
    } else {
      $this->loadViewsFrom(__DIR__ . '/../Views/', 'vanityurl');
    }
    
    $this->loadViewsFrom(__DIR__ . '/../Views/', 'vanityurl-admin');
    
    /**
    *  call this with trans('vanityurl::file.key');
    */      
    //$this->loadTranslationsFrom(__DIR__ . '/../Languages', "vanityurl");

  }

}
