<?php

return [
    'section' => [
        'app' => [
            'title' => 'Aplikasi',
            'keys' => [
                'APP_NAME' => [
                    'label' => 'Nama Aplikasi',
                    'type' => 'text',
                    'rules' => 'required|string|max:255',
                ],
                'APP_LOCATION_NAME' => [
                    'label' => 'Nama Lokasi',
                    'type' => 'text',
                    'rules' => 'required|string|max:255',
                ],
            ],
        ],
    ],
];
