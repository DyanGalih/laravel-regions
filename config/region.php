<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Database Table Prefix
    |--------------------------------------------------------------------------
    |
    | This prefix will be prepended to all regional data tables.
    |
    */
    'table_prefix' => 'indonesia_',

    /*
    |--------------------------------------------------------------------------
    | API Middleware
    |--------------------------------------------------------------------------
    |
    | This middleware group will be applied to the package's API routes.
    |
    */
    'middleware' => ['api'],

    /*
    |--------------------------------------------------------------------------
    | CSV Data Source
    |--------------------------------------------------------------------------
    |
    | The absolute path to the directory containing regional CSV files.
    |
    */
    'csv_path' => env('REGION_CSV_PATH', __DIR__ . '/../database/data'),
];
