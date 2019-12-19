<?php

namespace SiDev\LaravelLayered\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class LayeredBunchMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:layered-bunch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a model layered bunch';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model Layered Bunch';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->createModel();
        $this->createRepository();
        $this->createService();

        $this->info($this->type.' created successfully.');
    }

    protected function createModel()
    {
        $model = Str::studly(class_basename($this->argument('name')));

        $this->call('make:model', [
            'name'        => $model,
            '--migration' => null,
            '--factory'   => null,
        ]);
    }

    protected function createRepository()
    {
        $model = Str::studly(class_basename($this->argument('name')));

        $this->call('make:repository', [
            'name'       => "{$model}Repository",
            '--model'    => $model,
            '--contract' => null,
            '--extends'  => null,
        ]);
    }

    protected function createService()
    {
        $model = Str::studly(class_basename($this->argument('name')));

        $this->call('make:service', [
            'name'         => "{$model}Service",
            '--repository' => "{$model}RepositoryInterface",
            '--contract'   => null,
            '--extends'    => null,
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return '';
    }
}
