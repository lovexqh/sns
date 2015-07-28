<?php
class VideoApi extends Api{

	/**
	 * 
	* @Title: videolist
	* @Description: api接口显示 视频列表信息
	* @return 视频列表数组
	* @author Ricker lhyfe@sohu.com
	 */
	function videolist(){
		$video =  D('VideoApi', 'video')->getAllVideo($this->since_id , $this->max_id , $this->count , $this->page,$this->data['order']);
		return $video;
	}
	
	/**
	* @Title: videolistbyuid
	* @Description: 根据用户uid得到用户上传视频列表
	* @return 视频列表数组
	* @author Ricker lhyfe@sohu.com
	 */
	function videolistbyuid(){
		$video =  D('VideoApi', 'video')->getVideoByUid($this->user_id);
		return $video;
	}
}
