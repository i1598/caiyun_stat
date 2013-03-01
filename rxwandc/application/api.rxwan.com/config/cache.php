<?php defined('SYSPATH') or die('No direct script access.');
return array
(
    // Override the default configuration
    'memcache'   => array
    (
        'driver'         => 'memcache',  // Use Memcached as the default driver
        'default_expire' => 8000,        // Overide default expiry
        'servers'        => array
        (
            // Add a new server
            array
            (
                'host'       => 'localhost',
                'port'       => 12000,
                'persistent' => FALSE
            )
        ),
        'compression'    => FALSE
    )
);