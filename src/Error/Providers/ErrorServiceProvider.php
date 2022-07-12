<?php

namespace Dmpty\LaravelUtilities\Error\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Dmpty\LaravelUtilities\Error\Handler\ExceptionHandler as Handler;
use Dmpty\LaravelUtilities\Error\Handler\LogHandler;
use Throwable;

class ErrorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        if (!$this->app->runningInConsole()) {
            app()->singleton(
                ExceptionHandler::class,
                Handler::class
            );
            /** @var Handler $handler */
            $handler = app(ExceptionHandler::class);
            $handler->reportable(function (Throwable $e) {
                return LogHandler::handle($e);
            });
            $handler->renderable(function (Throwable $e) {
                return error($e);
            });
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
