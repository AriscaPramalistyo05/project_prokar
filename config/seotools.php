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
            'title'        => 'Prokar Elektronik – Jual, Beli & Servis Elektronik Bekas Jepara, Kudus, Pati, Rembang',
            'titleBefore'  => false,
            'description'  => 'Pusat jual beli & servis elektronik bekas/second bergaransi terpercaya di Jepara, Kudus, Pati, Rembang. Kulkas, TV, Mesin Cuci, AC, Dispenser berkualitas dengan teknisi berpengalaman & antar jemput.',
            'separator'    => ' | ',
            'keywords'     => [
                'jual elektronik bekas',
                'beli elektronik bekas',
                'beli elektronik second',
                'servis elektronik jepara',
                'servis elektronik kudus',
                'servis elektronik pati',
                'servis elektronik rembang',
                'servis kulkas jepara',
                'servis mesin cuci kudus',
                'servis tv pati',
                'jual kulkas second rembang',
                'toko elektronik bekas jawa tengah',
                'jual beli elektronik second terdekat',
                'service ac jepara',
                'prokar elektronik'
            ],
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
