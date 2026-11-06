<?php

namespace Module\CustomFields\Providers;

/*
 * Provider: CustomFields
 * @Atlantis CMS
 * v 1.0
 */

class CustomFieldsServiceProvider extends \Illuminate\Support\ServiceProvider
{

  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
      \Module\CustomFields\Commands\CustomFieldsCommand::class
  ];

  public function register()
  {

    /** Register artisan commands * */
    //$this->commands($this->commands);
  
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Setup.php', "customfields.setup"
    );
    
    $this->mergeConfigFrom(
            __DIR__ . '/../Setup/Config.php', "customfields.config"
    );
    
    $aConfig = \Config::get('customfields.config');
  
    if (isset($aConfig['appBind'])) {
      foreach ($aConfig['appBind'] as $key => $value) {
        $this->app->bind($key, $value);
      }
    }

    $subscriber = new \Module\CustomFields\Events\CustomFieldsEvent();

    \Event::subscribe($subscriber);   

    //routes for modules should be included in the register method to preceed the base routes

    include __DIR__ . '/../../../routes.php';
    
    /**
     * register widgets
     */
    //\Atlantis\Widgets\Register::set(\Module\CustomFields\Widgets\CustomFieldsWidget::class, \Config::get('customfields.setup'));

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
     *  $t->setEventValue("search.providers", [  'search' => 'Module\CustomFields\Models\Search' , 'weight' => 10] );
     *  $t->setEventValue("sitemap.providers", [  'sitemap' => 'Module\CustomFields\Models\Sitemap' , 'weight' => 10] );
     */

     
    $themeModViewPath = \Atlantis\Helpers\Themes\ThemeTools::getFullThemePath() . '/modules/customfields/views/';

    if (is_dir($themeModViewPath)) {
      $this->loadViewsFrom($themeModViewPath, 'customfields');
    } else {
      $this->loadViewsFrom(__DIR__ . '/../Views/', 'customfields');
    }
    
    $this->loadViewsFrom(__DIR__ . '/../Views/', 'customfields-admin');
    
    /**
    *  call this with trans('customfields::file.key');
    */      
    $this->loadTranslationsFrom(__DIR__ . '/../Languages', "customfields");

  }

}
