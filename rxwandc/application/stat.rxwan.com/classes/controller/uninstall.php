<?php
//session_start();
defined('SYSPATH') or die('No direct script access.');

class Controller_Uninstall extends Controller_Base{

	private $model;
	

	public function __construct(Request $request, Response $response) {
		parent::__construct($request, $response);
	}
	
	/**
	 * @获取安装的数据
	 * @return $this->
	 */
	public function action_data(){
		//接收数据
		$data = array(
			'uuid' => isset($_REQUEST['uuid']) ? iconv('GBK','UTF-8',trim($_REQUEST['uuid'])) : '',
			'ip' => isset($_REQUEST['ip']) ? iconv('GBK','UTF-8',trim($_REQUEST['ip'])) : 0,
			'version' => isset($_REQUEST['version']) ? iconv('GBK','UTF-8',trim($_REQUEST['version'])) : '',
			'pkg' => isset($_REQUEST['pkgname']) ? iconv('GBK','UTF-8',trim($_REQUEST['pkgname'])) : '',
		    'dateline' => isset($_REQUEST['dateline']) ? (int)$_REQUEST['dateline'] : 0,
		    'softid' => isset($_REQUEST['soft_id']) ? (int)$_REQUEST['soft_id'] : 0
		);
		$src = isset($_REQUEST['source']) ? iconv('GBK','UTF-8',trim($_REQUEST['source'])) : 0;
		
		$data['uuid'] = $this->check_uuid($data['uuid']);
		//如果uuid存在异常
		if($data['uuid'] === false){
			//logtofile(LOG_SOFTWARE_ACTIVE, 'uuid is illegal', json_encode($data), true);
		}
		
		$data['ip'] = $this->check_ip($data['ip']);
		$data['dateline'] = ($src==1 && ! empty($data['dateline'])) ? $data['dateline'] : TIME;
		$pkg_info = $this->explode_package($data['pkg']);
		$data['coop'] = $pkg_info['coop'];
	
		// echo '<pre>';
		 // print_r($data);
		 // echo '</pre>';
		//exit;
		//echo json_encode($data);exit;
		
		
		
		//对合作商进行查找操作
		if (! empty ( $data ['coop'] )) {
			$model_alliance = Model::factory('alliance');//实例化合作商表
			/*判断是否有此合作商 -> 
				 先判断缓存里是否存在 -> 不存在 : 查找数据库  存在 : 不做操作
				数据库里是否存在 -> 不存在 : 写入一条新数据 存在 : 读取出 写入缓存 */
			//$cache_val = cache_get( 'coopn_'.$data ['coop'], 'default' );
			if (true) {
				$alliance_member = $model_alliance->getOne (array('username'=>$data['coop']));//获取对应的合作商数据
				$alliance_member_id = $alliance_member['id'];
				
				if (empty ( $alliance_member_id )) {
					$data_alliance = array();
					$is_qq = is_numeric($data ['coop']) ? 1 : 0;
					$data_alliance = array ('title' => '-', 'username' => $data ['coop'], 'url' => '-', 'dateline' => TIME, 'is_qq'=>$is_qq );
					$arr = $model_alliance->add($data_alliance);//获取添加后的数组
					$alliance_member_id = $arr[0];//获取添加后的自增id
				}
				//cache_set( 'coopn_'.$data ['coop'], $alliance_member_id, 'default' );//设置对应memcache的值coopn
				//cache_set( 'coopid_'.$alliance_member_id, $data ['coop'], 'default' );//设置对应memcache的值coopid
			}
		}
		
		//现在开始正式写统计的东西
		
		//获得数据库
	
		$install_model = new Model_Install ( $data ['uuid'] );
		$reinstall_model = new Model_Reinstall ( $data ['uuid'] );
		$firstopen_model = new Model_Firstopen ( $data ['uuid'] );
		$uninstall_model = new Model_Uninstall ( $data ['uuid'] );
		$reuninstall_model = new Model_Reuninstall ( $data ['uuid'] );
		$runinstall_model = new Model_Runinstall ( $data ['uuid'] );
		//SQL语句(根据传过来的uuid 查询uuid是否存在  )
		$data_install = $install_model->getOne(array('uuid'=>$data['uuid'],'softid'=>$data['softid'],'coop'=>$data['coop']));
		if(empty($data_install)){
			$shoot_type = 0;
		}else{
			$shoot_type = 3;
			$time_last_install = $data_install['dateline'];
			if($data_install['is_alive']){				
				$shoot_type = 4;
				if($data_install['version'] == $data['version']){
					$shoot_type = 5;
				}
			}else{ 
				$data_reinstall = $reinstall_model->getList(array('uuid'=>$data['uuid'],'softid'=>$data['softid'],'coop'=>$data['coop'],'dateline'=>array('<',$data['dateline'])),NULL,NULL,array('key'=>'dateline','sort'=>'desc'));
				$data_reinstall = $data_reinstall[0];
				if(!empty($data_reinstall)){
					$time_last_install = $data_reinstall['dateline'];
					$shoot_type = $data_reinstall['version']==$data['version'] ? 2 : 1;
				}
			}
		}
		$update_data_install = array('is_alive'=>0,'lastupdate_time'=>$data ['dateline']);
		$install_model->edit(array('uuid'=>$data['uuid'],'softid'=>$data['softid'],'coop'=>$data['coop']),$update_data_install);
		//写入卸载表
		$data ['livetime'] = $data ['dateline'] - $time_last_install;
		$data ['shoot_type'] = $shoot_type;
		$uninstall_model->add($data);
		
		// 净增卸载(默认 install和reinstall 模式) --- start
		$num = $runinstall_model->getCount(array('uuid'=>$data['uuid'],'softid'=>$data['softid'],'coop'=>$data['coop']));
		if ($num) {
			$reuninstall_model->add($data);	
		}else{
			$runinstall_model->add($data);	
		}
		// 净增卸载(默认 install和reinstall 模式) --- end
		
		//end
		 //echo '<pre>';
		 //print_r($list);
		 //echo '</pre>';
	}
	

	
	




}