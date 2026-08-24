<?php
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'inertia' => env('SEO_TOOLS_INERTIA', false),
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'        => 'Prokar Elektronik',
            'titleBefore'  => false, // Put defaults.title before page title, like 'It's Over 9000! - Dashboard'
            'description'  => 'Jual, beli, dan servis elektronik bekas bergaransi terpercaya. Kulkas, TV, Mesin Cuci, AC, Dispenser, dan Microwave berkualitas dengan teknisi berpengalaman.',
            'separator'    => ' | ',
            'keywords'     => ['elektronik bekas', 'servis elektronik', 'jual beli elektronik', 'kulkas bekas', 'tv bekas', 'mesin cuci bekas', 'servis kulkas', 'servis mesin cuci', 'servis tv', 'prokar'],
            'canonical'    => 'current', // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'robots'       => 'all', // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
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
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => 'Prokar Elektronik - Jual Beli & Servis Elektronik Bekas Bergaransi',
            'description' => 'Jual, beli, dan servis elektronik bekas bergaransi terpercaya. Pilihan elektronik rumah tangga berkualitas yang telah teruji oleh teknisi profesional.',
            'url'         => null, // Set null for using Url::current(), set false to total remove
            'type'        => 'website',
            'site_name'   => 'Prokar Elektronik',
            'images'      => [],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'card'        => 'summary_large_image',
            'site'        => '@prokar_elektronik',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title'       => 'Prokar Elektronik',
            'description' => 'Jual, beli, dan servis elektronik bekas bergaransi terpercaya.',
            'url'         => false, // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'type'        => 'Store',
            'images'      => [],
        ],
    ],
];
