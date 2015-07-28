<?php
/**
 +------------------------------------------------------------------------------
 * 文本编辑器Action
 +------------------------------------------------------------------------------
 * @category	desktop
 * @package		Lib/Action
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-6-14 下午17:55:54
 +------------------------------------------------------------------------------
 */
class UeditorAction extends BaseAction{
	protected $file;
	
	public function _initialize() {
		global $ts;
		parent::_initialize();
		//验证大桌面权限
		if(!$this->space['self']){
			echo L('no_privilege');
			exit();
		}
		$file1=trim($_GET['file']);
		$this->file=DATA_PATH.'/'.$file1;
	}

	/**
	 +----------------------------------------------------------
	 * 索引入口
	 +----------------------------------------------------------
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-6-18 下午1:58:19
	 +----------------------------------------------------------
	 */
	public function index(){
		$txtexts=array('DSKDOC','HTM','HTML','SHTM','SHTML','HTA','HTC','XHTML','STM','SSI','JS','JSON','AS','ASC','ASR','XML','XSL','XSD','DTD','XSLT','RSS','RDF','LBI','DWT','ASP','ASA','ASPX','ASCX','ASMX','CONFIG','CS','CSS','CFM','CFML','CFC','TLD','TXT','PHP','PHP3','PHP4','PHP5','PHP-DIST','PHTML','JSP','WML','TPL','LASSO','JSF','VB','VBS','VTM','VTML','INC','SQL','JAVA','EDML','MASTER','INFO','INSTALL','THEME','CONFIG','MODULE','PROFILE','ENGINE');
		$icoid=intval($_GET['icoid']);
		$type=intval($_GET['type']);
		if(!$icoid){
			$str='&nbsp;';
		}else{
			if(!$icoarr=M('dsk_icos')->field("icoid,url,name,ext")->where("icoid='{$icoid}' and type='attach'")->find()){
				$this->assign('msg', L('file_not_exist'));
				$this->display('show_dialog');
				exit();
			}
			if(!in_array(strtoupper($icoarr['ext']),$txtexts)){
				$this->assign('msg', L('file_ext_not_support'));
				$this->display('show_dialog');
				exit();
			}

			$str=(file_get_contents(DATA_PATH.'/'.$icoarr['url']));
			require_once ADDON_PATH.'/lib/Encode.class.php';
			$p=new Encode_Core();
			$code=$p->get_encoding($str);
			//echo 'wwwwwwwww='.$code;
			//$haha= mb_detect_encoding($str);echo 'vvvvvv='.$haha.'<br>';
			$str=preg_replace("/<script[^>]*>.*?<\/script>/ims",'',$str);
			//$str=preg_replace("/<!--\{.*?\}-->/i",'',$str);
			$str = preg_replace("/>[\n\r\t]+([ ]{4})+/",'>',$str);
			$str =preg_replace("/[\n\r\t]+([ ]{4})+</",'<',$str);
			$str =preg_replace("/>[\n\r\t]+</",'><',$str);
			$str =preg_replace("/\t/",'&nbsp;&nbsp;&nbsp;&nbsp;',$str);
			$str =preg_replace("/\r\n/",'<br>',$str);
			//if($code) $str=diconv($str,$code, 'UTF-8');
		}
		
		$this->assign($_GET);
		$this->display();
    }
    
    /**
     +----------------------------------------------------------
     * 保存文档
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-6-18 下午1:58:19
     +----------------------------------------------------------
     */
    public function save(){
    	$newsave=intval($_POST['newsave']);
    	$code=trim($_POST['code']);
    	$savecmd=trim($_POST['cmd']);
    	$message=($_POST['editorValue']);
    	$message =preg_replace("/<br.*?>/","",$message);
    	//if($code) $message=diconv($message,'UTF-8',$code);
    	
    	if(!$newsave){
    		$icoid=intval($_POST['icoid']);
    	
    		if($icoarr=textsaveattach($icoid,$message)){
    			if($icoarr['error']){
    				include dzztemplate('header_common');
    				echo "<script type=\"text/javascript\">";
    				echo "top.showDialog('".$icoarr['error']."');";
    				echo "</script>";
    				include dzztemplate('footer');
    				exit();
    			}else{
    				include dzztemplate('header_common');
    				echo "<script type=\"text/javascript\">";
    				if($savecmd=='save'){
    					echo "parent.editor_a.fileicoid='".$icoid."';";
    					echo "parent.editor_a.fileencode='".$code."';";
    					echo "parent.setWinParam({\"needsave\":0});";
    					echo "parent.editor_a.needsave=0;";
    				}
    				echo "</script>";
    				include dzztemplate('footer');
    				exit();
    			}
    	
    		}else{
    			include dzztemplate('header_common');
    			echo "<script type=\"text/javascript\">";
    			echo "top.showDialog('".$file.dzzlang('message','file_save_failure')."');";
    			echo "</script>";
    			include dzztemplate('footer');
    		}
    	}else{
    		$position=trim($_POST['position']);
    		$filename_title=trim($_POST['filename']);
    		if(strpos($filename_title,'.')===false){
    			$filename_title.='.dzzdoc';
    		}
    		$msg='';
    		//判断位置 有无权限存储；
    		list($prex,$fid,$fuid)=explode('-',$position);
    		$container='';
    		if($prex=='d'){
    			$container='icosContainer_body_'.$fid;
    		}elseif($prex=='f'){
    			$container='icosContainer_folder_'.$fid;
    		}
    		if($fuid!=$space['uid']){
    			include dzztemplate('header_common');
    			echo "<script type=\"text/javascript\">";
    			echo "top.showDialog('".dzzlang('message','no_privilege_not_self')."');";
    			echo "</script>";
    			include dzztemplate('footer');
    			exit();
    		}
    		if($icoarr=textsaveToattach($filename_title,$container,$message)){
    			if($icoarr['error']){
    				include dzztemplate('header_common');
    				echo "<script type=\"text/javascript\">";
    				echo "top.showDialog('".$icoarr['error']."');";
    				echo "</script>";
    				include dzztemplate('footer');
    				exit();
    			}else{
    				include dzztemplate('header_common');
    				echo "<script type=\"text/javascript\">";
    				if($savecmd=='save'){
    					echo "parent.editor_a.fileicoid='".$icoarr['icoid']."';";
    					echo "parent.editor_a.fileencode='".$code."';";
    					echo "parent.setWinParam({\"needsave\":0});";
    					echo "parent.editor_a.needsave=0;";
    				}
    				echo "top._ico.createIco(".json_encode($icoarr).");";
    				echo "</script>";
    				include dzztemplate('footer');
    				exit();
    			}
    		}else{
    			include dzztemplate('header_common');
    			echo "<script type=\"text/javascript\">";
    			echo "top.showDialog('".$icoarr['error']?$icoarr['error']:dzzlang('message','file_save_failure')."');";
    			echo "</script>";
    			include dzztemplate('footer');
    			exit();
    		}
    	}
    	
    	$this->display('index');
    }
    
    private function textsaveattach($icoid,$message){
		$space = $this->space;
		if(!$icoarr=M('dsk_icos')->field("size,icoid,oid,type,uid,name")->where("icoid='{$icoid}' and type='attach'")->find()){
			return array('error' => L('file_not_exist'));
		}
		if(!$attach=getAttachByMd5($message,$icoarr['name'])){
			return array('error' => L('file_save_failure'));
		}
		//判断用户权限
		if(!$space['self'] ||($icoarr['uid']!=$this->mid && $space['self']<2)){
			return array('error' => L('no_privilege'));
		}
		//计算用户新的空间大小
		$csize=$attach['filesize']-$icoarr['size'];
		//检查用户空间容量
		if($space['maxspacesize']>0 && ($csize+$space['usesize'])>$space['maxspacesize']){
			return array('error' => L('inadequate_capacity_space'));
		}
		//重新计算用户空间；
		if($csize){
			 M()->query("update ".$this->prefix.'dsk_userconfig'." set usesize=usesize+$csize where uid='{$this->mid}'");
		}
		//更新sourcedata
		
		  if($aid=M('dsk_attach')->where("qid='{$icoarr[oid]}'")->getField('aid')){
			 if($attachold=M('dsk_attachment')->where("aid='{$aid}'")->find()){
				if($attachold['copys']<=1){
					@unlink($_G['setting']['attachdir'].$attachold['attachment']);
					M('dsk_attachment')->where("aid='{$aid}'")->delete();
				}else{
					M('dsk_attachment')->where("aid='{$aid}'")->save(array('copys'=>$attachold['copys']-1));
				}
			 }
		  }
			$sourcedata=array(
						'filesize'=>$attach['filesize'],
						'attachment'=>$attach['attachment'],
						'filetype'=>$attach['filetype'],
						'filename' =>$attach['filename'],
						'remote'=>$attach['remote'],
						'aid'=>$attach['aid']
				);
				
			M('dsk_attach')->where("qid='{$icoarr[oid]}'")->save(daddslashes($sourcedata));
			M()->query("update ".$this->prefix.'dsk_attachment'." set copys=copys+1 where aid='{$attach[aid]}'");
		//更新icoarr
		$icoarr=array(
					'url'=>$_G['setting']['attachurl'].$attach['attachment'],
					'size'=>$attach['filesize'],
					'ext'=>$attach['filetype'],
				);
			M('dsk_icos')->where("icoid='{$icoid}'")->save(daddslashes($icoarr));
		return $icoarr;
	}
}
?>