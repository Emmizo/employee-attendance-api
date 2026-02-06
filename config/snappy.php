<?php

return [
    'pdf' => [
        'enabled' => true,

        // In Sail this binary will be available inside the container after installing wkhtmltopdf.
        // You can override it from .env with WKHTML_PDF_BINARY if needed.
        'binary'  => env('WKHTML_PDF_BINARY', '/usr/bin/wkhtmltopdf'),

        'timeout' => 60,
        'options' => [],
        'env'     => [],
    ],

    'image' => [
        'enabled' => true,
        'binary'  => env('WKHTML_IMAGE_BINARY', '/usr/bin/wkhtmltoimage'),
        'timeout' => 60,
        'options' => [],
        'env'     => [],
    ],
];

