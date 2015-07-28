<?php

	class CampusModel extends Model{

		/**
		 * 
		 +----------------------------------------------------------
		 * 获取校园设施列表（暂以静态数据返回）
		 +----------------------------------------------------------
		 * @return array 设施列表
		 * @author Snail 2013-12-5
		 +----------------------------------------------------------
		 */
		public function getCampusList(){
			$path=__THEME__ . '/campus';
			$Nav_jxl['name']='教学楼';
			$Nav_jxl['data'] = array(
					array('title'=>'综合教学馆1','imgPath'=>$path . '/PopPic/ghjxg.JPG'),
					array('title'=>'综合教学馆1','imgPath'=>$path . '/PopPic/ghjxg.JPG'),
					array('title'=>'综合教学馆3','imgPath'=>''),
					array('title'=>'逸夫教学馆 ','imgPath'=>$path . '/PopPic/yfjxg.jpg'),
					array('title'=>'综合楼 ','imgPath'=>''),
					array('title'=>'综合教学楼 ','imgPath'=>''),
			);
			$Nav_zsjg['name']='直属机构';
			$Nav_zsjg['data'] = array(
					array('title'=>'文体中心','imgPath'=>$path . '/PopPic/wtzx.jpg'),
					array('title'=>'图书馆','imgPath'=>$path . '/PopPic/tushuguan.jpg'),
					array('title'=>'实验楼','imgPath'=>$path . '/PopPic/shiyanlou.jpg'),
					array('title'=>'行政办公楼','imgPath'=>$path . '/PopPic/xingzhenglou.jpg'),
					array('title'=>'设计院','imgPath'=>''),
					array('title'=>'建筑博物馆','imgPath'=>''),
					array('title'=>'培训中心','imgPath'=>''),
					array('title'=>'科研行政楼','imgPath'=>''),
					array('title'=>'实验研究中心','imgPath'=>''),
					array('title'=>'实验中心','imgPath'=>''),
					array('title'=>'研究中心','imgPath'=>''),
					array('title'=>'中心广场','imgPath'=>$path . '/PopPic/zxgc.jpg'),
					array('title'=>'实习工厂','imgPath'=>''),
			);
			$Nav_xsgy['name']='学生公寓';
			$Nav_xsgy['data'] = array(
					array('title'=>'1号公寓','imgPath'=>$path . '/PopPic/yigongyu.jpg'),
					array('title'=>'2号公寓','imgPath'=>$path . '/PopPic/jhgy.jpg'),
					array('title'=>'3号公寓 ','imgPath'=>$path . '/PopPic/shgy3.jpg'),
					array('title'=>'4号公寓','imgPath'=>$path . '/PopPic/shgy4.jpg'),
					array('title'=>'5号公寓','imgPath'=>$path . '/PopPic/wugongyu.jpgt'),
					array('title'=>'6号公寓','imgPath'=>$path . '/PopPic/lhgy.jpg'),
					array('title'=>'7号公寓','imgPath'=>$path . '/PopPic/qhgy.JPG'),
					array('title'=>'8号公寓','imgPath'=>$path . '/PopPic/bagongyu.jpg'),
					array('title'=>'9号公寓','imgPath'=>$path . '/PopPic/yigongyu.jpg'),
					array('title'=>'10号公寓 ','imgPath'=>$path . '/PopPic/shhgy.jpg'),
					array('title'=>'11号公寓','imgPath'=>$path . '/PopPic/syhgy.jpg'),
					array('title'=>'研究生公寓1','imgPath'=>''),
					array('title'=>'研究生公寓2','imgPath'=>''),
			);
			$Nav[]=$Nav_jxl;
			$Nav[]=$Nav_zsjg;
			$Nav[]=$Nav_xsgy;
			return $Nav;
		}

}
?>