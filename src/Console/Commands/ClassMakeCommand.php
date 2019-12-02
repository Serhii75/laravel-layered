<?php

namespace SiDev\LaravelLayered\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use SiDev\LaravelLayered\Traits\Commands\SortableImport;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ClassMakeCommand extends GeneratorCommand
{
    use SortableImport;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../stubs/classes/class'.$this->buildFromOption().'.stub';
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
        $method = 'buildClass'.Str::studly(str_replace('.', '_', $this->buildFromOption()));

        return $method === 'buildClass'
            ? parent::buildClass($name)
            : $this->{$method}($name);
    }

    /**
     * Build the class that: 1) extends another class, 2) has injected dependency, 3) implements contract.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassExtendsDependencyContract(string $name)
    {
        return str_replace(
            [
                'NamespaceDummyExtends',
                'DummyExtends',
                'NamespaceDummyDependency',
                'DummyDependency',
                'DummyVariableName',
                'NamespaceDummyContract',
                'DummyContract',
            ],
            [
                $namespaceExtends = $this->qualifyClass($this->option('extends')),
                class_basename($namespaceExtends),
                $namespaceDependency = $this->qualifyClass($this->option('dependency')),
                $dependencyClass = class_basename($namespaceDependency),
                $this->getDependencyName($dependencyClass),
                $namespaceContract = $this->getContractNamespace(),
                class_basename($namespaceContract),
            ],
            parent::buildClass($name)
        );
    }

    /**
     * Build the class that extends another class and inject dependency.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassExtendsDependency(string $name)
    {
        return str_replace(
            [
                'NamespaceDummyExtends',
                'DummyExtends',
                'NamespaceDummyDependency',
                'DummyDependency',
                'DummyVariableName',
            ],
            [
                $namespaceExtends = $this->qualifyClass($this->option('extends')),
                class_basename($namespaceExtends),
                $namespaceDependency = $this->qualifyClass($this->option('dependency')),
                $dependencyClass = class_basename($namespaceDependency),
                $this->getDependencyName($dependencyClass),
            ],
            parent::buildClass($name)
        );
    }

    /**
     * Build the class that extends another class nd implements the specified contract.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassExtendsContract(string $name)
    {
        return str_replace(
            [
                'NamespaceDummyExtends',
                'DummyExtends',
                'NamespaceDummyContract',
                'DummyContract',
            ],
            [
                $namespaceExtends = $this->qualifyClass($this->option('extends')),
                class_basename($namespaceExtends),
                $namespaceContract = $this->getContractNamespace(),
                class_basename($namespaceContract),
            ],
            parent::buildClass($name)
        );
    }

    /**
     * Build the class with contract and injected dependency.
     *
     * @param $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassDependencyContract($name)
    {
        return str_replace(
            [
                'NamespaceDummyDependency',
                'DummyDependency',
                'DummyVariableName',
                'NamespaceDummyContract',
                'DummyContract',
            ],
            [
                $namespaceDependency = $this->qualifyClass($this->option('dependency')),
                $dependencyClass = class_basename($namespaceDependency),
                $this->getDependencyName($dependencyClass),
                $namespaceContract = $this->getContractNamespace(),
                class_basename($namespaceContract),
            ],
            parent::buildClass($name)
        );
    }

    /**
     * Build the class that extends another class.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildClassExtends(string $name)
    {
        $namespaceExtends = $this->qualifyClass($this->option('extends'));

        return str_replace(
            ['NamespaceDummyExtends', 'DummyExtends'],
            [$namespaceExtends, class_basename($namespaceExtends)],
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
    protected function buildClassDependency(string $name)
    {
        $namespaceDependency = $this->qualifyClass($this->option('dependency'));
        $dependencyClass = class_basename($namespaceDependency);

        return str_replace(
            ['NamespaceDummyDependency', 'DummyDependency', 'DummyVariableName'],
            [$namespaceDependency, $dependencyClass, $this->getDependencyName($dependencyClass)],
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
    protected function buildClassContract(string $name)
    {
        $namespaceContract = $this->getContractNamespace();

        return str_replace(
            ['NamespaceDummyContract', 'DummyContract'],
            [$namespaceContract, class_basename($namespaceContract)],
            parent::buildClass($name)
        );
    }

    /**
     * Get the contract namespace.
     *
     * @return string
     */
    protected function getContractNamespace()
    {
        $namespace = $this->option('contract')
            ? $this->qualifyClass('Contracts\\'.$this->option('contract'))
            : $this->qualifyClass('Contracts\\'.$this->argument('name').'Interface');

        if (! interface_exists($namespace)) {
            $this->createContract($namespace);
        }

        return $namespace;
    }

    /**
     * Get the dependency variable name.
     *
     * @param $dependencyClass
     *
     * @return string
     */
    protected function getDependencyName($dependencyClass)
    {
        if ($this->option('dependencyName')) {
            return $this->option('dependencyName');
        }

        return Str::camel(Str::before($dependencyClass, 'Interface'));
    }

    /**
     * Create the contract by given namaspace.
     *
     * @param string $namespace
     */
    protected function createContract(string $namespace)
    {
        $this->call('make:contract', [
            'name' => $namespace,
        ]);
    }

    /**
     * Build pivot string from passed options.
     *
     * @return string
     */
    protected function buildFromOption()
    {
        $result = '';

        if ($this->option('extends')) {
            $result .= '.extends';
        }

        if ($this->option('dependency')) {
            $result .= '.dependency';
        }

        if (false !== $this->option('contract')) {
            $result .= '.contract';
        }

        return $result;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['extends', 'e', InputOption::VALUE_REQUIRED, 'The name of the class that being extended'],
            ['contract', 'c', InputOption::VALUE_OPTIONAL, 'Create a new contract for the class', false],
            ['dependency', 'd', InputOption::VALUE_REQUIRED, 'The name of the injected dependency'],
            ['dependencyName', null, InputOption::VALUE_REQUIRED, 'The name of the injected dependency variable'],
        ];
    }
}
