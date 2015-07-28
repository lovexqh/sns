<?php

class VideoListHooks extends Hooks
{
	public function home_spaceindex_index()
	{
		$newlist = D('Video','video')->getTopVideo(0,8,'mTime DESC');
		$hotlist = D('Video','video')->getTopVideo(0,8);
		$num = M('video')->where('status = 1')->count();
		$this->assign('num',$num);
		$this->assign('newlist',$newlist);
		$this->assign('hotlist',$hotlist);
		$this->display('video');
		//Addons::hook('home_spaceindex_index');
	}
}
