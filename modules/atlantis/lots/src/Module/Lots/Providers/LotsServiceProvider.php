<?php

namespace Module\Lots\Providers;

/*
 * Provider: Lots
 * @Atlantis CMS
 * v 1.0
 */

class LotsServiceProvider extends \Illuminate\Support\ServiceProvider
{

  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
      \Module\Lots\Commands\LotsCommand::class
  ];

  public function register()
  {

    /** Register artisan commands * */
    //$this->commands($this->commands);
  
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Setup.php', "lots.setup"
    );
    
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Config.php', "lots.config"
    );
    
    $aConfig = \Config::get('lots.config');
  
    if (isset($aConfig['appBind'])) {
      foreach ($aConfig['appBind'] as $key => $value) {
        $this->app->bind($key, $value);
      }
    }

    $subscriber = new \Module\Lots\Events\LotsEvent();

    \Event::subscribe($subscriber);   

    //routes for modules should be included in the register method to preceed the base routes

    include __DIR__ . '/../../../routes.php';
    
    /**
     * register widgets
     */
    //\Atlantis\Widgets\Register::set(\Module\Lots\Widgets\LotsWidget::class, \Config::get('lots.setup'));

  }

  public function boot()
  {

    //  load assests if any
    // \Atlantis\Helpers\Assets::registerScript('https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js', 10);

    
     // To register search and sitemap provider
     
      //$t = \App::make('Transport');
     
      //$t->setEventValue("search.providers", [  'search' => 'Module\Lots\Models\Search' , 'weight' => 10] );
      \App::make('Transport')->setEventValue("sitemap.providers", [  'sitemap-lots' => 'Module\Lots\Models\Sitemap' , 'weight' => 10] );
     

     
    $themeModViewPath = \Atlantis\Helpers\Themes\ThemeTools::getFullThemePath() . '/modules/lots/views/';

    if (is_dir($themeModViewPath)) {
      $this->loadViewsFrom($themeModViewPath, 'lots');
    } else {
      $this->loadViewsFrom(__DIR__ . '/../Views/', 'lots');
    }
    
    $this->loadViewsFrom(__DIR__ . '/../Views/', 'lots-admin');
    
    /**
    *  call this with trans('lots::file.key');
    */      
    //$this->loadTranslationsFrom(__DIR__ . '/../Languages', "lots");

  }

}
