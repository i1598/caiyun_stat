<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_FS {
    
    public static function lists($dir){
    
    }
    
    public static function parsepath($path){
        $obj_file = new Filesystem_File();
        return $obj_file->parsepath($path);
    }
    
    public static function rrmdir($path){
        $dir = new Filesystem_Dir();
        return $dir->rrmdir($path);
    }
    
    public static function upload($files, $policy=array()){
        return new Filesystem_Upload($files,$policy);
    }
    
    public static function rm($file){
        $obj_file = new Filesystem_File();
        return $obj_file->rm($file);
    }
}