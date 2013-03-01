<?php defined('SYSPATH') or die('No direct script access.');
/**
 * PHP 5.2.x 
 * 建议安装 FileInfo 的PHP扩展
 * http://pecl.php.net/package/Fileinfo
 * 
 * PHP5.3 的 FileInfo 被整合进入PHP内部函数了    
 * @author Administrator
 *
 */
class Kohana_Filesystem_Upload {
    private $policy_dir = true; //如果不存在目录，就递归创建目录 0为不创建目录，如果是 0的话，就直接返回失败了
    private $policy_overwrite = true; //如果文件已经存在就覆盖
    private $policy_failone = true; //批量处理中，如果任何一个失败，就放弃继续处理
    private $policy_rollback = true; //批量处理中，任何一个失败，就回滚以前的，删除本批其他成功上传的
    private $policy_ext = array('gif','jpg','png','bmp'); //默认可用的扩展
    private $policy_size = 5242880; //5M=1024x1024x5
    private $policy_mode = 0755; //文件权限
    private $policy_prefix_dir = ''; //默认目录前缀，请以 / 结尾
    private $avaliable_mimes = array(); //可用的MIME列表, 由扩展得到的
    
    private $uploadeds = array(); // 成功上传的文件列表, 结构：array('src'=>mixed 'dst' or array(dst))
    private $faileds = array(); //上传失败的文件列表
    
    private $dir_created = array(); //本次上传的几个文件中被创建了目录的 array('src'=>mixed 'dst' or array(dst))

    public $flag = true; //默认上传是成功的状态
    public $status = 1; //默认上传成功
    public $details = array(); //每个上传的状态
    public $msg = '上传成功';
    public $ext = '';
    
    public $skip_lists = array(); //当目标文件存在而又不允许覆盖的时候，被忽略的文件列表
    
    private $debug = true;
    
    public $map_filetypes = array();
    
    /**
     * 本方法不处理一个 FILE变量里面有多个同名数组文件上传的情况，例如：<input type=file name="hookimg[]" />
     * 因为你无法指定保存的文件名 
     * 文件每次初始化上传的一批文件的时候，这批文件的目录、覆盖策略是相同的
     * 无后缀的文件名不让上传
     * 返回结果
     * @param array $files  Array('变量，是指$_FILE["这个值，不带$_FILE"]'=>'全路径' 或者 array('多文件中第1个文件保存路径','多文件中第2个文件保存路径','多文件中第3个文件保存路径')) 例如：array('capture'=>array('fullpath1', 'fullpath2'), 'minilog'=>'/opt/data/www/kohana/www.caiyunimg.com/game/hashdir/09/12/1.jpg')
     * @param array $policy Array('dir'=>'boolean，目录策略，可为空就是默认','overwirte'=>'boolean, 文件策略，可为空就是默认','failone'=>'','rollback'=>'', 'ext'=>array('jpg','jpeg', 'doc','可用扩展名，其实到类中对应的就是MIME'),'size'=>'单文件最大限制以byte计算的字节数。1024x1024 为1M', 'mode'=>'0755，文件或目录创建的用户权限信息')
     * @return array('status'=>'多个文件的整体状态,任何一个文件处理失败，就是FALSE','detail'=>array('目标文件全路径'=>array('signal'=>处理状态编号,'msg'=>'处理结果信息'))); 其中，detail中的数组跟上传的数组严格对应。
     */
    public function __construct($files, $policy=array()){
        $config = Kohana::config('filesystem')->upload;
        $this->policy_dir = isset($policy['dir'])? $policy['dir']:$config['dir'];
        $this->policy_overwrite = isset($policy['overwirte'])?$policy['overwirte']:$config['overwirte'];
        $this->policy_ext = isset($policy['ext'])?$policy['ext']:$config['ext'];
        $this->policy_failone = isset($policy['failone'])?$policy['failone']:$config['failone'];
        $this->policy_rollback = isset($policy['rollback'])?$policy['rollback']:$config['rollback'];
        $this->policy_size = isset($policy['size'])?$policy['size']:$config['size'];
        $this->policy_mode = isset($policy['mode'])? $policy['mode']:$config['mode'];
        $this->policy_prefix_dir = isset($policy['prefix_dir'])? $policy['prefix_dir']:$config['prefix_dir'];
        $this->map_filetypes = Kohana::config('filesystem')->ext2mime;
        $avaliable_mimes = array();
        foreach ($this->policy_ext as $ext){
            foreach($this->map_filetypes[$ext] as $mime){
                $avaliable_mimes[] =  $mime ;
            }
        }
        $this->avaliable_mimes = array_unique($avaliable_mimes);
        if(empty($files)){
            $this->flag = false;
            $this->status = 2;
            $this->msg = '错误！上传列表为空。';
        }else{
            foreach ($files as $file=>$path_saves){
                if(!isset($_FILES[$file]['tmp_name'])){
                    $this->flag = false;
                    $this->status = 3;
                    $this->msg = '错误！文件不存在。';
                    $this->_add_skiplist($path_saves);
                    break;
                }                   
                if(count($_FILES[$file]['tmp_name'])!=count($path_saves)){ 
                    $this->flag = false;
                    $this->status = 4;
                    $this->msg = '错误！子上传列表与子保存列表不一致。';
                    $this->_add_skiplist($path_saves);
                    break;
                }
                if (is_array($_FILES[$file]['tmp_name'])) {
                	foreach ($_FILES[$file]['tmp_name'] as $k=>$single_file){
                	   $this->upload_single($file, $path_saves[$k], $k);
                	   $status = $this->status;
                	   $msg = $this->msg;
                	   if (!$this->flag) {
//                	       $status = 0;         // 2012-06-28 任洪海 修改
                	       $msg = '未处理';
                	   	   $this->_add_skiplist($path_saves[$k]);
                	   }
                	   $this->details[$path_saves[$k]] = array('signal'=>$status, 'key'=>$file, 'ext'=>$this->ext, 'msg'=>$msg);
                	}
                }else {
                    $this->upload_single($file, $path_saves);
                    $status = $this->status;
                    $msg = $this->msg;
                    if (!$this->flag) {
//                        $status = 0;                // 2012-06-28 任洪海 修改
                        $msg = '未处理';
                        $this->_add_skiplist($path_saves);
                    }
                    $this->details[$path_saves] = array('signal'=>$status, 'key'=>$file, 'ext'=>$this->ext, 'msg'=>$msg);
                }                         
            }
        }
        if (!$this->flag) {
        	if($this->policy_rollback){
        	   foreach ($this->uploadeds as $upload){
        	       FS::rm($upload); //只删除文件，不删除自己创建的目录
        	   }
        	}
        }
    }
    
    
    
    /**
     * 上传单个文件的处理
     * @param String $source_key      源文件的下标
     * @param String $path_save        准备保存的路径
     * @param INT $k                            当多个文件上传的时候，处理第几个文件。默认为空表示是总共只有一个文件。
     */
    public function upload_single($source_key, $path_save, $k=''){
        $debug = $this->debug ? "(S:$source_key,K:$k,P:$path_save).":'';
        if (!$this->flag) {            
            return false;
        }
        
        if(empty($source_key)){
            $this->flag = false;
            $this->status = 5;
            $this->msg = '错误！上传时键名为空。'.$debug;
            return false;
        }
        if(empty($path_save)){
            $this->flag = false;
            $this->status = 6;
            $this->msg = '错误！上传时保存路径为空。'.$debug;
            return false;
        }
        if (!isset($_FILES[$source_key])) {
        	$this->flag = false;
            $this->status = 7;
            $this->msg = '错误！上传失败1。'.$debug;
            return false;
        }
        
        if(is_numeric($k)){
            if (!isset($_FILES[$source_key]['tmp_name'][$k])) {
                $this->flag = false;
                $this->status = 8;
                $this->msg = '错误！上传失败2。'.$debug;
                return false;
            }
            $name_client = $_FILES[$source_key]['name'][$k];
            $file_type = $_FILES[$source_key]['type'][$k];
            $file_size = $_FILES[$source_key]['size'][$k]; 
            $file_tmpname = $_FILES[$source_key]['tmp_name'][$k]; 
            $file_error = $_FILES[$source_key]['error'][$k]; 
        }else{
            if (!isset($_FILES[$source_key]['tmp_name'])) {
                $this->flag = false;
                $this->status = 9;
                $this->msg = '错误！上传失败3。'.$debug;
                return false;
            }
            $name_client = $_FILES[$source_key]['name'];
            $file_type = $_FILES[$source_key]['type'];
            $file_size = $_FILES[$source_key]['size']; 
            $file_tmpname = $_FILES[$source_key]['tmp_name']; 
            $file_error = $_FILES[$source_key]['error']; 
        }
        
        
        //常规检查
        switch ($file_error){
            case UPLOAD_ERR_OK: //上传到服务器上了，但是未保存
                break;
            case UPLOAD_ERR_INI_SIZE:
                $this->flag = false;
                $this->status = 10;
                $this->msg = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。'.$debug;
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->flag = false;
                $this->status = 11;
                $this->msg = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。'.$debug;
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->flag = false;
                $this->status = 12;
                $this->msg = '文件只有部分被上传。'.$debug;
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->flag = false;
                $this->status = 13;
                $this->msg = '没有文件被上传。 '.$debug;
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->flag = false;
                $this->status = 14;
                $this->msg = '找不到临时文件夹。'.$debug;
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $this->flag = false;
                $this->status = 15;
                $this->msg = '文件写入失败。'.$debug;
                break;            
            default:
                $this->flag = false;
                $this->status = 16;
                $this->msg = '错误！未知的上传错误。'.$debug;
                break;
        }
                
        if (!is_uploaded_file($file_tmpname)) {
        	$this->flag = false;
            $this->status = 17;
            $this->msg = '错误！没有进行上传。'.$debug;
            return false;
        }
        
        if (!$this->flag) {
            return false;
        }
        
        if ($file_size>$this->policy_size) {
        	$this->flag = false;
            $this->status = 18;
            $this->msg = '错误！上传的大小超过了策略中的限制。'.$debug;
            return false;
        }
        
        $file_paths = FS::parsepath($this->policy_prefix_dir.$path_save);
        if (file_exists($file_paths['path'])) { 
            if (!is_dir($file_paths['path'])) { 
                $this->flag = false;
                $this->status = 19;
                $this->msg = '错误！存在同名文件。'.$debug;
                return false;
            }
        }else{
            if($this->policy_dir){
               mkdir($file_paths['path'], $this->policy_mode, true);
            }
        }
        
        if(!is_writable($file_paths['path'])){
            $this->flag = false;
            $this->status = 20;
            $this->msg = '错误！目录不可写。'.$debug;
            return false;
        }
        
        //上传的时候，比较的是 MIME-TYPE
        $this->validate_mime($source_key, $k);
        if(!$this->flag)        // 2012-06-28 Ren honghai 
            return false;
        
        $file_saved = $this->policy_prefix_dir.$path_save.'.'.$this->ext;
        $is_upload_need = (is_file($file_saved) && !$this->policy_overwrite)?false:true;
       
        // 文件存在，但不可被覆盖的时候，应该报错,停止上传        
        if($is_upload_need && move_uploaded_file($file_tmpname, $file_saved)){  //如果目标已存在，就会被覆盖
            $this->uploadeds[] = $file_saved;
            $this->flag = true;
        }else{
            $this->flag = false;
            if (!$this->policy_overwrite) {
                $this->status = 21;
                $this->msg = "错误！上传文件不允许覆盖，出现同名文件。$debug";
            }else{
                $this->status = 22;                
                $this->msg = "错误！移动文件到目标路径下产生错误。$debug";
            }
            $this->_add_skiplist($file_saved);
        }
        
    }
    
    /**
     * 请最好装上 Fileinfo 扩展，这样，在服务端做文件检查
     * 如果没有安装，就用 $_FILES['src']['type']
     * 如果也没有 $_FILES['src']['type'], 就启用扩展找类型
     * @param $src
     */
    public function get_mime($src, $index=''){
        $mime = '';
        $file = is_numeric($index)? $_FILES[$src]['tmp_name'][$index]:$_FILES[$src]['tmp_name'];
        $file_type = is_numeric($index)?$_FILES[$src]['type'][$index]:$_FILES[$src]['type'];
        $file_name = is_numeric($index)?$_FILES[$src]['name'][$index]:$_FILES[$src]['name'];
        if(!is_file($file)){
            $this->flag = false;
            $this->status = 23;
            $this->msg = '错误！临时文件没有被上传到服务器上。';
            return false; 
        }
        $this->ext = $this->get_extension($file_name);
        //lastModify  Honghai Ren   file_info();
        if (!empty($file_type)){
            $mime = $file_type;
        }elseif (function_exists('finfo_open')) {
        	$mime = $this->get_fileinfo_mime($file);
        }else{            
            if (!in_array($this->ext, $this->policy_ext)) {
            	$this->flag = false;
                $this->status = 24;                
                $debug = $this->debug ? "(S:$src,I:$index).":'';
                $this->msg = "错误！根据客户端传递的扩展名不被允许。$debug";
                return false;  
            }
            
            if(!isset($this->map_filetypes[$this->ext])){
                $this->flag = false;
                $this->status = 25;
                $this->msg = '错误！根据客户端传递的扩展名不在已知的MIME列表中。';
                return false;  
            }
            $mime = $this->map_filetypes[$this->ext][0];
        }
        return $mime;
    }
    
    public function validate_mime($src, $index=''){
        $mime = $this->get_mime($src, $index);
        if(!in_array($mime, $this->avaliable_mimes)){
            $this->flag = false;
            $this->status = 26;
            $debug = $this->debug ? "(S:$src,I:$index,M: $mime).":'';
            $this->msg = "错误！文件MIME-TYPE不被服务器所支持。$debug";
            return false;
        }
        return true;
    }
    
    public function get_extension($file){
        $extension = '';
        if(strpos($file, '.')){
            $file_splits = explode('.', $file);
            $extension = $file_splits[count($file_splits)-1];
        }
        return $extension;        
    }
    
    public function get_fileinfo_mime($file){
        $mime = '';
        $finfo = finfo_open(FILEINFO_MIME, "/usr/share/misc/magic"); 
        if (!$finfo) {
            echo "Opening fileinfo database failed";
            exit();
        }else{
            $mimes = finfo_file($finfo, $file);
            list($mime, ) = explode(';', $mimes);
            finfo_close($finfo);
        }
        return trim($mime);
    }
    
    
    public function get_uploaded(){
        return $this->uploadeds;
    }
    
    public function get_faileds(){
        return $this->faileds;
    }
    
    public function clear_uploads(){
        $uploads = $this->uploadeds; //array('img'=>array('dst1','dst2',...), 'avartor'=>'dst3')
        if (!empty($uploads)) {
        	foreach ( $uploads as $upload){
        	    is_array($upload) or $upload = array($upload);
        	   foreach ($upload as $up){
                    @unlink($up);
                }
        	}
        }
        unset($uploads);
    }
    
    public function clear_dircreateds(){
        $dir_createds = $this->dir_created;
        if (!empty($dir_createds)) {
            foreach ( $dir_createds as $dir_created){
                is_array($dir_created) or $dir_created = array($dir_created);
                foreach ($dir_created as $create){
                    FS::rrmdir($create);
                }
            }
        }
        unset($dir_createds);
    }
    
    
    private function _add_skiplist($filepath){
        if(!in_array($filepath, $this->skip_lists)){
            $this->skip_lists[] = $filepath;
        }    
    }
}