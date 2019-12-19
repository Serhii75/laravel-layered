<?php

namespace SiDev\LaravelLayered\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use SiDev\LaravelLayered\Traits\Commands\SortableImport;
use Symfony\Component\Console\Input\InputOption;

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
        $stub = str_replace(
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
                $namespaceExtends = $this->getExtendsNamespace(),
                class_basename($namespaceExtends),
                $namespaceDependency = $this->qualifyClass($this->option('dependency')),
                $dependencyClass = class_basename($namespaceDependency),
                $this->getDependencyName($dependencyClass),
                $namespaceContract = $this->getContractNamespace(),
                class_basename($namespaceContract),
            ],
            parent::buildClass($name)
        );

        return $this->cleanBuiltClassFromUseless($name, $stub, [
            $namespaceExtends,
            $namespaceDependency,
            $namespaceDependency,
        ]);
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
        $stub = str_replace(
            [
                'NamespaceDummyExtends',
                'DummyExtends',
                'NamespaceDummyDependency',
                'DummyDependency',
                'DummyVariableName',
            ],
            [
                $namespaceExtends = $this->getExtendsNamespace(),
                class_basename($namespaceExtends),
                $namespaceDependency = $this->qualifyClass($this->option('dependency')),
                $dependencyClass = class_basename($namespaceDependency),
                $this->getDependencyName($dependencyClass),
            ],
            parent::buildClass($name)
        );

        return $this->cleanBuiltClassFromUseless($name, $stub, [
            $namespaceExtends,
            $namespaceDependency,
        ]);
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
        $stub = str_replace(
            [
                'NamespaceDummyExtends',
                'DummyExtends',
                'NamespaceDummyContract',
                'DummyContract',
            ],
            [
                $namespaceExtends = $this->getExtendsNamespace(),
                class_basename($namespaceExtends),
                $namespaceContract = $this->getContractNamespace(),
                class_basename($namespaceContract),
            ],
            parent::buildClass($name)
        );

        return $this->cleanBuiltClassFromUseless($name, $stub, [
            $namespaceExtends,
            $namespaceContract,
        ]);
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
        $stub = str_replace(
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

        return $this->cleanBuiltClassFromUseless($name, $stub, [
            $namespaceDependency,
            $namespaceContract,
        ]);
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
        $namespaceExtends = $this->getExtendsNamespace();

        $stub = str_replace(
            ['NamespaceDummyExtends', 'DummyExtends'],
            [$namespaceExtends, class_basename($namespaceExtends)],
            parent::buildClass($name)
        );

        return $this->cleanBuiltClassFromUseless($name, $stub, [$namespaceExtends]);
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

        $stub = str_replace(
            ['NamespaceDummyDependency', 'DummyDependency', 'DummyVariableName'],
            [$namespaceDependency, $dependencyClass, $this->getDependencyName($dependencyClass)],
            parent::buildClass($name)
        );

        return $this->cleanBuiltClassFromUseless($name, $stub, [$namespaceDependency]);
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

        $stub = str_replace(
            ['NamespaceDummyContract', 'DummyContract'],
            [$namespaceContract, class_basename($namespaceContract)],
            parent::buildClass($name)
        );

        return $this->cleanBuiltClassFromUseless($name, $stub, [$namespaceContract]);
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
     * Get extends namespace.
     *
     * @return string
     */
    protected function getExtendsNamespace()
    {
        $extends = $this->option('extends');

        return Str::startsWith($extends, 'SiDev')
            ? str_replace('/', '\\', $extends)
            : $this->qualifyClass($extends);
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
     * Create the contract by given namespace.
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
     * Remove useless 'use' namespaces from the stub.
     *
     * @param string $name
     * @param string $stub
     * @param array  $uses
     *
     * @return string
     */
    protected function cleanBuiltClassFromUseless(string $name, string $stub, array $uses)
    {
        $namespace = $this->getNamespace($name);

        foreach ($uses as $use) {
            if ($namespace === $this->getNamespace($use)) {
                $stub = $this->removeUselessUse($stub, $use);
            }
        }

        return $stub;
    }

    /**
     * Remove useless 'use' directive from stub.
     *
     * @param string $stub
     * @param string $useNamespace
     *
     * @return string
     */
    protected function removeUselessUse(string $stub, string $useNamespace)
    {
        return str_replace("use $useNamespace;\n", '', $stub);
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
