<?php

return [
    'meta'      => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'        => env("APP_TITLE"),
            'description'  => env("APP_DESC"), // set false to total remove
            'separator'    => ' - ',
            # 'keywords'     => [env("APP_KEYW")],
            'keywords'     => null,
            'canonical'    => env("APP_URL"), // Set null for using Url::current(), set false to total remove
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
        ],
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => env("APP_TITLE"),
            'description' => env("APP_DESC"), // set false to total remove
            'url'         => env("APP_URL"), // Set null for using Url::current(), set false to total remove
            'type'        => false,
            'site_name'   => env("APP_NAME"),
            //'images'      => [asset('/rk_content/preview.png')],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'card'        => 'summary',
            'site'        => '@sanalyer',
        ],
    ],
];
