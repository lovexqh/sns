<?php

class BlogListHooks extends Hooks
{
	public function home_spaceindex_index()
	{
		$newlist = D('Blog','blog')->getSpaceBlogList(2,'cTime DESC');
		$hotlist = D('Blog','blog')->getSpaceBlogList(7,'readCount DESC');
		$num = M('blog')->count();
		$this->assign('num',$num);
		$this->assign('newlist',$newlist);
		$this->assign('hotlist',$hotlist);
		$this->display('blog');
	}
}
