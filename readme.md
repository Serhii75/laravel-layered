# Package for using layered structure in Laravel.

This is an open source Laravel package which is intended primarily for those developers who uses the layered structure, like:

`model - repository - service - controller`

or

`model - service - controller`.

Actually, the package allows to create any classes, interfaces, traits that you want. 

## Features
- list of new artisan make commands: class, trait, contract, repository, service, layered-bunch
- almost every command (except trait) has options
- you can inherit abstract package classes, but if that doesn't suit you, you can create your base classes and inherit them
- the same is about interfaces - you can implement or extend the provided interfaces or write your own base interfaces

## Installation

```bash
composer require si-dev/laravel-layered
```

Then run command:  

`php artisan vendor:publish --provider="SiDev\LaravelLayered\LayeredServiceProvider"`

> Package will use its own base repository and service classes and interfaces, but if you want to use yours, you can redefine them in config/layered.php.

## Artisan commands and usage

### make:contract

creates a contract (interface) with the given name. It is also possible to specify an extensible interface.

**Options**

```bash
-e, --extends=EXTENDS
```
creates a contract that extends another specified contract

> **Note:** the contract that extends will not be created 

#### Examples

```bash
php artisan make:contract ProductInterface
php artisan make:contract ProductInterface --extends=AdapterInterface
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

```bash
php artisan make:class Adapters/DocumentAdapter
php artisan make:class Adapters/DocumentAdapter -c
php artisan make:class Adapters/DocumentAdapter -c -dDocument
php artisan make:class Adapters/DocumentAdapter  --dependency=DocumentInterface --dependencyName=document
```

### make:repository

creates a repository with the specified name. It also allows to extends base repository class, create and implement contract, inject the specified model.

**Options**

```bash
-e, --extends[=EXTENDS]
```

extends the specified class. If the name of the class to extend is not defined, it extends the base repository class that defined in package config.

```bash
-m, --model=MODEL
```

inject the model you specify. **Note**: the model will not be created, you should create it with appropriate command or manually.

```bash
-c, --contract
```

creates and implements the contract for the repository class

> All repositories will be placed in the directory `App\Repositories`. All contracts you can find in the folder `App\Contracts\Repositories`.

#### Examples

```bash
php artisan make:repository ProductRepository
php artisan make:repository ProductRepository -c -e
php artisan make:repository ProductRepository -c -e
php artisan make:repository ProductRepository -c -e --model=Product
```

### make:service

creates service class in the `App\Services` folder. Also you can inject repository or model, extends base service class, implement contract.

**Options**

```bash
-e, --extends[=EXTENDS]
```

extends the specified class. If the name of the class to extend is not defined, it extends the base service class that defined in package config.

```bash
-r, --repository[=REPOSITORY]
```

injects repository class or interface. 

```bash
-m, --model[=MODEL]
```

injects the specified model.

> **Notice**: you can use either the repository or the model, but not both options together. 

```bash
-c, --contract
```

creates and implements the contract for the service class

#### Examples

```bash
php artisan make:service ProductService
php artisan make:service ProductService --model=Product
php artisan make:service ProductService -c
php artisan make:service ProductService -c -e
php artisan make:service ProductService -c -e --repository=ProductRepository
```

### make:layered-bunch

creates bunch of classes for layered structure. As the result the next ones will be created:
- model
- factory
- migration
- repository contract that extends base repository contract
- repository class that extends base repository class and implements the contract that was created on the previous step. Also model will be injected in this class
- service contract that extends base service contract
- service class that extends base service class and implements the contract created on the previous step. Also repository contract will be injected

> **Notice** this command only creates contracts and classes, but does not bind abstract to concrete implementation. You should do it manually in AppServiceProvider or other service provider!

#### Examples

```bash
php artisan make:layered-bunch Product
```

### make:trait

creates a trait with the specified name. There is no custom options for this command. 

#### Examples

```bash
php artisan make:trait Taggable
```


## License

laravel-layered is open-source package licensed under the MIT license
