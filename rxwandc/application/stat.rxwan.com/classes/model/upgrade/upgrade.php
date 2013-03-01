<?php
defined( 'SYSPATH' )or die( 'No direct script access.' );

class Model_Upgrade_Upgrade extends Model{
	
	/**
	 * 规则
	 * @var unknown_type
	 */
    public $_rule_list = array(
        'version'=>array(
                                    array('not_empty'),
                                    array('regex',array(':value', '/^[0-9]{1,3}\.[0-9][0-9\.]*$/')) // array(array($this, 'is_version'), array(':value'))
                            ),
        'pkgname'=>array(array('not_empty')),
        'soft_id'=>array(array('regex',array(':value', '/^[0-9]+$/')))
    );
    
    /**
     * 验证
     * @param $data
     */
    public function rule_gets($data){
        $validator = Validation::factory($data);
        foreach ($this->_rule_list as $field=>$rules){
            $validator->rules($field, $rules);
        }
        $errors = $validator->check() ? array():$validator->errors('softupgrade');
        return $errors;
    }

    /**
     * 获取当前的升级版本
     */
    public function get_release_current($soft_id){
        return Model::factory('upgrade_version')->get_version($soft_id,1);
    }
    
    /**
     * 获取当前的测试升级版本
     */
	public function get_update_current($soft_id){
        return Model::factory('upgrade_version')->get_version($soft_id,2);
    }
    
    public function get_force(){
        $force = '1.0';
        return $force;    
    }
    
    private function get_version_title($version){
        $title = '';
        if(strpos($version, '.')){
            $versions = explode('.', $version);
            $title = $versions[0].'.'.$versions[1];
        }
        return $title;
    }
    
    /**
     * 获取日志信息
     */
    public function get_changes($soft_id,$version){
        $version_title = $this->get_version_title($version);
        return Model::factory('upgrade_version')->get_version_by_title($soft_id,$version_title);
    }
    
    
}