<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Stringhash extends Model{

    public function getHashValue($str) {
        $bytes = $this->stringToByteArray($str);
        return array_reduce($bytes, array($this, 'hash'));
    }

    public function hash($p, $v) {
        return (($p << 5) - $p) + $v;
    }

    public function stringToByteArray($str) {   
        preg_match_all('/(.)/s', $str, $bytes);
        $bytes=array_map(array($this, 'toByte'), $bytes[1]);   
        return $bytes;   
    } 
    public function toByte($input) {
        $num = ord($input);
        if($num > 0x7F) {
            return 0 - (~($num - 1) & 0x7F);
        }
        return $num; 
    }


    

}