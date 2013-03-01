
最好安装上  FileInfo 模块
http://pecl.php.net/package/Fileinfo

PHP5.3 默认就开启了这个模块


array('变量，是指$_FILE["这个值"]，例如：$_FILES['capture']就是capture'=>array('relative path1, no extension', 'relative path2, no extension') or '相对地址,没有后缀名')
array('capture'=>array('relative path1, no extension', 'relative path2, no extension'), 'minilog'=>'game/hashdir/09/12/1')
\\\

HTML:
<form action="/upload/upload.do" method="post" enctype="multipart/form-data">
  Send these files:<br />
  Multi1: <input name="capture[]" type="file" /><br />
  Multi2: <input name="capture[]" type="file" /><br />
  Single: <input name="minilogo" type="file" /><br />
  <input type="submit" value="Send files" />
</form>

每次上传只能指定一块地方。不能上传到两个完全不同的分区里面。例如：/data1/ 和 /data2/
当然，你可以设置前缀为 / 相对为 data1 和 data2，但是这样不好，文件名获取不好

1. bootstrap.php 中启用模块
'filesystem'      => MODPATH.'filesystem'

2. 调用办法：

$uploads = array('capture'=>array('09/26/1_65','09/26/1_75'), 'minilogo'=>'03/26/1_75');
$policy = array('mode'=>0600, 'dir'=>false, 'prefix_dir'=>PATH_UPLOAD);
$obj_upload = FS::upload($uploads,$policy);   
if($obj_upload->flag){
    //上传成功
    echo $obj_upload->msg; 
}else{
    //上传失败
    //参见返回信息
    
}


参数说明：
          参数一：上传信息
          array('变量，是指$_FILE["这个值"]，例如：$_FILES['capture']就是capture'=>array('relative path1, no extension', 'relative path2, no extension') or '相对地址,没有后缀名')
          例如：
          array('capture'=>array('relative path1, no extension', 'relative path2, no extension'), 'minilog'=>'game/hashdir/09/12/1')
          
          参数二：策略定制
            具体策略，请见配置文件。如果你不写这个策略中的任意值，都会自动以配置文件中的为准
            array(
                'size'=>'INT, 上传文件限制最大字节数',
                'mode'=>'八进制, 0755，文件读写权限',
                'dir'=>'Boolean, 如果目录不存在，是否自动创建。TRUE自动，FALSE不自动就返回错误，如果目录不存在',
                'overwirte'=>'Boolean, 如果文件已经存在，是否覆盖。true就是覆盖，否则不覆盖',
                'ext'=>'Array, 一维数组，支持的扩展名和文件格式',
                'failone'=>'Boolean, 失败一个是否终止,备用',
                'rollback'=>'Boolean, 失败一个后，是否回滚，如果为TRUE，任何一个失败，就自动删除已经成功上传的',
                'prefix_dir'=>'文件保存的前缀路径，Web地址不可访问的，也就是网站根目录。请务必以 / 结尾' 
            )



返回值说明:
object
    flag: boolean,  本次一批文件全部成功就是true,任何一个失败就是 false
    status: INT,  本次最后一个失败或成功的代码CODE，代码列表后附
    msg: String, 本次最后一个文件处理的状态信息
    details: Array, 本次处理的明细
                 array(
                    '保存相对路径, 不含后缀'=>array(
                                                    'signal'=>本文件处理状态, 0 表示未处理
                                                    'key'=>上传时所用的文件变量,例如：capture等
                                                    'ext'=>文件后缀,
                                                    'msg'=>本条上传出错的原因                   
                                                )                 
                 )
    skip_lists: Array, 因为错误等被忽略的列表，一维数组


关于文中所出现的 status 和 signal 列表：
0                表示未处理
1                处理成功
2                错误！上传列表为空。
3                错误！文件不存在。
4                错误！子上传列表与子保存列表不一致。
5                错误！上传时键名为空。
6                错误！上传时保存路径为空。
7                错误！上传失败1。
8                错误！上传失败2。
9                错误！上传失败3。
10              上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。
11              上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。
12              文件只有部分被上传。
13              没有文件被上传。
14              找不到临时文件夹。
15              文件写入失败。
16              错误！未知的上传错误。
17              错误！没有进行上传。
18              错误！上传的大小超过了策略中的限制。
19              错误！存在同名文件。
20              错误！目录不可写。
21              错误！上传文件不允许覆盖，出现同名文件。
22              错误！移动文件到目标路径下产生错误。
23              错误！临时文件没有被上传到服务器上。
24              错误！根据客户端传递的扩展名不被允许。
25              错误！根据客户端传递的扩展名不在已知的MIME列表中。
26              错误！文件MIME-TYPE不被服务器所支持。



备注：
     同type=file name=capture[] 相同多文件上传时，不知道多少个的做法：
     $uploads = array();
     $capture_total = count($_FILE['capture']['tmp_name']);
     for($i=0; $i<$capture_total; $i++){
          $uploads['capture'][$i]  = '文件相对路径N'; 
     }

    或者
    $uploads = array();
    foreach($_FILE['capture']['tmp_name'] as $v){
        $uploads['capture'][]  = '文件相对路径N'; 
    }

=-==-==-==-==-==-==-==-==-==-==-==-==-==-==-==-==-==-=
小文件(10M以下)远程上传(非上传到本地)
$remote = array()
作为上传的第三个参数
server     所存放的服务器
protocal 所用协议,可选用：ftp、 http or rsync，不能为其他
address  地址，可以为IP,
port        端口,默认的话，可以省略
username 用户名
password 密码
dstpath  目标路径存放地址完整路径（含文件名）。其中，ftp为相对路径，http和rsync根据具体情况，可以为相对，也可以为绝对

改写 save 方法，支持远程存放

