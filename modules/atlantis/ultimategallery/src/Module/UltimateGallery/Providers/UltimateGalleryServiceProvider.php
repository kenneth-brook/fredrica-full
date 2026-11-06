<?php

namespace Module\UltimateGallery\Providers;

/*
 * Provider: UltimateGallery
 * @Atlantis CMS
 * v 1.0
 */

class UltimateGalleryServiceProvider extends \Illuminate\Support\ServiceProvider
{

  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
      \Module\UltimateGallery\Commands\UltimateGalleryCommand::class
  ];

  public function register()
  {

    /** Register artisan commands * */
    //$this->commands($this->commands);
  
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Setup.php', "ultimategallery.setup"
    );
    
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Config.php', "ultimategallery.config"
    );
    
    $aConfig = \Config::get('ultimategallery.config');
  
    if (isset($aConfig['appBind'])) {
      foreach ($aConfig['appBind'] as $key => $value) {
        $this->app->bind($key, $value);
      }
    }

    $subscriber = new \Module\UltimateGallery\Events\UltimateGalleryEvent();

    \Event::subscribe($subscriber);   

    //routes for modules should be included in the register method to preceed the base routes

    include __DIR__ . '/../../../routes.php';
    
    /**
     * register widgets
     */
    //\Atlantis\Widgets\Register::set(\Module\UltimateGallery\Widgets\UltimateGalleryWidget::class, \Config::get('ultimategallery.setup'));

  }

  public function boot()
  {

    //  load assests if any
    // \Atlantis\Helpers\Assets::registerScript('https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js', 10);

    /**
     * To register search and sitemap provider
     *
     * $t = \App::make('Transport');
     *
     *  $t->setEventValue("search.providers", [  'search' => 'Module\UltimateGallery\Models\Search' , 'weight' => 10] );
     *  $t->setEventValue("sitemap.providers", [  'sitemap' => 'Module\UltimateGallery\Models\Sitemap' , 'weight' => 10] );
     */

     
    $themeModViewPath = \Atlantis\Helpers\Themes\ThemeTools::getFullThemePath() . '/modules/ultimategallery/views/';

    if (is_dir($themeModViewPath)) {
      $this->loadViewsFrom($themeModViewPath, 'ultimategallery');
    } else {
      $this->loadViewsFrom(__DIR__ . '/../Views/', 'ultimategallery');
    }
    
    $this->loadViewsFrom(__DIR__ . '/../Views/', 'ultimategallery-admin');
    
    /**
    *  call this with trans('ultimategallery::file.key');
    */      
    //$this->loadTranslationsFrom(__DIR__ . '/../Languages', "ultimategallery");

  }

}
