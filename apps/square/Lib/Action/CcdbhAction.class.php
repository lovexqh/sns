<?php
	/**
	 +------------------------------------------------------------------------------
	 * 广场 东北虎
	 +------------------------------------------------------------------------------
	 * @category	square
	 * @package		Lib/Action
	 * @author		美美 <meimeili@gridinfo.com.cn>
	 * @version		1.0
	 +------------------------------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:36:13
	 +------------------------------------------------------------------------------
	 */
class CcdbhAction extends BaseAction{
   
   
	public function index()
	{
		
		include_once SITE_PATH.'/addons/libs/Snoopy.class.php';
		$Snoopy= new Snoopy();
		$url='http://www.ccdbh.com';
		$Snoopy->fetch($url);
		$htmlstr = $Snoopy->results;
		$htmlarr=explode('<div id="focusSlider" class="scroll"></div>',$htmlstr);
		$htmlstr=$htmlarr[1];
		$htmlarr=explode('<div id="wraper">',$htmlstr);
		$htmlstr=$htmlarr[0];
		$htmlstr=mb_convert_encoding($htmlstr, "UTF-8", "GBK");
		$htmlstr=str_replace('/Moviepic/',$url.'/Moviepic/',$htmlstr);
		$this->assign('scrollScript',$htmlstr);
		$this->display();
    }
	
 
}
?>