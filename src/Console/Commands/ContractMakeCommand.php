<?php

namespace SiDev\LaravelLayered\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ContractMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:contract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new contract';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Contract';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = __DIR__.'/../stubs/contracts/contract';

        if ($this->option('extends')) {
            $stub .= '.extends';
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
        if ($extends = $this->option('extends')) {
            return $this->buildExtendedClass($name, $extends);
        }

        return parent::buildClass($name);
    }

    /**
     * Build the class with the given and extends.
     *
     * @param string $name
     * @param string $extends
     *
     * @throws FileNotFoundException
     *
     * @return mixed
     */
    protected function buildExtendedClass(string $name, string $extends)
    {
        $namespaceExtends = $this->qualifyClass($extends);
        $extendsBasename = class_basename($namespaceExtends);

        $stub = str_replace([
            'DummyExtendedInterface',
            'DummyExtendedNamespace',
        ], [
            $extendsBasename,
            $namespaceExtends,
        ], parent::buildClass($name));

        return $this->getNamespace($name) === $this->getNamespace($namespaceExtends)
            ? $this->removeUselessUse($stub, $namespaceExtends)
            : $stub;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace('DummyInterface', $class, $stub);
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
        return $rootNamespace.'\Contracts';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the contract.'],
        ];
    }

    /**
     * Remove useless 'use' directive from stub.
     *
     * @param string $stub
     * @param string $namespaceExtends
     *
     * @return string
     */
    protected function removeUselessUse(string $stub, string $namespaceExtends)
    {
        return str_replace("use $namespaceExtends;\n\n", '', $stub);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['extends', 'e', InputOption::VALUE_REQUIRED, 'The name of the contract that being extended'],
        ];
    }
}
