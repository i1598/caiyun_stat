<?php
defined ( 'SYSPATH' ) or die ( 'No direct script access.' );

class Model_Soft_Log extends Model {
    
    private $table = 'log';
    private $table_user = 'user';
    
    /**
     * 获取版本号的数量
     */
    public function get_count() {
        return DB::select ( array (DB::expr ( 'COUNT(*)' ), 'totalcount' ) )->from ( $this->table )->execute ( Database::instance ( 'soft' ) )->get ( 'totalcount' );
    }
    
    /**
     * 获得顶级目录 版本号
     * @param unknown_type $limit
     * @param unknown_type $offset
     */
    public function get_list($limit, $offset) {
        $list_data = DB::select ()->from ( $this->table )->order_by('dateline','DESC')->limit ( $limit )->offset ( $offset )->execute ( Database::instance ( 'soft' ) )->as_array ();
        if(! empty($list_data)){
            foreach ( $list_data as $k => $v ) {
                $list_data [$k] ['admin_username'] = $this->get_admin_username ( $v ['admin_id'] );
                $list_data [$k] ['ip'] = long2ip ( $v ['admin_ip'] );
            }
        }
        return $list_data;
    }
    
    /**
     * 获取管理员username
     * @param unknown_type $id
     */
    public function get_admin_username($id) {
        return DB::select ( 'username' )->from ( $this->table_user )->where ( 'user_id', '=', $id )->execute ( Database::instance ( 'opsys' ) )->get ( 'username' );
    }
    
    /**
     * 添加说明
     * @param unknown_type $id
     * @param unknown_type $explain
     */
    public function add_explain($id, $explain) {
        $json = array('statusCode'=>300,'message'=>'键入失败');
        $result = DB::update ( $this->table )->set ( array ('explain' => $explain ) )->where ( 'id', '=', $id )->execute ( Database::instance ( 'soft' ) );
        $json = array ();
        if ($result !== false) {
            $json ['statusCode'] = 200;
            $json ['message'] = '键入成功';
            $json ['navTabId'] = 'version_log';
            $json ['callbackType'] = 'closeCurrent';
            $json ['forwardUrl'] = '';
        }
        return json_encode ( $json );
    }

	/**
	 * 添加操作
	 */
	public function add($data){
		return DB::insert($this->table,array_keys($data))->values($data)->execute(Database::instance('soft'));
	}

	/**
	 * 写日志
	 */
	public function record_log($operate) {
    	$data_log = array ('admin_id' => UID, 'admin_ip' => UIP, 'dateline' => TIME, 'operate' => $operate );
    	return $this->add($data_log);
    }


}
