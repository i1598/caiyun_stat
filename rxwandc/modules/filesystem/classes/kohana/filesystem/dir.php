<?php
defined ( 'SYSPATH' ) or die ( 'No direct script access.' );

class Kohana_Filesystem_Dir {
    
    
    
   
    /**
     * 递归清空目录，相当于 rm -rf
     * @param $dir
     */
    public function rrmdir($path=''){
        if (file_exists($path)){
             !preg_match('/.+\/$/', $path) or $dir .= '/';
            $dirs = array_filter(scandir($path), array($this, '_is_protect_dir'));
            if (!empty($dirs)) {
                foreach($dirs as $dir){
                    is_file($dir) ? @unlink($dir):$this->rrmdir($dir);
                }
            }
            @rmdir($dirs); //尝试删除一下自己
        }
    }
    
    
    private function _is_protect_dir($dir){
            $dir_protects = array('/', '/usr/', '/bin/', '/sbin/', '/usr/local/', '/usr/local/','/var/', '/etc/', '/lib', '/lib64','.','..');
            return ! in_array($dir, $dir_protects);
    }
    
    
}