<?php

class ClubListHooks extends Hooks
{
	public function home_spaceindex_index(){
		//获取前六个热门社团
		$hotClub = D('ClubTopic','club')->getTopicHotClub(6, 3);
		foreach ($hotClub as &$club){
			$club['title'] = mStr(text($club['title']),6);
			$club['photourl'] = $this->get_photo_url( $club['logo'] );
			foreach ($club['topic'] as &$val){
				$val['title'] = mStr(text($val['title']),12);
			}
		}
		//获取社团中前七条热帖
		$hotTopic = D('ClubTopic','club')->getHotTopic(7);
		foreach ($hotTopic as &$value){
			$value['title'] = mStr(text($value['title']),10);
		}
		//获取社团数量
		$clubnum = M('club')->where('isdel=0')->count();
		
		$this->assign('hotClub',$hotClub);
		$this->assign('hotTopic',$hotTopic);
		$this->assign('clubnum',$clubnum);
		$this->display('club');
		//Addons::hook('home_spaceindex_index');
	}
	
	//根据存储路径，获取照片真实URL
	function get_photo_url($savepath) {
		if($savepath){
			$path	=	__ROOT__.'/data/uploads/' . $savepath;
		}else{
			$path = './apps/club/Tpl/desktop/Public/images/club_default.png';
		}
		return $path;
	}
}
