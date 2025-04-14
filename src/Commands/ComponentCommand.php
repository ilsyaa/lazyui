<?php

namespace LazyUI\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\text;

class ComponentCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazy:component
        {name : The name of the component.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish LazyUI components';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = str()->slug($this->argument('name'));

        if ($name == 'all') {
            if($this->confirm('Are you sure you want to publish all components?')) {
                $dirComponents = __DIR__ . '/../../resources/views/components';
                if (File::isDirectory($dirComponents)) {
                    if(!File::isDirectory(resource_path('views/components'))) {
                        File::makeDirectory(resource_path('views/components'), 0777, true);
                    }
                    if(!File::isDirectory(resource_path('js/lazy/plugins'))) {
                        File::makeDirectory(resource_path('js/lazy/plugins'), 0777, true);
                    }
                    File::copyDirectory(__DIR__ . '/../../resources/js/plugins', resource_path('js/lazy/plugins'));
                    File::copyDirectory($dirComponents, resource_path('views/components'));
                    $this->components->success("All components published.");
                    $this->components->bulletList(["resources/views/components"]);
                    $this->newLine();

                    return;
                }
            }
        }

        $dirComponents = __DIR__ . '/../../resources/views/components/' . $name;
        if (File::isDirectory($dirComponents)) {
            if(!File::isDirectory(resource_path('views/components'))) {
                File::makeDirectory(resource_path('views/components'), 0777, true);
            }
            File::copyDirectory($dirComponents, resource_path('views/components/' . $name));
            if(File::exists(__DIR__ . '/../../resources/js/plugins/' . $name . '.js')) {
                if(!File::isDirectory(resource_path('js/lazy/plugins'))) {
                    File::makeDirectory(resource_path('js/lazy/plugins'), 0777, true);
                }
                File::copy(__DIR__ . '/../../resources/js/plugins/' . $name . '.js', resource_path('js/lazy/plugins/' . $name . '.js'));
            }
            $this->components->success("Component '{$name}' published.");
            $this->components->bulletList(["resources/views/components/{$name}"]);
            $this->newLine();
        } else {
            $this->newLine();
            $this->components->warn("Component '{$name}' not found.");
        }
    }

    protected function promptForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if ($this->didReceiveOptions($input)) {
            return;
        }

        if (! $input->getArgument('name')) {
            $name = text('What is the name of the component you want to publish?', 'button');
            $input->setArgument('name', $name);
        }
    }
}
