<?php defined('SYSPATH') or die('No direct access allowed.');
return array
(
    'default' => array
    (
        'type'       => 'mysql',
        'connection' => array(
            'hostname'   => 'localhost:3306',
            'username'   => 'root',
            'password'   => 'soft798.com',
            'persistent' => FALSE,
            'database'   => 'rxboxdc',
        ),
        'table_prefix' => '',
        'charset'      => 'utf8',
    ),
    'xgame' => array
    (
    	'type' => 'mysql',
    	'connection' => array(
            'hostname'   => 'localhost:3306',
            'username'   => 'root',
            'password'   => 'soft798.com',
            'persistent' => FALSE,
            'database'   => 'qynn',
        ),
        'table_prefix' => '',
        'charset'      => 'gbk',
	)
);
