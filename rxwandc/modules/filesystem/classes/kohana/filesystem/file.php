<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Filesystem_File {
    
    public function rm($file){
        $signal = false;
        $message = '文件不存在，不能删除。';
        if(file_exists($file)){
            if (is_writable($file)) {
                $message = '文件删除失败。';
                if(unlink($file)){
                    $signal = true;
                    $message = '文件删除成功。';
                }
            }else{
                $message = '文件存在，但不可被执行删除操作。';
            }       
        }
        
        return array('signal'=>$signal, 'msg'=>$message);
    }
    
    public function file_create($file, $content=''){
    
    }
    
    public function file_append($file, $content=''){
    
    
    }
    
    public function file_get($file){  
    }
    
    /**
     * 
     * @param string $path  必须是含有文件的路径
     */
    public function parsepath($path){
        $filename = basename($path);
        $path = str_replace($filename, '', $path);
        $subparentpath = '';
        $paths = explode(DIRECTORY_SEPARATOR, $path);
        $total_path = count($paths);
        if ($total_path>2) {
        	$subparentpath = str_replace($paths[$total_path-1], '', $path);
        }
        
        if (empty($path)) {
            $path = './';
        }
        return array('subparentpath'=>$subparentpath,'path'=>$path, 'name'=>$filename);
    }
    
}
