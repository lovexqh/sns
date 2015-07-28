<?php

class PhotoListHooks extends Hooks
{
	public function home_spaceindex_index()
	{
		$list = D('Photo','photo')->getPhotoNumList(8);
		$num = M('photo')->where('isDel = 0')->count();
		$this->assign('num',$num);
		$this->assign('list',$list);
		$this->display('photo');
		//Addons::hook('home_spaceindex_index');
	}
}
