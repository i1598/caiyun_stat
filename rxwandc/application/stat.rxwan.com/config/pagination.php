<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'address' => array(
        'current_page'   => array('source' => 'route', 'key' => 'page'), // source: "query_string" or "route"
        'total_items'    => 0,
        'items_per_page' => 12,
        'view'           => 'pagination/floating',
        'auto_hide'      => TRUE,
    ),
    // Application defaults
    'default' => array(
        'current_page'   => array('source' => 'query_string', 'key' => 'page'), // source: "query_string" or "route"
        'total_items'    => 0,
        'items_per_page' => 2,
        'view'           => 'pagination/basic',
        'auto_hide'      => TRUE,
    ),

);
