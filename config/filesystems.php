<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'default' => [
            'driver'     => 'local',
            'root'       => public_path(),
            'url'        => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver'   => 's3',
            'key'      => env('AWS_ACCESS_KEY_ID'),
            'secret'   => env('AWS_SECRET_ACCESS_KEY'),
            'region'   => env('AWS_DEFAULT_REGION'),
            'bucket'   => env('AWS_BUCKET'),
            'url'      => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

        'dospace' => [
            'driver'   => 's3',
            'key'      => env('DOS_ACCESS_KEY_ID'),
            'secret'   => env('DOS_SECRET_ACCESS_KEY'),
            'region'   => env('DOS_DEFAULT_REGION'),
            'bucket'   => env('DOS_BUCKET'),
            'endpoint' => 'https://' . env('DOS_DEFAULT_REGION') . '.digitaloceanspaces.com',
        ],

        'wasabi' => [
            'driver'   => 's3',
            'key'      => env('WAS_ACCESS_KEY_ID'),
            'secret'   => env('WAS_SECRET_ACCESS_KEY'),
            'region'   => env('WAS_DEFAULT_REGION'),
            'bucket'   => env('WAS_BUCKET'),
            'endpoint' => 'https://s3.' . env('WAS_DEFAULT_REGION') . '.wasabisys.com',
        ],

        'vultr' => [
            'driver'     => 's3',
            'key'        => env('VULTR_ACCESS_KEY'),
            'secret'     => env('VULTR_SECRET_KEY'),
            'region'     => env('VULTR_REGION'),
            'bucket'     => env('VULTR_BUCKET'),
            'visibility' => 'public',
            'endpoint'   => env('VULTR_ENDPOINT'),
        ],

        'ftp'  => [
            'driver'   => 'ftp',
            'host'     => '85.10.205.248',
            'username' => 'myfoota1',
            'password' => 't01[y2K]UYm3jY',

            // Optional FTP Settings...
            // 'port'     => 21,
            // 'root'     => '',
//            'passive'  => true,
            // 'ssl'      => true,
            // 'timeout'  => 30,
        ],
        'sftp' => [
            'driver'   => 'sftp',
            'host'     => '195.248.243.128',
            'root'     => '/home/pouya/domains/newqiqoo.ir/public_html',
            'url'      => 'http://newqiqoo.ir/',
            // Settings for basic authentication...
            'username' => 'root',
            'password' => 'fZcYcxiXHDE0tY',

            // Settings for SSH key based authentication with encryption password...
//            'privateKey' => env('SFTP_PRIVATE_KEY'),
//            'password' => env('SFTP_PASSWORD'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
