<?php
/**
 +------------------------------------------------------------------------------
 * 频道统计模型
 +------------------------------------------------------------------------------
 * @category	电台直播 （应用名称）
 * @package		Lib/Model
 * @author		小伟 <ericyang@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-5 下午04:38:48
 +------------------------------------------------------------------------------
 */
class liveStatisticsModel extends Model {

	public function statistics() {
		$app_alias	 = getAppAlias('live');
		$liveDao     = M('live');
		$liveCount     = $liveDao->count();
		return array(
			'频道数量'            	=>	$liveCount,
		);
	}
}