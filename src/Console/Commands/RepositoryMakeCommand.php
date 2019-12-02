<?php

namespace SiDev\LaravelLayered\Console\Commands;

use Illuminate\Support\Str;
use SiDev\LaravelLayered\Traits\Commands\SortableImport;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
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
        $stub = __DIR__.'/../stubs/repositories/repository';

        if ($this->option('model')) {
            $stub .= '.model';
        }

        if ($this->option('contract')) {
            $stub .= '.contract';
        }

        return $stub.'.stub';
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
            return $this->buildClassWithDependencyAndContract($name);
        }

        if ($this->option('model')) {
            return $this->buildClassWithDependency($name);
        }

        if ($this->option('contract')) {
            return $this->buildClassWithContract($name);
        }

        return parent::buildClass($name);
    }

    /**
     * Build the class with contract and injected dependency.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassWithDependencyAndContract(string $name)
    {
        $this->createContract();

        return str_replace(
            [
                'NamespaceDummyModel',
                'DummyModel',
                'NamespaceDummyRepositoryInterface',
                'DummyRepositoryInterface',
            ],
            [
                $namespaceModel = $this->getDependencyNamespace(),
                class_basename($namespaceModel),
                $namespaceContract = $this->rootNamespace().'Contracts\\'.$this->contract,
                class_basename($namespaceContract),
            ],
            parent::buildClass($name)
        );
    }

    /**
     * Build the class with injected dependency.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassWithDependency(string $name)
    {
        return str_replace(
            ['NamespaceDummyModel', 'DummyModel'],
            [$namespaceModel = $this->getDependencyNamespace(), class_basename($namespaceModel)],
            parent::buildClass($name)
        );
    }

    /**
     * Build the class that implements contract.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassWithContract(string $name)
    {
        $this->createContract();
        $namespaceContract = $this->rootNamespace().'Contracts\\'.$this->contract;

        return str_replace(
            ['NamespaceDummyRepositoryInterface', 'DummyRepositoryInterface'],
            [$namespaceContract, class_basename($namespaceContract)],
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
        $this->contract = 'Repositories\\'.Str::studly(class_basename($this->argument('name'))).'Interface';

        $this->call('make:contract', [
            'name' => $this->contract,
            '--extends' => 'Repositories/RepositoryInterface',
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
        return $rootNamespace.'\Repositories';
    }

    /**
     * Get the dependency namespace.
     *
     * @return mixed|string
     */
    protected function getDependencyNamespace()
    {
        return $this->option('model')
            ? str_replace('/', '\\', trim($this->rootNamespace(), '\\').'\\'.$this->option('model'))
            : 'Illuminate\Database\Eloquent\Model';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The name of the model'],
            ['contract', 'c', InputOption::VALUE_NONE, 'Create a new contract for the repository class'],
        ];
    }
}
