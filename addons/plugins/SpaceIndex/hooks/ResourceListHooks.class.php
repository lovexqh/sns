<?php

class ResourceListHooks extends Hooks
{
	public function home_spaceindex_index()
	{
		$newlist = D('Resource','resource')->getResourceList(7);
		$hotlist = D('Resource','resource')->getResourceList(7,'readCount DESC');
		$num = M('resource')->count();
		$this->assign('num',$num);
		$this->assign('newlist',$newlist);
		$this->assign('hotlist',$hotlist);
		$this->display('resource');
	}
}
