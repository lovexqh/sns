<?php
class MobileModel extends Model {
    protected	$tableName = 'android_ver_manage';
    //获取版本列表
    public function getList(){
        return $this->where(array('status'=>'1'))->findAll();
    }
}