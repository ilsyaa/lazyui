<?php

namespace LazyUI\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\text;

class LayoutCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazy:layout
        {path : The path of your layout.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate layout panel.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $path = str()->slug($this->argument('path'));

        $layout = $this->generateLayouts($path);
        $route = $this->generateRoute($path);

        $this->newLine();
        $list = [];
        if ($layout) {
            $this->components->success("Layout created");
            $list[] = "resources/views/{$path}";
        } else {
            $this->components->info("Layout skipped.");
        }

        if ($route) {
            $this->components->success("Route file created and linked in routes/web.php");
            $list[] = "routes/{$path}.php";
        } else {
            $this->components->info("Route skipped.");
        }
        if(count($list) > 0) {
            $this->components->bulletList($list);
        }
        $this->newLine();
    }

    protected function generateLayouts(string $path): bool
    {
        $sourceDir = __DIR__ . '/../../resources/views/layouts';
        $destDir = resource_path("views/{$path}/layouts");

        // Check if folder already exists
        if (File::isDirectory($destDir)) {
            if (! $this->confirm("The layout '{$path}' already exists. Overwrite?", false)) {
                return false;
            }
        } else {
            File::makeDirectory($destDir, 0777, true, true);
        }

        // Copy and replace placeholder
        foreach (File::glob("{$sourceDir}/*.blade.php") as $file) {
            $filename = basename($file);
            $content = str_replace('[path]', $path, File::get($file));

            $destination = $filename === 'example.blade.php'
                ? resource_path("views/{$path}/index.blade.php")
                : "{$destDir}/{$filename}";

            File::put($destination, $content);
        }

        return true;
    }

    protected function generateRoute(string $path): bool
    {
        if (! $this->confirm("Create a new route file for '{$path}'? (routes/{$path}.php)", false)) {
            return false;
        }

        $routePath = base_path("routes/{$path}.php");
        $stubRouteContent = str_replace('[path]', $path, File::get(__DIR__ . '/../../routes/routes.php'));

        if (File::exists($routePath)) {
            if (! $this->confirm("The route file '{$routePath}' already exists. Overwrite?", false)) {
                return false;
            }
        }

        File::put($routePath, $stubRouteContent);

        // Append to web.php
        $webContent = str_replace('[path]', $path, File::get(__DIR__ . '/../../routes/web.php'));
        File::append(base_path('routes/web.php'), $webContent);

        return true;
    }

    protected function promptForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if ($this->didReceiveOptions($input)) {
            return;
        }

        if (! $input->getArgument('path')) {
            $path = text('What is the name of the layout path you want to generate?', 'admin');
            $input->setArgument('path', $path);
        }
    }
}
