<?php

namespace SiDev\LaravelLayered\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use SiDev\LaravelLayered\Traits\Commands\SortableImport;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class RepositoryMakeCommand extends GeneratorCommand
{
    use SortableImport;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * The default parent fully qualified class name.
     *
     * @var string
     */
    protected $defaultParent = 'App\Repositories\Eloquent\EloquentRepository';

    /**
     * The contract to implement if it passed as an option.
     *
     * @var string
     */
    protected $contract = '';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->option('contract')) {
            $this->createContract();
        }

        $this->createClass();

        $this->info($this->type.' created successfully.');
    }

    /**
     * Create the class by the given parameters.
     */
    public function createClass()
    {
        $name = 'Repositories/'.$this->argument('name');

        $params = ['name' => $name];

        if ($this->option('contract')) {
            $params = array_merge($params, ['--contract' => $this->contract]);
        }

        if ($model = $this->option('model')) {
            $params = array_merge($params, [
                '--dependency' => $model,
                '--dependencyName' => 'model',
            ]);
        }

        $params = array_merge($params, ['--extends' => $this->getExtendsName()]);

        $this->call('make:class', $params);
    }

    /**
     * Get the extends' fully qualified class name.
     *
     * @return string
     */
    protected function getExtendsName()
    {
        return $this->option('extends')
            ? $this->qualifyClass($this->option('extends'))
            : $this->defaultParent;
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

    /**
     * Create the contract for the repository.
     *
     * @return void
     */
    protected function createContract()
    {
        $this->contract = 'Repositories\\'.Str::studly(class_basename($this->argument('name'))).'Interface';

        $this->call('make:contract', [
            'name' => $this->contract,
            '--extends' => 'Repositories/RepositoryInterface',
        ]);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['extends', 'e', InputOption::VALUE_REQUIRED, 'The name of the extended class'],
            ['model', 'm', InputOption::VALUE_REQUIRED, 'The name of the injected model'],
            ['contract', 'c', InputOption::VALUE_NONE, 'Create a new contract for the repository class'],
        ];
    }
}
