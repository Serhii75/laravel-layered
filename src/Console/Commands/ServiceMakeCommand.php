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
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = __DIR__.'/../stubs/services/service';

        if ($this->option('model')) {
            $stub .= '.model';
        }

        if ($this->option('repository')) {
            $stub .= '.repository';
        }

        if ($this->option('contract')) {
            $stub .= '.contract';
        }

        return $stub.'.stub';
    }

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     *
     * @return bool|null
     */
    public function handle()
    {
        if ($this->option('model') && $this->option('repository')) {
            $this->error($this->type.' can not use model and repository options at the same time');

            return false;
        }

        return parent::handle();
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return string
     */
    protected function buildClass($name)
    {
        if ($this->option('model') && $this->option('contract')) {
            return $this->buildClassWithModelAndContract($name);
        }

        if ($this->option('model')) {
            return $this->buildClassWithModel($name);
        }

        if ($this->option('repository') && $this->option('contract')) {
            return $this->buildClassWithRepositoryAndContract($name);
        }

        if ($this->option('repository')) {
            return $this->buildClassWithRepository($name);
        }

        return parent::buildClass($name);
    }

    /**
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassWithModel(string $name)
    {
        return str_replace(
            ['NamespaceDummyModel', 'DummyModel'],
            [$namespaceModel = $this->getModelNamespace(), class_basename($namespaceModel)],
            parent::buildClass($name)
        );
    }

    /**
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassWithRepository(string $name)
    {
        return str_replace(
            ['NamespaceDummyRepository', 'DummyRepository'],
            [$namespaceRepo = $this->getRepositoryNamespace(), class_basename($namespaceRepo)],
            parent::buildClass($name)
        );
    }

    /**
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassWithModelAndContract(string $name)
    {
        $this->createContract();

        return str_replace(
            [
                'NamespaceDummyModel',
                'DummyModel',
                'NamespaceDummyServiceInterface',
                'DummyServiceInterface',
            ],
            [
                $namespaceModel = $this->getModelNamespace(),
                class_basename($namespaceModel),
                $namespaceContract = $this->rootNamespace().'Contracts\\'.$this->contract,
                class_basename($namespaceContract),
            ],
            parent::buildClass($name)
        );
    }

    /**
     * Build the class that implements interface.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassWithRepositoryAndContract(string $name)
    {
        $this->createContract();

        return str_replace(
            [
                'NamespaceDummyRepository',
                'DummyRepository',
                'NamespaceDummyServiceInterface',
                'DummyServiceInterface',
            ],
            [
                $namespaceRepo = $this->getRepositoryNamespace(),
                class_basename($namespaceRepo),
                $namespaceContract = $this->rootNamespace().'Contracts\\'.$this->contract,
                class_basename($namespaceContract),
            ],
            parent::buildClass($name)
        );
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
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Services';
    }

    /**
     * Get the model namespace.
     *
     * @return string
     */
    protected function getModelNamespace()
    {
        return $this->option('model')
            ? str_replace('/', '\\', trim($this->rootNamespace(), '\\').'\\'.$this->option('model'))
            : 'Illuminate\Database\Eloquent\Model';
    }

    /**
     * Get the repository namespace.
     *
     * @return string
     */
    protected function getRepositoryNamespace()
    {
        $prefix = Str::endsWith($name = $this->option('repository'), 'Interface')
            ? 'Contracts\Repositories\\'
            : 'Repositories\\';

        return str_replace('/', '\\', trim($this->rootNamespace(), '\\').'\\'.$prefix.$name);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The name of the injected model'],
            ['repository', 'r', InputOption::VALUE_OPTIONAL, 'The name of the injected repository'],
            ['contract', 'c', InputOption::VALUE_NONE, 'Create a new contract for the service class'],
        ];
    }
}
