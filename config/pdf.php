<?php

return [
    'mode'                     => 'utf-8',
    'format'                   => 'A4-P',
    'default_font'             => 'sans-serif',
    'default_font_size'        => 12,
    'default_font'             => 'nikosh',
    'margin_left'              => 10,
    'margin_right'             => 10,
    'margin_top'               => 10,
    'margin_bottom'            => 10,
    'margin_header'            => 0,
    'margin_footer'            => 0,
    'orientation'              => 'L',
    'title'                    => env('APP_NAME', 'Ecommerce'),
    'subject'                  => '',
    'author'                   => 'themeshaper',
    'watermark'                => '',
    'show_watermark'           => true,
    'show_watermark_image'     => false,
    'watermark_font'           => 'sans-serif',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'watermark_image_path'     => 'https://themeshaper.net/img/logo.png',
    'watermark_image_alpha'    => 0.2,
    'watermark_image_size'     => 'D',
    'watermark_image_position' => 'P',
    'custom_font_dir'          => storage_path('app/public/fonts/'),
    'custom_font_data'         => [
        'nikosh' => [
            'R'         => 'Nikosh.ttf',
            'useOTL'    => 0xFF,
        ],
        'barlow' => [
            'R'  => 'Barlow-Light.ttf',    // regular font
        ],
        'mplus' => [
            'R' => 'MPLUS1p-Light.ttf'
        ]
    ],
    'auto_language_detection'  => false,
    'temp_dir'                 => storage_path('app/temp'),
    // 'temp_dir'                 => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,
];

