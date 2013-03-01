<?php defined('SYSPATH') or die('No direct access allowed.');
 
class Model_User_User extends ORM {
 	
	protected $_table_name = 'user';
	
    public function rules()
    {
        return array(
            'status' => array(
                array('not_empty'),
            ),
            'createTime' => array(
                array('not_empty'),
            ),
			'ip' => array(
                array('not_empty'),
            ),
           	'isActive' => array(
                array('not_empty'),
            ),
            'uuid' => array(
                array('not_empty'),
                array('min_length', array(':value', 4)),
                array('max_length', array(':value', 32)),
                //array('regex', array(':value', '/^[-\pL\pN_.]++$/uD')),
            ),
        );
    }
}