<?php

namespace Modules\Demowebinar\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Demowebinar\Entities\Demowebinar;
use Modules\Demowebinar\Entities\Survey;
u

class DemowebinarServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        //Service binding
        $this->webinarServices();
        $this->scheduleCommands();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(CommandsServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('demowebinar.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'demowebinar'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/demowebinar');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/demowebinar';
        }, \Config::get('view.paths')), [$sourcePath]), 'demowebinar');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/demowebinar');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'demowebinar');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'demowebinar');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Binding dependancies for webinar services.
     *
     * @param void
     * @return void
     */
    public function webinarServices()
    {

        $this->app->bind('Modules\Demowebinar\Repositories\Webinar\SurveyInterface', function ($app) {
            return new SurveyRepository(new Survey());
        });

        $this->app->bind('SurveyService', function ($app) {
            return new SurveyService(
                // Injecting survey dependency
                $app->make('Modules\Demowebinar\Repositories\Webinar\SurveyInterface')
            );
        });

    }

    /**
     * Scheduling Commands for webinar services.
     *
     * @param void
     * @return void
     */
    public function scheduleCommands()
    {

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('demowebinar:webinar_analytics')->everyFiveMinutes();
        });
    }
}
