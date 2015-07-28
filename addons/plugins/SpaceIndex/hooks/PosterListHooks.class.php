<?php

class PosterListHooks extends Hooks
{
	public function home_spaceindex_index()
	{
		$data = D('Poster','poster')->getPosterList();	
		$newlist = $data['data'];
		
		$num = M('poster')->count();
		$this->assign('num',$num);
		$this->assign('newlist',$newlist);		
		$this->display('poster');
	}
}
