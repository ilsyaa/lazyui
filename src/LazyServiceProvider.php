<?php

namespace LazyUI;

use Illuminate\Support\ServiceProvider;
use LazyUI\Commands\ComponentCommand;
use LazyUI\Commands\InstallCommand;
use LazyUI\Commands\LayoutCommand;

class LazyServiceProvider extends ServiceProvider {

    public function register(): void {

    }

    public function boot(): void {
        $this->commands([
            LayoutCommand::class,
            InstallCommand::class,
            ComponentCommand::class
        ]);
    }
}
