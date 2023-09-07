<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Authorization Configuration
    |--------------------------------------------------------------------------
    */
    'route-prefix' => 'authorize',
    'user-model' => 'App\Models\Admin',
    'middleware' => 'authorize'
];
