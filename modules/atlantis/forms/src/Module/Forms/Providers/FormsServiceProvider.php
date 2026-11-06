<?php

namespace Module\Forms\Providers;

/*
 * Provider: Forms
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Support\ServiceProvider;
use Form;

class FormsServiceProvider extends ServiceProvider {

    public function register() {

        $this->mergeConfigFrom(
                __DIR__ . '/../Setup/Setup.php', "forms.setup"
        );

        $this->mergeConfigFrom(
                __DIR__ . '/../Setup/Config.php', "forms.config"
        );

        $aConfig = \Config::get('forms.config');

        if (isset($aConfig['appBind'])) {
            foreach ($aConfig['appBind'] as $key => $value) {
                $this->app->bind($key, $value);
            }
        }

        $t = \App::make('Transport');

        /** When form is submitted * */
        $t->registerEvent('form.submitted');

        \Illuminate\Support\Facades\Validator::extend('valid_checkbox', 'Module\Forms\Models\Repositories\FormsRepository@validCheckbox');
        \Illuminate\Support\Facades\Validator::extend('valid_select', 'Module\Forms\Models\Repositories\FormsRepository@validSelect');
        \Illuminate\Support\Facades\Validator::extend('valid_radio', 'Module\Forms\Models\Repositories\FormsRepository@validRadio');
        \Illuminate\Support\Facades\Validator::extend('zip_us_ca', 'Module\Forms\Models\Repositories\FormsRepository@validZipUsCa');
        \Illuminate\Support\Facades\Validator::extend('phone', 'Module\Forms\Models\Repositories\FormsRepository@validPhone');

        //routes for modules should be included in the register method to preceed the base routes
        include __DIR__ . '/../../../routes.php';

    }

    public function boot() {

        Form::macro('selectWithDisabled', function($name, $options = [], $selected = null, $attributes = []) {
            $html = '<select name="' . $name . '"';
            foreach ($attributes as $attribute => $value) {
                $html .= ' ' . $attribute . '="' . $value . '"';
            }
            $html .= '>';
     
            $selected = isset(\Session::get("_old_input")[$name]) ? \Session::get("_old_input")[$name] : $selected;
            foreach ($options as $value => $text) {

                $parsedText = str_replace("::disabled", "", $text);
                $html .= '<option value="' . $value . '"' .
                        ($value == $selected ? ' selected="selected"' : '') .
                        (strpos($text, "::disabled") !== false ? ' disabled="disabled"' : '') . '>' . $parsedText
                        . '</option>';
            }

            $html .= '</select>';

            return $html;
        });



        $themeModViewPath = \Atlantis\Helpers\Themes\ThemeTools::getFullThemePath() . '/modules/forms/views/';

        if (is_dir($themeModViewPath)) {
            $this->loadViewsFrom($themeModViewPath, 'forms');
        } else {
            $this->loadViewsFrom(__DIR__ . '/../Views/', 'forms');
        }

        $this->loadViewsFrom(__DIR__ . '/../Views/', 'forms-admin');

        $this->loadTranslationsFrom(__DIR__ . '/../Languages', "forms");

    }

}
