<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Repository Base Class and Contract Paths
    |--------------------------------------------------------------------------
    |
    | When using the "make:repository" command with "extends" and/or "contract" option(s),
    | we need to know which base class and/or contract should be used.
    |
    | Feel free to change these paths, but note that it is your responsibility
    | to create the appropriate class and/or interface.
    |
    */
    'repository' => [
        'base_class' => SiDev\LaravelLayered\Repositories\AbstractEloquentRepository::class,
        'contract' => SiDev\LaravelLayered\Contracts\Repositories\RepositoryInterface::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Service Base Class and Contract Paths
    |--------------------------------------------------------------------------
    |
    | When using the "make:service" command with "extends" and/or "contract" option(s),
    | we need to know which base class and/or contract should be used.
    |
    | Feel free to change these paths, but note that it is your responsibility
    | to create the appropriate class and/or interface.
    |
    */
    'service' => [
        'base_class' => SiDev\LaravelLayered\Services\AbstractService::class,
        'contract' => SiDev\LaravelLayered\Contracts\Services\ServiceInterface::class,
    ],
];
