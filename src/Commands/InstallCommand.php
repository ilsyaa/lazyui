<?php

namespace LazyUI\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazy:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install LazyUI to your application.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->publishAssets();

        $this->newLine();
        $this->components->success('LazyUI installed successfully');
        $this->components->bulletList([
            'resources/css/lazy',
            'resources/js/lazy',
            'public/assets/lazy',
        ]);
        $this->newLine();
    }

    protected function publishAssets(): void
    {
        $this->createDirectories();
        $this->copyFiles();
        $this->mergePackageJson();
        $this->requireComponents();
    }

    protected function createDirectories(): void
    {
        collect([
            resource_path('css/lazy'),
            resource_path('js/lazy'),
            public_path('assets/lazy'),
        ])->each(function ($path) {
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true);
            }
        });
    }

    protected function copyFiles(): void
    {
        File::copyDirectory(__DIR__ . "/../../resources/css", resource_path('css/lazy'));
        File::copy(__DIR__ . "/../../resources/js/app.js", resource_path('js/lazy/app.js'));
        if(!File::isDirectory(resource_path('js/lazy/plugins'))) {
            File::makeDirectory(resource_path('js/lazy/plugins'), 0777, true);
        }
        File::copyDirectory(__DIR__ . "/../../public/assets-lazy", public_path('assets/lazy'));
    }

    protected function mergePackageJson(): void
    {
        $packageRoot = File::get(base_path('package.json'));
        $packageLazy = File::get(__DIR__ . '/../../package.json');

        $package = json_decode($packageRoot, true);
        $lazy = json_decode($packageLazy, true);

        $package = $this->arrayMergeRecursiveDistinct($package, $lazy);

        File::put(
            base_path('package.json'),
            json_encode($package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }


    protected function arrayMergeRecursiveDistinct(array &$array1, array &$array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    protected function requireComponents(): void
    {
        $this->call('lazy:component', ['name' => 'button', '--silent' => true]);
        $this->call('lazy:component', ['name' => 'dropdown', '--silent' => true]);
        $this->call('lazy:component', ['name' => 'nav', '--silent' => true]);
        $this->call('lazy:component', ['name' => 'sheet', '--silent' => true]);
        $this->call('lazy:component', ['name' => 'switch', '--silent' => true]);
    }
}
