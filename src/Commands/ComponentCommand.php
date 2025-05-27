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
        {name : The name of the component.}
        {--silent : Silent mode.}';

    protected $availableComponents  = [
        'alert',
        'accordion',
        'avatar',
        'autocomplete',
        'badge',
        'breadcrumb',
        'button',
        'card',
        'checkbox',
        'dialog',
        'dropdown',
        'filepond',
        'form',
        'input',
        'label',
        'nav',
        'radio',
        'select',
        'sheet',
        'switch',
        'tabs',
        'textarea',
        'tinymce',
        'toast',
        'tooltip',
    ];

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
        $silent = $this->option('silent');

        if($name == 'all') {
            if(!$this->confirm('Are you sure you want to publish all components?')) return;
            foreach ($this->availableComponents as $component) {
                $this->call('lazy:component', ['name' => $component, '--silent' => true]);
            }
            $this->components->success("All components published.");
            $this->components->bulletList($this->availableComponents);
            $this->newLine();
            return;
        }

        if (! in_array(strtolower($name), array_map('strtolower', $this->availableComponents ))) {
            $this->components->error("Component '{$name}' not found.");
            $this->components->bulletList($this->availableComponents);
            $this->newLine();
            return;
        }

        $component = $this->publishComponent($name);
        $alpine = $this->publishAlpine($name);
        $vanilla = $this->publishVanilla($name);

        if (!$silent) {
            $this->components->success("Component '{$name}' published.");
            $include = [];
            if ($component) {
                $include[] = "resources/views/components/{$name}";
            }
            if ($alpine) {
                $include[] = "resources/js/plugins/alpine/{$name}.js";
            }
            if ($vanilla) {
                $include[] = "resources/js/plugins/vanilla/{$name}.js";
            }
            $this->components->bulletList($include);
            $this->newLine();
        }
    }

    protected function publishComponent($name) {
        $dirComponents = __DIR__ . '/../../resources/views/components/' . $name;
        if (File::isDirectory($dirComponents)) {
            if(!File::isDirectory(resource_path('views/components'))) {
                File::makeDirectory(resource_path('views/components'), 0777, true);
            }
            File::copyDirectory($dirComponents, resource_path('views/components/' . $name));
            return true;
        } else {
            return false;
        }
    }

    protected function publishAlpine($name) {
        $fileAlpine = __DIR__ . '/../../resources/js/plugins/alpine/' . $name . '.js';
        if (File::isFile($fileAlpine)) {
            if(!File::isDirectory(resource_path('js/lazy/plugins/alpine'))) {
                File::makeDirectory(resource_path('js/lazy/plugins/alpine'), 0777, true);
            }
            File::copy($fileAlpine, resource_path('js/lazy/plugins/alpine/' . $name . '.js'));
            return true;
        } else {
            return false;
        }
    }
    protected function publishVanilla($name) {
        $fileVanilla = __DIR__ . '/../../resources/js/plugins/vanilla/' . $name . '.js';
        if (File::isFile($fileVanilla)) {
            if(!File::isDirectory(resource_path('js/lazy/plugins/vanilla'))) {
                File::makeDirectory(resource_path('js/lazy/plugins/vanilla'), 0777, true);
            }
            File::copy($fileVanilla, resource_path('js/lazy/plugins/vanilla/' . $name . '.js'));
            return true;
        } else {
            return false;
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
