<?php
	return array(
	    'native' => array(
	        'name' => 'session_name',
	        'lifetime' => 300,
	    ),
	    'cookie' => array(
	        'name' => 'cookie_name',
	        'encrypted' => TRUE,
	        'lifetime' => 60,
	    ),
	    'database' => array(
	        'name' => 'cookie_name',
	        'encrypted' => TRUE,
	        'lifetime' => 43200,
	        'group' => 'default',
	        'table' => 'table_name',
	        'columns' => array(
	            'session_id'  => 'session_id',
	            'last_active' => 'last_active',
	            'contents'    => 'contents'
	        ),
	        'gc' => 500,
	    ),
	);