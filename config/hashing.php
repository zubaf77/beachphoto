<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hashing Driver
    |--------------------------------------------------------------------------
    |
    | Laravel supports a variety of hashing options. You may change your
    | default hashing driver here. By default, Laravel uses the Bcrypt
    | hashing algorithm, but you may use Argon2 or other algorithms.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Here you may define the default options for the Bcrypt hashing driver.
    | If you wish to change the default options for Bcrypt or Argon, you
    | may do so here. The default is well-optimized for most use cases.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Here you may define the default options for the Argon hashing driver.
    | You may configure Argon as you see fit, but these defaults should
    | be good for most use cases. They may change over time.
    |
    */

    'argon' => [
        'memory' => 1024,
        'time' => 2,
        'threads' => 2,
    ],

];
