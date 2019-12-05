<?php

namespace SiDev\LaravelLayered\Console\Commands;

use Illuminate\Support\Str;
use SiDev\LaravelLayered\Traits\Commands\SortableImport;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ServiceMakeCommand extends GeneratorCommand
{
    use SortableImport;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * The contract to implement if it passed as an option.
     *
     * @var string
     */
    protected $contract = '';

    /**
     * The default parent fully qualified class name.
     *
     * @var string
     */
    protected $defaultParent = 'App\Generic\AbstractService';

    /**
     * Execute the console command.
     *
     * @return bool|void
     */
    public function handle()
    {
        if ($this->option('model') && $this->option('repository')) {
            $this->error($this->type.' can not use model and repository options at the same time');

            return false;
        }

        if ($this->option('contract')) {
            $this->createContract();
        }

        $this->createClass();

        $this->info($this->type.' created successfully.');
    }

    /**
     * Create the class by the given parameters.
     */
    protected function createClass()
    {
        $name = 'Services/'.$this->argument('name');

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

        if ($repository = $this->option('repository')) {
            $params = array_merge($params, [
                '--dependency' => $this->getRepositoryNamespace($repository),
                '--dependencyName' => 'repository',
            ]);
        }

        if (false !== $this->option('extends')) {
            $params = array_merge($params, ['--extends' => $this->getExtendsName()]);
        }

        $this->call('make:class', $params);
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
     * Create the contract for the repository.
     *
     * @return void
     */
    protected function createContract()
    {
        $this->contract = 'Services\\'.Str::studly(class_basename($this->argument('name'))).'Interface';

        $this->call('make:contract', [
            'name' => $this->contract,
            '--extends' => 'Services/ServiceInterface',
        ]);
    }

    /**
     * Get the repository namespace.
     *
     * @return string
     */
    protected function getRepositoryNamespace(string $repository)
    {
        $prefix = Str::endsWith($repository, 'Interface')
            ? 'Contracts\Repositories\\'
            : 'Repositories\\';

        return str_replace(
            '/', '\\', trim($this->rootNamespace(), '\\').'\\'.$prefix.$repository
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['extends', 'e', InputOption::VALUE_OPTIONAL, 'The name of the extended class', false],
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The name of the injected model'],
            ['repository', 'r', InputOption::VALUE_OPTIONAL, 'The name of the injected repository'],
            ['contract', 'c', InputOption::VALUE_NONE, 'Create a new contract for the service class'],
        ];
    }
}
