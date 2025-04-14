<?php

namespace Kodingin\LazyUI;

use Illuminate\Support\ServiceProvider;
use Kodingin\LazyUI\Commands\ComponentCommand;
use Kodingin\LazyUI\Commands\InstallCommand;
use Kodingin\LazyUI\Commands\LayoutCommand;

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
