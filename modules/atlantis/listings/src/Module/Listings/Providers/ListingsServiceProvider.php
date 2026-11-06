<?php

namespace Module\Listings\Providers;

/*
 * Provider: Listings
 * @Atlantis CMS
 * v 1.0
 */

class ListingsServiceProvider extends \Illuminate\Support\ServiceProvider
{

  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
      \Module\Listings\Commands\ListingsCommand::class
  ];

  public function register()
  {

    /** Register artisan commands * */
    //$this->commands($this->commands);
  
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Setup.php', "listings.setup"
    );
    
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Config.php', "listings.config"
    );
    
    $aConfig = \Config::get('listings.config');
  
    if (isset($aConfig['appBind'])) {
      foreach ($aConfig['appBind'] as $key => $value) {
        $this->app->bind($key, $value);
      }
    }

    $subscriber = new \Module\Listings\Events\ListingsEvent();

    \Event::subscribe($subscriber);   

    //routes for modules should be included in the register method to preceed the base routes

    include __DIR__ . '/../../../routes.php';
    
    /**
     * register widgets
     */
    //\Atlantis\Widgets\Register::set(\Module\Listings\Widgets\ListingsWidget::class, \Config::get('listings.setup'));

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
     *  $t->setEventValue("search.providers", [  'search' => 'Module\Listings\Models\Search' , 'weight' => 10] );
     */
    
       \App::make('Transport')->setEventValue("sitemap.providers", [  'sitemap' => 'Module\Listings\Models\Sitemap' , 'weight' => 10] );
     

     
    $themeModViewPath = \Atlantis\Helpers\Themes\ThemeTools::getFullThemePath() . '/modules/listings/views/';

    if (is_dir($themeModViewPath)) {
      $this->loadViewsFrom($themeModViewPath, 'listings');
    } else {
      $this->loadViewsFrom(__DIR__ . '/../Views/', 'listings');
    }
    
    $this->loadViewsFrom(__DIR__ . '/../Views/', 'listings-admin');
    
    /**
    *  call this with trans('listings::file.key');
    */      
    //$this->loadTranslationsFrom(__DIR__ . '/../Languages', "listings");

  }

}
