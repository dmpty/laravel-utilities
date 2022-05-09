<?php

namespace Dmpty\LaravelUtilities\Error\Providers;

use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Dmpty\LaravelUtilities\Error\Handler\ExceptionHandler as Handler;
use Dmpty\LaravelUtilities\Error\Handler\LogHandler;

class ErrorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->app->runningInConsole()) {
            app()->singleton(
                ExceptionHandler::class,
                Handler::class
            );
            /** @var Handler $handler */
            $handler = app(ExceptionHandler::class);
            $handler->reportable(function (Exception $e) {
                return LogHandler::handle($e);
            });
            $handler->renderable(function (Exception $e) {
                return error($e);
            });
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
