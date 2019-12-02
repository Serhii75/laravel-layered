# Package for using layered structure in Laravel.

## Features
- list of new artisan make commands: class, trait, contract, repository, service, layered-bunch
- almost every command (except trait) has options
- publish Generic folder with abstract classes (or generate?)
- publish Contracts with RepositoryInterface and ServiceInterface (or generate?)

## Installation

```bash
composer require sidev/laravel-layered
```

## Artisan commands

### make:contract

creates a contract (interface) with the given name. It is also possible to specify an extensible interface.

**Options**

```bash
-e, --extends=EXTENDS
```
creates a contract that extends another specified contract

> **Note:** the contract that extends will not be created 

#### Examples

1. Create a contract 

    ```bash
    php artisan make:contract ProductRepositoryInterface
    ```
    
    Result:
    
    ```php
    <?php
    
    namespace App\Contracts;
    
    interface ProductRepositoryInterface
    {
        //
    }
    ```

2. Create a contract that extends another specified contract

    ```bash
    php artisan make:contract ProductRepositoryInterface --extends=RepositoryInterface
    ```
    
    Result:
    
    ```php
    <?php
    
    namespace App\Contracts;
    
    interface ProductRepositoryInterface extends RepositoryInterface
    {
        //
    }
    ```

### make:class

creates a class with the specified name. Using options you can create class with injected dependency and/or implementing the specified interface.

**Options**
```bash
-c, --contract[=CONTRACT]
```
If the name of the contract is specified, the command will create an interface with the specified name and a class that implements the created interface. Otherwise, a contract with the default name (classname + Interface) will be created and a class that implements the created interface. 

```bash
-d, --dependency=DEPENDENCY
```
creates a class with the specified name and injects the specified dependency into the constructor. 

```bash
--dependencyName=DEPENDENCYNAME
```
this option can only be used with the `dependency` option. Used if you want to name the injection dependency variable with a different name.

> **Note:** `dependency` and `dependencyName` will not create a dependency (class or interface). You should create it manually or using command.

#### Examples

1. Create a class with contract 

    ```bash
    php artisan make:class Adapters/DocumentAdapter -c
    ```

    Result:

    ```php
    <?php
    
    namespace App\Contracts\Adapters;
    
    interface DocumentAdapterInterface
    {
        //
    }
    ```

    ```php
    <?php
    
    namespace App\Adapters;
    
    use App\Contracts\Adapters\DocumentAdapterInterface;
    
    class DocumentAdapter implements DocumentAdapterInterface
    {
        /**
         * DocumentAdapter constructor.
         */
        public function __construct()
        {
            //
        }
    }
    ```

2. Create a class with injected dependency and interface
    ```bash
    php artisan make:class Adapters/DocumentAdapter -c -dDocument
    ```
    
    Result:
    ```php
    <?php
    
    namespace App\Contracts\Adapters;
    
    interface DocumentAdapterInterface
    {
        //
    }
    ```
    
    ```php
    <?php
    
    namespace App\Adapters;
    
    use App\Document;
    use App\Contracts\Adapters\DocumentAdapterInterface;
    
    class DocumentAdapter implements DocumentAdapterInterface
    {
        /**
         * @var Document
         */
        protected $document;
    
        /**
         * DocumentAdapter constructor.
         *
         * @param Document $document
         */
        public function __construct(Document $document)
        {
            $this->document = $document;
        }
    }
    
    ```

3. Create a class with custom named injected dependency variable

    ```bash
    php artisan make:class Adapters/DocumentAdapter  --dependency=DocumentInterface --dependencyName=document
    ```
    
    Result:
    
    ```php
    <?php
    
    namespace App\Adapters;
    
    use App\DocumentInterface;
    
    class DocumentAdapter
    {
        /**
         * @var DocumentInterface
         */
        protected $document;
    
        /**
         * DocumentAdapter constructor.
         *
         * @param DocumentInterface $document
         */
        public function __construct(DocumentInterface $document)
        {
            $this->document = $document;
        }
    }
    ```

### make:trait

creates a trait with the specified name. There is no custom options for this command. 

#### Examples

1. Create a trait

    ```bash
    php artisan make:trait Taggable
    ```
    
    Result:
    
    ```php
    <?php
    
    namespace App\Traits;
    
    trait Taggable
    {
        //
    }
    ```


## License

laravel-layered is open-source package licensed under the MIT license
