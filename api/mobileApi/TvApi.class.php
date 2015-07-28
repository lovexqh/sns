<?php
/**
 * －－－－－－－－－－－－－－－－－－－－－－－－－－
 * 电视台API
 * @author ssq  14-02-10
 * －－－－－－－－－－－－－－－－－－－－－－－－－－
 */
class TvApi extends Api {
	function _initialize() {
	}
	/**
	 * 获取视频名称及视频流地址
	 * @author liman 2014-3-17
	 * @return 视频名及视频流  */
	public function getTvNameandStream(){
		$result['data']= D ( 'Live', 'tv' )->getTvNameStream ();
		if($result['data']){
			$result['status']     = 0;
			$result['statusCode'] = '连接成功';
		}
		else {
			$result['status']     = 1;
			$result['statusCode'] = '连接失败';
		}
		return $result;
	}
}
?>