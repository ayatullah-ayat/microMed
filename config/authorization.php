<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Authorization Configuration
    |--------------------------------------------------------------------------
    */
    'route-prefix' => 'admin/authorize',
    'user-model' => 'App\Models\Admin',
    'middleware' => 'authorize'
];
