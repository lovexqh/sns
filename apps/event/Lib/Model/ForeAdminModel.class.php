<?php
/**
 +------------------------------------------------------------------------------
 * 空中课堂模型
 +------------------------------------------------------------------------------
 * @category	空中课堂 （应用名称）
 * @package		Lib/Model
 * @author		小伟 <ericyang@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-5 下午05:09:23
 +------------------------------------------------------------------------------
 */
class ForeAdminModel extends Model {
	var $tableName = '';
	
	/**
	 * idFieldName:ID字段名
	 * statusFieldName:状态字段名
	 * noaudit:未审核状态值
	 * pass:已通过状态值
	 * unpass:已驳回状态值
	 * close:已关闭状态值
	 */
	public function getConfig() {
		return array(
			'applist'=>array(
				'title'=>'活动',
				'table'=>'event',
				'idFieldName'=>'id',
				'statusFieldName'=>'status',
				'noaudit'=>'0',
				'pass'=>'1',
				'unpass'=>'2',
				'close'=>'3'
			)
		);
	}
}
?>