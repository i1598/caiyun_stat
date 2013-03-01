<?php
//session_start();
defined('SYSPATH') or die('No direct script access.');

class Controller_Active extends Controller_Base{

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
	
		//echo json_encode($data);exit;
		
		//现在开始正式写统计的东西
		
		//获取需要的表名
		$install_model = new Model_Install ( $data ['uuid'] );
		$firstopen_model = new Model_Firstopen ( $data ['uuid'] );
		$active_model = new Model_Active ( $data ['uuid'] );
		$ractive_model = new Model_Ractive ( $data ['uuid'] );
		
		//查询安装数据来跟 队列中数据对比 产生shoot_type信息
		$data_install = $install_model->getOne(array('uuid'=>$data['uuid'],'softid'=>$data['softid'],'coop'=>$data['coop']));
		$data_unavailable = array(0,13,14,15);
		$data['shoot_type'] = 15;
		$is_firstopen = TRUE;
		if(! empty($data_install)){
			$data['shoot_type'] = 1;
			if($data_install['version'] == $data['version']){
				$data['shoot_type'] = 2;
				if($data_install['is_alive']==1){
					$data['shoot_type'] = 3;
				}			
			}
		}

		//查询第一次打开量(根据传过来的uuid跟版本号)
		$first_open = $firstopen_model->getOne(array('uuid'=>$data['uuid'],'softid'=>$data['softid'],'coop'=>$data['coop']));
		$is_firstopen = TRUE;
		if(! empty($first_open)){
			$time_interval = $data['dateline']-$first_open['dateline'];
			if($time_interval>86400){
				$is_version = $first_open['version'] == $data['version'] ? TRUE : FALSE;
				if(empty($data_install)){
					$data['shoot_type'] = $is_version ? 13 : 14;			
				}else{
					$data['shoot_type'] = $is_version ? 5 : 4;
					if($data_install['version'] == $data['version']){
						$data['shoot_type'] = $is_version ? 7 : 6;
						if($data_install['is_alive'] == 1){
							$data['shoot_type'] = $is_version ? 9 : 8;
						}
					}
				}
				$is_firstopen = FALSE;
				//更新install中的active字段
				if(! in_array($data['shoot_type'],$data_unavailable) && $data_install['is_active'] != 1){
					$array = array('is_active'=>1,'time_active'=>TIME);
					$install_model->edit(array('uuid'=>$data['uuid'],'softid'=>$data['softid'],'coop'=>$data['coop']),$array);
				}
				//写入active
				$active_model->add($data);
			}
		}
		//安装表里没有存在信息，写入一条新纪录(is_firstopen为1,is_miss为1)
		if(in_array($data['shoot_type'],$data_unavailable)){
				$array = array('uuid'=>$data['uuid'],'ip'=>$data['ip'],'dateline'=>$data['dateline'],'is_alive'=>1,'lastupdate_time'=>$data['dateline'],'version'=>$data['version'],'pkg'=>$data['pkg'],'is_firstopen'=>1,'time_firstopen'=>$data['dateline'],'is_miss'=>1);
				$install_model->add($array);
		}
		
		// 如果不存在纯净表中就写入: ractive 要与 active 表结构相同
		$num = $ractive_model->getCount(array('uuid'=>$data['uuid'],'softid'=>$data['softid'],'coop'=>$data['coop']));
		if (!$num) {
			$ractive_model->add($data);	
		}
		
		//写入firstopen
		if($is_firstopen){
			if(! in_array($data['shoot_type'],$data_unavailable) && $data_install['is_firstopen'] != 1){
				$array = array('is_firstopen'=>1,'time_firstopen'=>TIME);
				$install_model->edit(array('uuid'=>$data['uuid'],'softid'=>$data['softid'],'coop'=>$data['coop']),$array);
			}
			$firstopen_model->add($data);
		
		//end
		 //echo '<pre>';
		 //print_r($list);
		 //echo '</pre>';
		}
	}
	

	
	




}