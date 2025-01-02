<?php

namespace FurisonTech\LaraveditorJS\Providers;

use FurisonTech\LaraveditorJS\Console\Commands\MakeCustomBlockRulesSupplier;
use FurisonTech\LaraveditorJS\Console\Commands\MakeEditorJSFormRequest;
use Illuminate\Support\ServiceProvider;

/**
 * @codeCoverageIgnore
 */
class LaraveditorJSServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registering the command for generating EditorJS form requests
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeEditorJSFormRequest::class,
                MakeCustomBlockRulesSupplier::class
            ]);
        }
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'laraveditorjs');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../resources/views' => $this->app->resourcePath('views/vendor/laraveditorjs'),
            ], 'laraveditorjs-views');
        }
    }
}