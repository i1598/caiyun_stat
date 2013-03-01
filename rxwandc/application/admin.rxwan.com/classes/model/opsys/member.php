<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Opsys_Member extends Model_DB {

    private $code;

	public function __construct( ) {
        $config = array( "table"=>"member", "db"=>"opsys" );
		parent::__construct( $config );
        $this->code = Kohana::$config->load( "code" );
	}

	public function setSession( $key, $str ){
		return Session::instance()->set( $key, $str );
		
		//var_dump(Session::instance()->set($key,$str));exit;
	}

	public function getSession( $key, $default = NULL ){
		return Session::instance()->get( $key, $default );
	}

	public function delSession( $key ){
		return Session::instance()->delete( $key );
	}

	/**
     * 加密
     * @param unknown_type $string
     */
    public function encrypt($array) {
        $key = $this->code['key'];
        $iv = $this->code['iv'];
        $content = trim(json_encode($array));
        $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_CBC, '');
        $cipherText_b64 = '';
        if (mcrypt_generic_init($cipher, $key, $iv) != -1) {
            $cipherText = mcrypt_generic($cipher, $content);
            mcrypt_generic_deinit($cipher);
            mcrypt_module_close($cipher);
            $cipherText_b64 = base64_encode($cipherText);
        }
        return urlencode($cipherText_b64);
    }

    /**
     * 解密
     * @param unknown_type $string
     */
    public function decrypt($string) {
        $key = $this->code['key'];
        $iv = $this->code['iv'];
        $cipherText = $string;
        $cipherText = base64_decode($cipherText);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_CBC, '');
        $p_t = '';
        if (mcrypt_generic_init($td, $key, $iv) != -1) {
            $p_t = (string) mdecrypt_generic($td, $cipherText);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
        }
        return json_decode(trim($p_t), true);
    }

	
	
	/**
	 * 字段转换
	 * 
	 */
	 
	 /*
	  * private function getField(){
        return array(
                'id'     =>  'adminid',
                'username' =>  'adminname',
                'game_server'   =>  'gameServer',
                'dateline'      =>  'addTime',
                'url'           =>  'url',
                'id'            =>  'id'
                );
    	}
	  */
}
