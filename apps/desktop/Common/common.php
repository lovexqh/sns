<?php

/**
 +----------------------------------------------------------
 * 获取应用配置参数
 +----------------------------------------------------------
 * @param NULL $key 空
 * @return array $config 配置信息数组; string $config[$key] 应用配置key值
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-18 下午4:07:20
 +----------------------------------------------------------
 */
function get_config($key = NULL) {
	$result = M ( 'dsk_config' )->order ( 'listorder' )->findAll ();
	foreach ( $result as $obj ) {
		if(stripos($obj ['value'],'php')){
			$obj ['value'] = SITE_URL.'/'.$obj ['value'];
		}
		$config [$obj ['key']] = $obj ['value'];
	}	
	if ($key == NULL) {
		return $config;
	} else {
		return $config [$key];
	}
}

/**
 +----------------------------------------------------------
 * 根据用户ID获取用户space相关设置
 +----------------------------------------------------------
 * @param int $uid 用户ID
 * @return multitype:number NULL mixed string multitype:unknown  
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-20 下午3:30:35
 +----------------------------------------------------------
 */
function dskgetspace($uid){
	global $ts;
	$space=array();
	if($uid==0){
		if($ts['isSystemAdmin']==true){
			$space=array( 'uid' => 0,'self'=>2, 'username' => '', 'adminid' => 1, 'groupid' => 1, 'credits' => 0, 'timeoffset' => 9999);
		}else{
			$space=array( 'uid' => 0,'self'=>0, 'username' => '', 'adminid' => 0, 'groupid' => 7, 'credits' => 0, 'timeoffset' => 9999);
		}
	}else{
		//该分支有些复杂暂不考虑
		//$space=getuserbyuid($uid);
	}
	
// 	require_once libfile('function/friend');
// 	$space['isfriend']=friend_check($uid);

	//获取用户组信息
	if(!$usergroup=M('dsk_usergroup')->where("groupid='{$space[groupid]}'")->find()){
		$usergroup=array();
	}
	$space['groupsize']=$usergroup['maxspacesize']*1024*1024;
	$config = M()->query("select u.thame,u.custom_filemanage,u.custom_dock,u.custom_backimg,custom_window,u.custom_topbar,u.spacename,u.metakeyword,u.metadescription,u.friend,u.status as dzzstatus,u.dateline,u.updatetime,u.usesize,f.allownewfolder,f.allowupload,f.allownewlink,f.attachextensions,f.maxattachsize,f.addsize,f.buysize from ".C('DB_PREFIX')."dsk_userconfig u
								LEFT JOIN ".C('DB_PREFIX')."dsk_userconfig_field f ON u.uid=f.uid
			where u.uid='$uid'");
	$config = $config[0];
	if($uid>0 && $config){
			$arr=M('dsk_thame')->where("id='{$config[thame]}'")->find();
			if(!$arr){
				$arr=M('dsk_thame')->where("`default`='1'")->find();
			}
			unset($config['thame']);
			$space['thame']=array(
					'folder'=>$arr['folder'],
					'backimg'=>$config['custom_backimg']?$config['custom_backimg']:$arr['backimg'],
					'dock'=>$config['custom_dock']?$config['custom_dock']:$arr['dock'],
					'window'=>$config['custom_window']?$config['custom_window']:$arr['window'],
					'browser'=>$config['custom_browser']?$config['custom_browser']:$arr['browser'],
						
					'topbar'=>$config['custom_topbar']?$config['custom_topbar']:$arr['topbar'],
					'member'=>$config['custom_member']?$config['custom_member']:$arr['member'],
					'filemanage'=>$config['custom_filemanage']?$config['custom_filemanage']:$arr['filemanage'],
			);
			$config['allownewfolder']=(!isset($config['allownewfolder']) || $config['allownewfolder']<0)?$usergroup['allownewfolder']:$config['allownewfolder'];
			$config['allownewlink']=(!isset($config['allownewlink']) || $config['allownewlink']<0)?$usergroup['allownewlink']:$config['allownewlink'];
			$config['allowupload']=(!isset($config['allowupload']) || $config['allowupload']<0)?$usergroup['allowupload']:$config['allowupload'];
			$config['attachextensions']=(!isset($config['attachextensions']) || $config['attachextensions']<0)?$usergroup['attachextensions']:$config['attachextensions'];
			$config['maxattachsize']=(!isset($config['maxattachsize']) || $config['maxattachsize']<0)?$usergroup['maxattachsize']:$config['maxattachsize'];
			$config['maxspacesize']=$usergroup['maxspacesize']==0?0:($usergroup['maxspacesize']*1024*1024+$config['addsize']+$config['buysize']);

			$space=array_merge($space,$config);
	}else{
		$arr=M('dsk_thame')->where("`default`='1'")->find();
		$space['thame']=array(
				'folder'=>$arr['folder'],
				'backimg'=>$arr['backimg'],
				'dock'=>$arr['dock'],
				'window'=>$arr['window'],
				'browser'=>$arr['browser'],
				'topbar'=>$arr['topbar'],
				'member'=>$arr['member'],
				'filemanage'=>$arr['filemanage'],
		);
		$space=array_merge($space,$usergroup);
	}
	if($ts['isSystemAdmin']==true){
		$space['allownewfolder']=1;
		$space['allownewlink']=1;
		$space['allowupload']=1;

	}
	$space['fusesize']=formatsize($space['usesize']);
	if($space['maxattachsize']==0){
		$space['fmaxattachsize']='不限制';
	}else{
		$space['fmaxattachsize']=formatsize($space['maxattachsize']);
	}
	if($space['maxspacesize']==0){
		$space['fmaxspacesize']='不限制';
	}else{
		$space['fmaxspacesize']=formatsize($space['maxspacesize']);
	}
	$space['attachextensions']=str_replace(' ','',$space['attachextensions']);

	return $space;

}

/**
 +----------------------------------------------------------
 * 格式化输出的大小单位
 +----------------------------------------------------------
 * @param unknown $size
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-20 下午4:09:33
 +----------------------------------------------------------
 */
function formatsize($size) {
	$prec=3;
	$size = round(abs($size));
	$units = array(0=>" B ", 1=>" KB", 2=>" MB", 3=>" GB", 4=>" TB");
	if ($size==0) return str_repeat(" ", $prec)."0$units[0]";
	$unit = min(4, floor(log($size)/log(2)/10));
	$size = $size * pow(2, -10*$unit);
	$digi = $prec - 1 - floor(log($size)/log(10));
	$size = round($size * pow(10, $digi)) * pow(10, -$digi);
	return $size.$units[$unit];
}

/**
 * +----------------------------------------------------------
 * 貌似是格式化字符串相关的功能（目前不详）
 * +----------------------------------------------------------
 * 
 * @param string $string
 *        	传入的字符串
 * @return string
 * @author 小波
 *         +----------------------------------------------------------
 *         创建时间：2013-3-19 下午2:21:23
 *         +----------------------------------------------------------
 */
function dstripslashes($string) {
	if (empty ( $string ))
		return $string;
	if (is_array ( $string )) {
		foreach ( $string as $key => $val ) {
			$string [$key] = dstripslashes ( $val );
		}
	} else {
		$string = stripslashes ( $string );
	}
	return $string;
}

/**
 * +----------------------------------------------------------
 * 将数组转换成数据库可执行的字符串
 * +----------------------------------------------------------
 * 
 * @param array $array
 *        	传入的数组
 * @return string number
 * @author 小波
 *         +----------------------------------------------------------
 *         创建时间：2013-3-19 下午4:19:01
 *         +----------------------------------------------------------
 */
function dimplode($array) {
	if (! empty ( $array )) {
		$array = array_map ( 'addslashes', $array );
		return "'" . implode ( "','", is_array ( $array ) ? $array : array (
				$array 
		) ) . "'";
	} else {
		return 0;
	}
}

/**
 * +----------------------------------------------------------
 * 拷贝过来的方法，暂时从字面上理解是移动桌面数据
 * +----------------------------------------------------------
 * 
 * @param unknown $unavid        	
 * @param unknown $uid        	
 * @param unknown $targetunavid        	
 * @return string Ambigous
 * @author 小波
 *         +----------------------------------------------------------
 *         创建时间：2013-3-19 下午4:02:41
 *         +----------------------------------------------------------
 */
function desktop_move_userdata($unavid, $uid, $targetunavid) {
	global $ts;
	if ($ts ['user'] ['uid'] != $uid && $ts ['isSystemAdmin'] != true) {
		return 'no_prevelige';
	}
	// ��ȡ�û��������
	$userconfig = M ( 'dsk_userconfig' )->where ( "uid='$uid'" )->find ();
	$screenlist = dstripslashes ( unserialize ( stripslashes ( $userconfig ['screenlist'] ) ) );
	// ����icos
	if ($screenlist [$targetunavid] && $screenlist [$unavid] ['icos']) {
		$screenlist [$targetunavid] ['icos'] = array_merge ( $screenlist [$targetunavid] ['icos'], $screenlist [$unavid] ['icos'] );
	}
	// ����widgets
	foreach ( $screenlist [$unavid] ['widgets'] as $value ) {
		$screenlist [$targetunavid] ['widgets'] [$value ['gid']] = $value;
	}
	// ����wins
	foreach ( $screenlist [$unavid] ['wins'] as $value ) {
		$screenlist [$targetunavid] ['wins'] [$value ['icoid']] = $value;
	}
	
	M ( 'dsk_userconfig' )->where ( "uid='$uid'" )->save ( array (
			'screenlist' => daddslashes ( serialize ( $screenlist ) ) 
	) );
	return $screenlist [$targetunavid];
}


/**
 +----------------------------------------------------------
 * getglobal
 +----------------------------------------------------------
 * @param unknown $key
 * @param string $group
 * @return NULL|unknown
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-19 下午5:20:21
 +----------------------------------------------------------
 */
function getglobal($key, $group = null) {
	global $data;
	$key = explode('/', $group === null ? $key : $group.'/'.$key);
	$v = &$data;
	foreach ($key as $k) {
		if (!isset($v[$k])) {
			return null;
		}
		$v = &$v[$k];
	}
	return $v;
}
/**
 +----------------------------------------------------------
 * dgmdate
 +----------------------------------------------------------
 * @param unknown $timestamp
 * @param string $format
 * @param string $timeoffset
 * @param string $uformat
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-19 下午5:17:30
 +----------------------------------------------------------
 */
function dgmdate($timestamp, $format = 'dt', $timeoffset = '9999', $uformat = '') {
	global $data;
	$format == 'u' && ! $data ['setting'] ['dateconvert'] && $format = 'dt';
	static $dformat, $tformat, $dtformat, $offset, $lang;
	if ($dformat === null) {
		$dformat = getglobal ( 'setting/dateformat' );
		$tformat = getglobal ( 'setting/timeformat' );
		$dtformat = $dformat . ' ' . $tformat;
		$offset = getglobal ( 'member/timeoffset' );
		$lang = L( 'date' );
	}
	$timeoffset = $timeoffset == 9999 ? $offset : $timeoffset;
	$timestamp += $timeoffset * 3600;
	$format = empty ( $format ) || $format == 'dt' ? $dtformat : ($format == 'd' ? $dformat : ($format == 't' ? $tformat : $format));
	if ($format == 'u') {
		$todaytimestamp = TIMESTAMP - (TIMESTAMP + $timeoffset * 3600) % 86400 + $timeoffset * 3600;
		$s = gmdate ( ! $uformat ? $dtformat : $uformat, $timestamp );
		$time = TIMESTAMP + $timeoffset * 3600 - $timestamp;
		if ($timestamp >= $todaytimestamp) {
			if ($time > 3600) {
				return '<span title="' . $s . '">' . intval ( $time / 3600 ) . '&nbsp;' . $lang ['hour'] . $lang ['before'] . '</span>';
			} elseif ($time > 1800) {
				return '<span title="' . $s . '">' . $lang ['half'] . $lang ['hour'] . $lang ['before'] . '</span>';
			} elseif ($time > 60) {
				return '<span title="' . $s . '">' . intval ( $time / 60 ) . '&nbsp;' . $lang ['min'] . $lang ['before'] . '</span>';
			} elseif ($time > 0) {
				return '<span title="' . $s . '">' . $time . '&nbsp;' . $lang ['sec'] . $lang ['before'] . '</span>';
			} elseif ($time == 0) {
				return '<span title="' . $s . '">' . $lang ['now'] . '</span>';
			} else {
				return $s;
			}
		} elseif (($days = intval ( ($todaytimestamp - $timestamp) / 86400 )) >= 0 && $days < 7) {
			if ($days == 0) {
				return '<span title="' . $s . '">' . $lang ['yday'] . '&nbsp;' . gmdate ( $tformat, $timestamp ) . '</span>';
			} elseif ($days == 1) {
				return '<span title="' . $s . '">' . $lang ['byday'] . '&nbsp;' . gmdate ( $tformat, $timestamp ) . '</span>';
			} else {
				return '<span title="' . $s . '">' . ($days + 1) . '&nbsp;' . $lang ['day'] . $lang ['before'] . '</span>';
			}
		} else {
			return $s;
		}
	} else {
		return gmdate ( $format, $timestamp );
	}
}

/**
 +----------------------------------------------------------
 * 貌似是解释html或数组到普通字符串的函数
 +----------------------------------------------------------
 * @param unknown $string
 * @param string $flags
 * @return Ambigous <mixed, string>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-20 上午9:55:37
 +----------------------------------------------------------
 */
function dhtmlspecialchars($string, $flags = null) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlspecialchars($val, $flags);
		}
	} else {
		if($flags === null) {
			$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
			if(strpos($string, '&amp;#') !== false) {
				$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
			}
		} else {
			if(PHP_VERSION < '5.4.0') {
				$string = htmlspecialchars($string, $flags);
			} else {
				if(strtolower(CHARSET) == 'utf-8') {
					$charset = 'UTF-8';
				} else {
					$charset = 'ISO-8859-1';
				}
				$string = htmlspecialchars($string, $flags, $charset);
			}
		}
	}
	return $string;
}

/**
 +----------------------------------------------------------
 * 生成随机数
 +----------------------------------------------------------
 * @param int $length 生成的长度
 * @param number $numeric
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-21 上午10:44:22
 +----------------------------------------------------------
 */
function random($length, $numeric = 0) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed{mt_rand(0, $max)};
	}
	return $hash;
}

/**
 +----------------------------------------------------------
 * 获取表单或cookie提交来的数据
 +----------------------------------------------------------
 * @param string $k 要获取的键
 * @param string $var 要获取的类型
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-21 下午2:37:21
 +----------------------------------------------------------
 */
function getvar($k, $var='R') {
	switch($var) {
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
	return isset($var[$k]) ? $var[$k] : NULL;
}

/**
 +----------------------------------------------------------
 * 获取表单由get post提交过来的数据
 +----------------------------------------------------------
 * @param string $k 要获取的关键字
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-21 下午2:40:30
 +----------------------------------------------------------
 */
function getgp($k){
	return ( isset($_POST[$k])&&!empty($_POST[$k]) ) ? $_POST[$k] : $_GET[$k];
}
/**
 +----------------------------------------------------------
 * 这是个神马方法呀，貌似是转换字符串（写的真复杂）
 +----------------------------------------------------------
 * @param unknown $string
 * @param unknown $length
 * @param number $in_slashes
 * @param number $out_slashes
 * @param number $bbcode
 * @param number $html
 * @return string
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return string <返回类型(void的方法就不用该选项)>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-20 上午9:51:30
 +----------------------------------------------------------
 */
function getstr($string, $length, $in_slashes=0, $out_slashes=0, $bbcode=0, $html=0) {

	$string = trim($string);
	$sppos = strpos($string, chr(0).chr(0).chr(0));
	if($sppos !== false) {
		$string = substr($string, 0, $sppos);
	}
	if($in_slashes) {
		$string = dstripslashes($string);
	}
	$string = preg_replace("/\[hide=?\d*\](.*?)\[\/hide\]/is", '', $string);
	if($html < 0) {
		$string = preg_replace("/(\<[^\<]*\>|\r|\n|\s|\[.+?\])/is", ' ', $string);
	} elseif ($html == 0) {
		$string = dhtmlspecialchars($string);
	}

	if($length) {
		$string = strSubstr($string, 0, $length);
	}

	if($bbcode) {
		//该部分貌似是转换ubb代码
		/* require_once DISCUZ_ROOT.'./source/class/class_bbcode.php';
		$bb = & bbcode::instance();
		$string = $bb->bbcode2html($string, $bbcode); */
	}
	if($out_slashes) {
		$string = daddslashes($string);
	}
	return trim($string);
}

/**
 +----------------------------------------------------------
 * 分页显示
 * 用于在页面显示的分页栏的输出
 +----------------------------------------------------------
 * @param int $totalRows 总行数
 * @param int $nowPage 当前页
 * @param int $totalPages 总页数
 * @return void|string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-22 下午1:34:22
 +----------------------------------------------------------
 */
function showPage($totalRows,$nowPage=1,$totalPages){
	if ($totalRows <= $nowPage) {
		return '';
	}
	$rollPage = 5;
	if(0 == $totalRows) return;

	$url	=	eregi_replace("(#.+$|p=[0-9]+)",'',$_SERVER['REQUEST_URI']);
	$url	=	$url.(strpos($url,'?')?'':"?");
	$url	=	eregi_replace("(&+)",'&',$url);

	//上下翻页字符串
	$upRow   = $nowPage-1;
	$downRow = $nowPage+1;
	if ($upRow>0){
		$upPage="<a class='prev' href='".$url."&".C('VAR_PAGE')."=$upRow'>".L('prev_page')."</a>";
	}else{
		$upPage="";
	}

	if ($downRow <= $totalPages){
		$downPage="<a class='nxt' href='".$url."&".C('VAR_PAGE')."=$downRow'>".L('next_page')."</a>";
	}else{
		$downPage="";
	}

	// 1 2 [3] 4 5
	$linkPage = "";
	$halfRoll	=	ceil($rollPage/2);

	if( $totalPages <= $rollPage ){
		$leftPages	=	$nowPage-1;
		$rightPages	=	$totalPages-$leftPages-1;
	}elseif( ($nowPage < $halfRoll) && ($totalPages > $rollPage) ){
		$leftPages	=	$nowPage-1;
		$rightPages	=	$rollPage-$leftPages-1;
	}elseif( ($totalPages-$nowPage) < $halfRoll ){
		$rightPages	=	$totalPages-$nowPage;
		$leftPages	=	$rollPage-$rightPages-1;
	}else{
		$rightPages	=	$rollPage-$halfRoll;
		$leftPages	=	$rollPage-$rightPages-1;
	}

	if($leftPages>0){
		for($i=$nowPage-$leftPages;$i<$nowPage;$i++){
			$linkPage .= "<a href='".$url."&".C('VAR_PAGE')."=$i'>".$i."</a>";
		}
	}
	$linkPage .= " <strong>".$nowPage."</strong>";
	if($rightPages>0){
		for($i=$nowPage+1;$i<=$nowPage+$rightPages;$i++){
			$linkPage .= "<a href='".$url."&".C('VAR_PAGE')."=$i'>".$i."</a>";
		}
	}
	// << < > >>
	if( $nowPage <= $halfRoll || $totalPages <= $rollPage ){
		$theFirst = "";
		$prePage = "";
	}else{
		$preRow =  $nowPage-$rollPage;
		$prePage = "<a href='".$url."&".C('VAR_PAGE')."=$preRow' >上".$rollPage."页</a>";
		$theFirst = "<a href='".$url."&".C('VAR_PAGE')."=1' >1..</a>";
	}

	if( ($totalPages-$nowPage) < $halfRoll || $totalPages <= $rollPage ){
		$nextPage = "";
		$theEnd="";
	}else{
		$nextRow = $nowPage+$rollPage;
		$theEndRow = $totalPages;
		$nextPage = "<a href='".$url."&".C('VAR_PAGE')."=$nextRow' >下".$rollPage."页</a>";
		$theEnd = "<a href='".$url."&".C('VAR_PAGE')."=$theEndRow' >..{$theEndRow}</a>";
	}

	if( ( $totalPages+1 - $halfRoll ) == $nowPage || $totalPages == $nowPage ){
		$theEnd = "";
	}
	 
	//页信息统计与分页
	$headerPage = "<label><input name='custompage' title='".L('custompage')."' class='px' onkeydown=\"if(event.keyCode==13) {window.location='".$url."&".C('VAR_PAGE')."='+this.value; doane(event);}\" type='text' size='2' value='".$nowPage."'>
    	<span title=\"".L('app_page')." ".$totalPages." ".L('page')."\"> / ".$totalPages." ".L('page')."</span>
    	</label>";

	if( $totalPages > 1 )
		$pageStr = '<div class="pg">'.$upPage.$theFirst.$linkPage.$theEnd.$headerPage.$downPage.'</div>';

	return $pageStr;
}

/**
  +----------------------------------------------------------
 * 分页显示
 * 用于在页面显示的分页栏的输出
 +----------------------------------------------------------
 * @param int $num 总行数
 * @param int $perpage 每页显示条数
 * @param int $curpage 当前页
 * @return void|string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-22 下午1:34:22
 +----------------------------------------------------------
 */
function pageList($num, $perpage=12, $curpage, $mpurl, $maxpages = 0, $page = 10, $autogoto = FALSE, $simple = FALSE, $jsfunc = FALSE) {
	if ($num <= $perpage) {
		return '';
	}
	$ajaxtarget = !empty($_GET['ajaxtarget']) ? " ajaxtarget=\"".dhtmlspecialchars($_GET['ajaxtarget'])."\" " : '';
	
	$a_name = '';
	if(strpos($mpurl, '#') !== FALSE) {
		$a_strs = explode('#', $mpurl);
		$mpurl = $a_strs[0];
		$a_name = '#'.$a_strs[1];
	}
	if($jsfunc !== FALSE) {
		$mpurl = 'javascript:'.$mpurl;
		$a_name = $jsfunc;
		$pagevar = '';
	} else {
		$pagevar = 'page=';
	}
	
	$shownum = $showkbd = FALSE;
	$showpagejump = TRUE;

	$lang['prev'] = '';
	$lang['next'] = L('next_page');
		
	$lang['pageunit'] = L('page');
	$lang['total'] = L('app_page');
	$lang['pagejumptip'] = L('custompage');

	$dot = '...';

	$multipage = '';
	if($jsfunc === FALSE) {
		$mpurl .= strpos($mpurl, '?') !== FALSE ? '&amp;' : '?';
	}
	
	$realpages = 1;
	$page_next = 0;
	$page -= strlen($curpage) - 1;
	if($page <= 0) {
		$page = 1;
	}
	if($num > $perpage) {
	
		$offset = floor($page * 0.5);
	
		$realpages = @ceil($num / $perpage);
		$curpage = $curpage > $realpages ? $realpages : $curpage;
		$pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;
	
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$page_next = $to;
		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.$pagevar.'1'.$a_name.'" class="first"'.$ajaxtarget.'>1 '.$dot.'</a>' : '').
		($curpage > 1 && !$simple ? '<a href="'.$mpurl.$pagevar.($curpage - 1).$a_name.'" class="prev"'.$ajaxtarget.'>'.$lang['prev'].'</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
			'<a href="'.$mpurl.$pagevar.$i.($ajaxtarget && $i == $pages && $autogoto ? '#' : $a_name).'"'.$ajaxtarget.'>'.$i.'</a>';
		}
		$multipage .= ($to < $pages ? '<a href="'.$mpurl.$pagevar.$pages.$a_name.'" class="last"'.$ajaxtarget.'>'.$dot.' '.$realpages.'</a>' : '').
		($showpagejump && !$simple && !$ajaxtarget ? '<label><input type="text" name="custompage" class="px" size="2" title="'.$lang['pagejumptip'].'" value="'.$curpage.'" onkeydown="if(event.keyCode==13) {window.location=\''.$mpurl.$pagevar.'\'+this.value; doane(event);}" /><span title="'.$lang['total'].' '.$pages.' '.$lang['pageunit'].'"> / '.$pages.' '.$lang['pageunit'].'</span></label>' : '').
		($curpage < $pages && !$simple ? '<a href="'.$mpurl.$pagevar.($curpage + 1).$a_name.'" class="nxt"'.$ajaxtarget.'>'.$lang['next'].'</a>' : '').
		($showkbd && !$simple && $pages > $page && !$ajaxtarget ? '<kbd><input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$mpurl.$pagevar.'\'+this.value; doane(event);}" /></kbd>' : '');
	
		$multipage = $multipage ? '<div class="pg">'.($shownum && !$simple ? '<em>&nbsp;'.$num.'&nbsp;</em>' : '').$multipage.'</div>' : '';
	}
	$maxpage = $realpages;
	return $multipage;
}

/**
 +----------------------------------------------------------
 * 将array格式化为url字符串
 +----------------------------------------------------------
 * @param array $gets
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-24 上午10:34:11
 +----------------------------------------------------------
 */
function url_implode($gets) {
	$arr = array();
	foreach ($gets as $key => $value) {
		if($value) {
			$arr[] = $key.'='.urlencode($value);
		}
	}
	return implode('&', $arr);
}

/**
 +----------------------------------------------------------
 * output_ajax 相关用到，暂时还没有研究具体作用
 +----------------------------------------------------------
 * @param unknown $content
 * @return unknown|mixed
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-22 下午2:43:49
 +----------------------------------------------------------
 */
function output_replace($content) {
	global $data;
	if(defined('IN_MODCP') || defined('IN_ADMINCP')) return $content;
	if(!empty($data['setting']['output']['str']['search'])) {
		if(empty($data['setting']['domain']['app']['default'])) {
			$data['setting']['output']['str']['replace'] = str_replace('{CURHOST}', $data['siteurl'], $data['setting']['output']['str']['replace']);
		}
		$content = str_replace($data['setting']['output']['str']['search'], $data['setting']['output']['str']['replace'], $content);
	}
	if(!empty($data['setting']['output']['preg']['search'])) {
		if(empty($data['setting']['domain']['app']['default'])) {
			$data['setting']['output']['preg']['search'] = str_replace('\{CURHOST\}', preg_quote($data['siteurl'], '/'), $data['setting']['output']['preg']['search']);
			$data['setting']['output']['preg']['replace'] = str_replace('{CURHOST}', $data['siteurl'], $data['setting']['output']['preg']['replace']);
		}

		$content = preg_replace($data['setting']['output']['preg']['search'], $data['setting']['output']['preg']['replace'], $content);
	}

	return $content;
}

/**
 +----------------------------------------------------------
 * ajax页面输出时用到
 +----------------------------------------------------------
 * @return Ambigous <mixed, unknown>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-3-22 下午2:44:21
 +----------------------------------------------------------
 */
function output_ajax() {
	global $data;
	$s = ob_get_contents();
	ob_end_clean();
	$s = preg_replace("/([\\x01-\\x08\\x0b-\\x0c\\x0e-\\x1f])+/", ' ', $s);
	$s = str_replace(array(chr(0), ']]>'), array(' ', ']]&gt;'), $s);
	$havedomain = implode('', $data['setting']['domain']['app']);
	if($data['setting']['rewritestatus'] || !empty($havedomain)) {
		$s = output_replace($s);
	}
	return $s;
}

/***************************************** 以下为小波个人添加 ***********************************************/

/**
 +----------------------------------------------------------
 * 删除个人小挂件
 +----------------------------------------------------------
 * @param 个人安装的应用ID $icoid
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-6-14 下午2:58:43
 +----------------------------------------------------------
 */
function dsk_delete_widget($gid){
	global $ts;
	if($widget=M("dsk_widget")->where("gid='{$gid}'")->find()){
		if($widget['uid']!=$ts['user']['uid'] && (!$ts['isSystemAdmin'])) return L('no_privilege');
		if($widget['idtype']=='pluginid' && $plugin=M("dsk_plugin")->where("pluginid='{$widget[typeid]}'")->find()){
			/*插件业务暂未实现*/
			if(is_file(DISCUZ_ROOT.'./dzz/plugin/'.$plugin['directory'].'fun.inc.php')){
				$plugin_delete_params=array('idtype'=>'gid','typeid'=>$widget['gid'],'uid'=>$widget['uid']);
				include DISCUZ_ROOT.'./dzz/plugin/'.$plugin['directory'].'fun.inc.php';
			}
		}
		M("dsk_widget")->where("gid='{$gid}'")->delete();
		return 'success';
	}else{
		return 'success';
	}
}
/**
 +----------------------------------------------------------
 * 删除个人应用图标及应用信息
 +----------------------------------------------------------
 * @param 个人安装的应用ID $icoid
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-17 下午2:58:43
 +----------------------------------------------------------
 */
function dsk_delete_ico($icoid){
	global $ts;
	if($icoarr=M('dsk_icos')->where("icoid='{$icoid}'")->find()){
		if($ts['user']['uid']!=$icoarr['uid'] && (!$ts['isSystemAdmin'])){ return L('no_privilege');}
		switch($icoarr['type']){
			case 'folder':case 'image':case 'blog':case 'video':case 'attach':case 'link':case 'app':
				$status = dsk_delete_source($icoarr['oid'],$icoarr['uid'],$icoarr['type']);
				if (!$status) {
					return $status;
				}
				break;
		}
		//插件功能
		if($icoarr['idtype']=='pluginid' && $plugin=M('dsk_plugin')->where("pluginid='{$icoarr[typeid]}'")->find()){
			/*该位置功能尚未实现*/
			if(is_file(DISCUZ_ROOT.'./dzz/plugin/'.$plugin['directory'].'fun.inc.php')){
				$plugin_delete_params=array('idtype'=>'icoid','typeid'=>$icoarr['icoid'],'uid'=>$icoarr['uid']);
				include DISCUZ_ROOT.'./dzz/plugin/'.$plugin['directory'].'fun.inc.php';
			}
		}
		if($icoarr['size']) M()->query("update " . C('DB_PREFIX') . "dsk_userconfig set usesize=usesize-{$icoarr['size']} where uid='{$icoarr['uid']}'");
		M('dsk_icos')->where("icoid='{$icoid}'")->delete();
		return 'success';
	}
	return 'success';
}
/**
 +----------------------------------------------------------
 * 删除桌面系统中的图片
 +----------------------------------------------------------
 * @param array|int $picids 图片ID
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-17 下午3:16:10
 +----------------------------------------------------------
 */
function delete_pic($picids){
	if(!is_array($picids)) $picids=array($picids);
	foreach($picids as $picid){
		$arr=array();
		$arr=M('dsk_pic')->where("picid='{$picid}'")->find();
		$arr['pic']=0;//pic_get($arr['filepath'], '', $arr['thumb'], $arr['remote'],0,1);
		if($arr['pic']) @unlink(DISCUZ_ROOT.'./'.$arr['pic']);
		if($arr['thumb']) @unlink(DISCUZ_ROOT.'./'.$arr['pic'].'.thumb.jpg');
	}
	M('dsk_pic')->where("picid IN (".dimplode($picids).")")->delete();
}
/**
 +----------------------------------------------------------
 * 删除桌面相关功能的源数据
 +----------------------------------------------------------
 * @param int $oid 数据ID
 * @param int $uid 用户ID
 * @param string $type 类型
 * @return boolean
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-17 下午3:15:21
 +----------------------------------------------------------
 */
function dsk_delete_source($oid,$uid,$type){
	global $ts;
	switch($type){
		case 'folder':
			if($folder=M('dsk_folder')->where("fid='{$oid}' and uid='{$uid}'")->find()){
				$ids=explode(',',$folder['ids']);
				foreach($ids as $icoid){
					dsk_delete_ico($icoid);
				}
			}
			M('dsk_folder')->where("fid='{$oid}' and uid='{$uid}'")->delete();
			break;

		case 'app'://删除应用
			//查询老系统中应用的id
			$oid = M('dsk_apps')->where('appid='.$oid)->getField('oid');
			//执行删除操作
			if (model('App')->where("`app_id`='{$oid}'")->getField('status') == '1')
				return 'default_app';
			if (model('App')->removeAppForUser($uid, $oid)) {
				model('App')->unsetUserInstalledApp($uid);
			} else {
				return 'uninstall_error';
			}
			
			break;
		case 'image':
			if($image=M('dsk_image')->where("picid='{$oid}' and uid='{$uid}'")->find()){
				if($image['aid']){
					$attach=M('dsk_attachment')->where("aid='{$image[aid]}'")->find();
					if($attach['copys']<=1){
						//@unlink($_G['setting']['attachdir'].$attach['attachment']);
						if($attach['thumb']){
							//@unlink($_G['setting']['attachdir'].$attach['attachment'].'.thumb.jpg');
						}
						M('dsk_attachment')->where("aid='{$image[aid]}'")->delete();
					}else{
						M('dsk_attachment')->where("aid='{$image[aid]}'")->save(array('copys'=>$attach['copys']-1));
					}
				}
				//����ɼ���
				if($image['cid']){
					$copys=M('dsk_cimage')->where("cid='{$image[cid]}'")->find();
					if($copys<=1){
						M('dsk_cimage')->where("cid='{$image[cid]}'")->delete();
					}else{
						M('dsk_cimage')->where("cid='{$image[cid]}'")->save(array('copys'=>$copys-1));
					}
				}
			}
			M('dsk_image')->where("picid='{$oid}' and uid='{$uid}'")->delete();
			break;
		case 'video':
			/* if($video=DB::fetch_first("select * from ".DB::table('dzz_video')." where vid='{$oid}' and uid='{$uid}'")){
				if($video['aid']){
					$attach=DB::fetch_first("select * from ".DB::table('dzz_attachment')." where aid='{$video[aid]}'");
					if($attach['copys']<=1){
						@unlink($_G['setting']['attachdir'].$attach['attachment']);
						if($attach['thumb']){
							@unlink($_G['setting']['attachdir'].$attach['attachment'].'.thumb.jpg');
						}
						DB::delete('dzz_attachment',"aid='{$image[aid]}'");
					}else{
						DB::update('dzz_attachment',array('copys'=>$attach['copys']-1),"aid='{$video[aid]}'");
					}
				}
				//����ɼ���
				if($video['cid']){
					$copys=DB::result_first("select copys from ".DB::table('dzz_cmusic')." where cid='{$video[cid]}'");
					if($copys<=1){
						DB::delete('dzz_cvideo',"cid='{$video[cid]}'");
					}else{
						DB::update('dzz_cvideo',array('copys'=>$copys-1),"cid='{$video[cid]}'");
					}
				}
			}
			DB::delete('dzz_video',"vid='{$oid}' and uid='{$uid}'"); */
			break;
		case 'music':
			/* if($music=DB::fetch_first("select * from ".DB::table('dzz_music')." where mid='{$oid}' and uid='{$uid}'")){
				if($music['aid']){
					$attach=DB::fetch_first("select * from ".DB::table('dzz_attachment')." where aid='{$music[aid]}'");
					if($attach['copys']<=1){
						@unlink($_G['setting']['attachdir'].$attach['attachment']);
						if($attach['thumb']){
							@unlink($_G['setting']['attachdir'].$attach['attachment'].'.thumb.jpg');
						}
						DB::delete('dzz_attachment',"aid='{$image[aid]}'");
					}else{
						DB::update('dzz_attachment',array('copys'=>$attach['copys']-1),"aid='{$music[aid]}'");
					}
				}
				//����ɼ���
				if($music['cid']){
					$copys=DB::result_first("select copys from ".DB::table('dzz_cmusic')." where cid='{$music[cid]}'");
					if($copys<=1){
						DB::delete('dzz_cmusic',"cid='{$music[cid]}'");
					}else{
						DB::update('dzz_cmusic',array('copys'=>$copys-1),"cid='{$music[cid]}'");
					}
				}
			}
			DB::delete('dzz_music',"mid='{$oid}' and uid='{$uid}'"); */
			break;
		case 'link':
			if($link=M('dsk_link')->where("lid='{$oid}' and uid='{$uid}'")->find()){
				//����ɼ���
				$cid = $link['cid'];
				if($cid){
					$copys=M('dsk_clink')->where("cid='{$cid}'")->getField("copys");
					if($copys<=1){
						M('dsk_clink')->where("cid='{$cid}'")->find();
					}else{
						M('dsk_clink')->where("cid='{$cid}'")->save(array('copys'=>$copys-1));
					}
				}
				if($link['did']){
					if($icon=M('dsk_icon')->where("did='{$link[did]}'")->find()){
						if($icon['check']<2 && $icon['copys']<2){
							//@unlink(DISCUZ_ROOT.'./'.$icon['pic']);
							M('dsk_icon')->where("did='{$link[did]}'")->delete();
						}else{
							M('dsk_icon')->where("did='{$link[did]}'")->save(array('copys'=>$icon['copys']-1));
						}
					}
				}
			}
			M('dsk_link')->where("lid='{$oid}' and uid='{$uid}'")->delete();
			
			break;
		case 'attach':
			/* if($attach1=DB::fetch_first("select * from ".DB::table('dzz_attach')." where qid='{$oid}' and uid='{$uid}'")){
				if($attach1['aid']){
					$attach=DB::fetch_first("select * from ".DB::table('dzz_attachment')." where aid='{$attach1[aid]}'");
					if($attach['copys']<=1){
						@unlink($_G['setting']['attachdir'].$attach['attachment']);
						if($attach['thumb']){
							@unlink($_G['setting']['attachdir'].$attach['attachment'].'.thumb.jpg');
						}
						DB::delete('dzz_attachment',"aid='{$attach1[aid]}'");
					}else{
						DB::update('dzz_attachment',array('copys'=>$attach['copys']-1),"aid='{$attach1[aid]}'");
					}
				}
			}
			DB::delete('dzz_attach',"qid='{$oid}' and uid='{$uid}'"); */
			break;
	}

	return true;
}
/**
 * +----------------------------------------------------------
 * 获取文件类型名称
 * +----------------------------------------------------------
 *
 * @param string $type
 *        	文件类型
 * @param string $ext
 *        	其它类型
 * @return string
 * @author 小波
 * +----------------------------------------------------------
 * 创建时间：2013-3-19 下午5:11:11
 * +----------------------------------------------------------
 */
function getFileTypeName($type, $ext) {
	$typename = '';
	switch ($type) {
		case 'image':
			$typename=L('typename_image');
			break;
		case 'video':
			$typename=L('typename_video');
			break;
		case 'music':
			$typename=L('typename_music');
			break;
		case 'attach':
			$typename=L('typename_attach');
			break;
		case 'app':
			$typename=L('typename_app');
			break;
		case 'link':
			$typename=L('typename_link');
			break;
		case 'folder':
			$typename=L('typename_folder');
			break;
	}

	$name = '';
	if ($ext == 'webdoc') {
		$name = 'Web文档';
	} elseif ($ext == 'txt') {
		$name = '文本文档';
	} else {
		$name = strtoupper ( $ext ) . ' ' . $typename;
	}

	return $name;
}
/**
 * +----------------------------------------------------------
 * 创建文件夹
 * +----------------------------------------------------------
 *
 * @param string $uid
 *        	用户ID
 * @param string $pfid
 * @return string
 * @author 小波
 * +----------------------------------------------------------
 * 创建时间：2013-6-14 下午5:11:11
 * +----------------------------------------------------------
 */
function dsk_create_folder($uid=0,$pfid=0,$desktop=0,$fname='',$ids='',$ficon='',$username=0){
	global $ts;
	if (!$uid) $uid = $ts['user']['uid'];
	if(empty($fname)) $fname=L('new_folder').get_newfolder_index($uid);
	if(empty($ficon)) $ficon=get_folder_sysicon();
	//if(!$uid) $uid=$_G['uid'];
	if(!$username) $username=$ts['user']['realname'];
	$setarr=array('fname'=>$fname,
			'uid'=>$uid,
			'ficon'=>$ficon,
			'username'=>$username,
			'pfid'=>$pfid,
			'iconview'=>M("dsk_iconview")->field("id")->order("disp")->find(),
			'desktop'=>$desktop,
			'dateline'=>time(),
			'friend'=>0
	);
	if (!empty($ids)) {
		$setarr['ids']=implode(',',$ids);
	}
	if($setarr['fid']=M("dsk_folder")->add($setarr)){
		$setarr1=array(
				'uid'=>$uid,
				'username'=>$username,
				'oid'=>$setarr['fid'],
				'name'=>$setarr['fname'],
				'url'=>'',
				'img'=>$setarr['ficon'],
				'type'=>'folder',
				'friend'=>0,
				'dateline'=>time(),
				'wwidth'=>0,
				'wheight'=>0,
				'open'=>0
		);
		if($setarr1['icoid']=M("dsk_icos")->add($setarr1)){
			$setarr1['fsize']=formatsize(0);
			$setarr1['ftype']=getFileTypeName('folder','');
			$setarr1['fdateline']=dgmdate($setarr1['dateline']);
			return array('icoarr'=>$setarr1,'folderarr'=>$setarr);
		}
	}
	return false;
}
/**
 +----------------------------------------------------------
 * 获取文件夹名称
 +----------------------------------------------------------
 * @param number $uid 用户ID
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-6-14 18:19
 +----------------------------------------------------------
 */
function get_newfolder_index($uid){
	global $ts;
	$index=array(0);
	$query=M("dsk_folder")->field("fname")->where("`uid`='{$uid}'")->findAll();
	foreach ($query as $value){
		if(strpos($value['fname'],L('new_folder'))===0)	$index[]=intval(str_replace(L('new_folder'),'',$value['fname']));
	}
	$index=array_unique($index);
	sort($index);
	for($i=0;$i<count($index);$i++){
		if($index[$i]>$i) return $i;
	}
	return $i;
}
/**
 +----------------------------------------------------------
 * 获取文件夹图标
 +----------------------------------------------------------
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-6-14 18:19
 +----------------------------------------------------------
 */
function get_folder_sysicon(){
	global $ts;
	$icon=M("dsk_sysicon")->where("`default`=1")->getField("icon");
	if(!$icon){
		$icon=M("dsk_sysicon")->where("1")->order("disp")->getField("icon");
	}
	if($icon){
		return C('APP_PUBLIC_PATH')."/images/sysicon/foldericon/".$icon;
	}else{
		return C('APP_PUBLIC_PATH').'/images/default/folder.png';
	}
}
/**
 +----------------------------------------------------------
 * 将一个字符串分割后返回某个索引的值
 +----------------------------------------------------------
 * @param string $str 要传入的字符串
 * @param number $index 要返回的索引值
 * @param string $split 分割符
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-12 上午10:48:28
 +----------------------------------------------------------
 */
function streq($str , $index=0 , $split=',' ){
	$return = explode($split,$str);
	return $return[$index];
}
/**
 +----------------------------------------------------------
 * 根据应用ID以及应用的状态值来获取应用的安装次数
 +----------------------------------------------------------
 * @param intval $appid 应用ID
 * @param number $status 应用状态值
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-11 下午5:09:10
 +----------------------------------------------------------
 */
function get_app_install_count($appid,$status=1){
	if (intval($status) == 1) {
		return M('user')->count();
	}else if (intval($status) == 2){
		return M('user_app')->where("app_id='$appid'")->group('uid')->count();
	}else{
		return 0;
	}
}
/**
 +----------------------------------------------------------
 * 更新评分信息
 +----------------------------------------------------------
 * @param int $id id
 * @param type $idtype
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-12 下午2:45:24
 +----------------------------------------------------------
 */
function updatescore($id,$idtype) {
	$stars=array('star1'=>0,'star2'=>0,'star3'=>0,'star4'=>0,'star5'=>0,'allstar'=>0);
	$result=M('dsk_score')->where("id='{$id}' and idtype='{$idtype}'")->findAll();
	foreach ($result as $value){
		if($value['star']==1) {$stars['star1']+=1;$stars['allstar']+=1;}
		if($value['star']==2) {$stars['star2']+=1;$stars['allstar']+=1;}
		if($value['star']==3) {$stars['star3']+=1;$stars['allstar']+=1;}
		if($value['star']==4) {$stars['star4']+=1;$stars['allstar']+=1;}
		if($value['star']==5) {$stars['star5']+=1;$stars['allstar']+=1;}
	}
	$score=0;
	for($i=1;$i<6;$i++){
		$score+=$stars['star'.$i]/$stars['allstar']*$i*2;
	}
	$score=round($score*100)/100;
	switch($idtype){
		case 'appid':
			M()->query("update " . C('DB_PREFIX') . "dsk_apps set hot=hot+2 ,star='{$score}', starnum='{$stars[allstar]}' where appid='{$id}'");
			break;
		case 'lid':
			M()->query("update " . C('DB_PREFIX') . "dsk_link set star='{$score}', starnum='{$stars[allstar]}' where lid='{$id}'");
			break;
		case 'vid':
			M()->query("update " . C('DB_PREFIX') . "dsk_video set star='{$score}', starnum='{$stars[allstar]}' where vid='{$id}'");
			break;
		case 'mid':
			M()->query("update " . C('DB_PREFIX') . "dsk_music set star='{$score}', starnum='{$stars[allstar]}' where mid='{$id}'");
			break;
		case 'picid':
			M()->query("update " . C('DB_PREFIX') . "dsk_image set star='{$score}', starnum='{$stars[allstar]}' where picid='{$id}'");
			break;
		case 'qid':
			M()->query("update " . C('DB_PREFIX') . "dsk_attach set star='{$score}', starnum='{$stars[allstar]}' where qid='{$id}'");
			break;
	}
}
/**
 +----------------------------------------------------------
 * 将桌面配置提交到配置文件中
 +----------------------------------------------------------
 * @param array $space 当前个人页面设置
 * @param int $icoid 安装完成后应用信息ID
 * @param string $container 桌面信息
 * @return boolean
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-17 下午2:04:29
 +----------------------------------------------------------
 */
function addtoconfig($icoid,$container){
	global $ts,$space;
	if(strpos($container,'icosContainer_body_')!==false){
		$desktop=str_replace('icosContainer_body_','',$container);
		if(!$desktop) return false;
		if(strpos($desktop,'sys_')!==false) $duid=0;
		else $duid=$ts['user']['uid'];
		if($space['self']<2 && strpos($desktop,'sys_')!==false){
			return false;
		}else{
			//获取用户的桌面设置信息
			if($config=M('dsk_userconfig')->field('screenlist')->where("uid='{$duid}'")->find()){
				$screenlist=unserialize(stripslashes($config['screenlist']));
				if($screenlist[$desktop]){
					$screenlist[$desktop]['icos'][]=$icoid;
					M('dsk_userconfig')->where("uid='{$duid}'")->save(array('screenlist'=>addslashes(serialize($screenlist))));
				}
				return true;
			}
		}
	}elseif(strpos($container,'icosContainer_folder_')!==false){
		$fid=str_replace('icosContainer_folder_','',$container);
		if(intval($fid)<1) return false;
		//获取文件夹设置信息
		if($folder=M('dsk_folder')->field('ids')->where("fid='{$fid}'")->find()){
			if($folder['ids']) $ids=explode(',',$folder['ids']);
			else $ids=array();
			$ids[]=$icoid;
			M('dsk_folder')->where("fid='{$fid}'")->save(array('ids'=>implode(',',$ids)));
			return true;
		}
	}
	return false;
}

/**
 +----------------------------------------------------------
 * 替换相关参数
 +----------------------------------------------------------
 * @param unknown $str
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-17 下午2:11:56
 +----------------------------------------------------------
 * @deprecated 该方法暂未使用
 */
function replace_param($str) {
	global $ts;
	$replacearr = array (
			'{SITEURL}' => getSiteUrl(),
			'{uid}' => $ts['user'] ['uid']
	);
	$search = array ();
	$replace = array ();
	foreach ( $replacearr as $key => $value ) {
		$search [] = $key;
		$replace [] = $value;
	}
	return str_replace ( $search, $replace, $str );
}

/**
 +----------------------------------------------------------
 * 根据应用ID获取应用相关详细信息
 +----------------------------------------------------------
 * @param member $appid 应用ID
 * @return array | null
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-12 上午9:39:59
 +----------------------------------------------------------
 */
function getapp($appid){
	if (empty($appid) || intval($appid) < 1) {
		return null;
	}
	$app = M()->table(C('DB_PREFIX') . 'dsk_apps p')
	->join( C('DB_PREFIX') . 'app_group c ON FIND_IN_SET( c.group_id,p.classid )' )
	->field( 'p.*,c.group_name' )
	->where( "p.appid = '" . $appid . "'" )
	->find();
	//安装次数统计
	$app['setupnum'] = get_app_install_count($app['app_id'],$app['status']);
	return $app;
}
/**
 +----------------------------------------------------------
 * 获取应用截图图片路径 <暂未完善>
 +----------------------------------------------------------
 * @param string $filepath 图片存放路径
 * @param string $type 类型
 * @param string $thumb 缩略图
 * @param string $remote 是否远程存储
 * @param number $return_thumb 是否返回缩略图
 * @param string $hastype 
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-12 上午10:00:41
 +----------------------------------------------------------
 */
function pic_get($filepath, $type, $thumb, $remote, $return_thumb=1, $hastype = '') {
	$url = $filepath;
	if($return_thumb && $thumb) $url = getimgthumbname($url);
	if($remote > 1 && $type == 'album') {
		$remote -= 2;
		$type = 'forum';
	}
	$type = $hastype ? '' : $type.'/';
	return ( $remote ? '' : '' ) . $type . $url;
}
/**
 +----------------------------------------------------------
 * 获取缩略图路径 <暂未完善>
 +----------------------------------------------------------
 * @param string $fileStr 文件路径
 * @param string $extend
 * @param string $holdOldExt
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-12 上午10:04:23
 +----------------------------------------------------------
 */
function getimgthumbname($fileStr, $extend='.thumb.jpg', $holdOldExt=true) {
	if(empty($fileStr)) {
		return '';
	}
	if(!$holdOldExt) {
		$fileStr = substr($fileStr, 0, strrpos($fileStr, '.'));
	}
	$extend = strstr($extend, '.') ? $extend : '.'.$extend;
	return $fileStr.$extend;
}
/**
 +----------------------------------------------------------
 * 注销cookies
 +----------------------------------------------------------
 * @param unknown $var
 * @param string $value
 * @param number $life
 * @param number $prefix
 * @param string $httponly
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-15 下午4:15:57
 +----------------------------------------------------------
 */
function dsetcookie($var, $value = '', $life = 0, $prefix = 1, $httponly = false) {
	global $ts;
	
	$config = $ts['config']['cookie'];

	$ts['cookie'][$var] = $value;
	$var = ($prefix ? $config['cookiepre'] : '').$var;
	$_COOKIE[$var] = $value;

	if($value == '' || $life < 0) {
		$value = '';
		$life = -1;
	}

	if(defined('IN_MOBILE')) {
		$httponly = false;
	}

	$life = $life > 0 ? getglobal('timestamp') + $life : ($life < 0 ? getglobal('timestamp') - 31536000 : 0);
	$path = $httponly && PHP_VERSION < '5.2.0' ? $config['cookiepath'].'; HttpOnly' : $config['cookiepath'];

	$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	if(PHP_VERSION < '5.2.0') {
		setcookie($var, $value, $life, $path, $config['cookiedomain'], $secure);
	} else {
		setcookie($var, $value, $life, $path, $config['cookiedomain'], $secure, $httponly);
	}
}
/**
 +----------------------------------------------------------
 * 获取cookies
 +----------------------------------------------------------
 * @param unknown $key
 * @return string
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-15 下午4:30:50
 +----------------------------------------------------------
 */
function getcookie($key) {
	global $ts;
	return isset($ts['cookie'][$key]) ? $ts['cookie'][$key] : '';
}

function getuser_appgroup(){
	global $ts;
	$uid = intval($ts['user']['uid']);
	$ucinfo = arrayKeyTolower($_SESSION['ucInfo']);
	$identitytype = $ucinfo['identitytype'];
	//如果在统一身份系统中没有获取到用户类型 则从当前社区的分组表中获取
	if ( empty($identitytype) || intval($identitytype) == 0  ) {
		if ($uid) {
			$map['uid'] = $uid;
			$usergroup = M()->table(C('DB_PREFIX') . "user_group as G")
			->join(C('DB_PREFIX').'user_group_link as L ON G.user_group_id = L.user_group_id')
			->field('G.user_group_id as gid,G.title as gtitle')
			->where("L.uid ='{$uid}' AND G.isreg<>'-1'")
			->findAll();
			foreach ($usergroup as $group){
				$gids[] = $group['gid'];
			}
		}
	}else{
		$appgroup=M('app_group')->where("FIND_IN_SET('".$identitytype."',`role_private`)")->findall();
		foreach($appgroup as $group){
			$gids[]=$group['group_id'];
		}
		//$gids = explode( ',' , $identitytype );
	}
	//是否为管理员
	//if( true == $ts['isSystemAdmin'] ){
		$gids[] = 1;
	//}
	return $gids;
}

/**
 +----------------------------------------------------------
 * 获取当前用户安装的应用列表
 +----------------------------------------------------------
 * @param intval $gid 分组ID 0为全部
 * @param string $field 需查询的字段信息 默认为：app_id, group_id, app_alias
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-22 上午10:56:07
 +----------------------------------------------------------
 */
function getuser_apps($gid=0, $field=""){
	global $ts;
	if (empty($field)) {
		$field = "app_id, group_id, app_alias";
	}
	//获取与我相关的分组ID
	$gids = getuser_appgroup();
	//组合查询语句
	$groupstr = array();
	if (is_array($gids)) {
		foreach ($gids as $gid){
			$groupstr[] = "FIND_IN_SET('".$gid."',`group_id`)";
		}
	}else{
		$groupstr[] = "FIND_IN_SET('".intval($gids)."',`group_id`)";
	}
	//查询我有权限看的应用结果
	$myapps = M('app')
	->field($field)
	->where("`status`<>0 AND ( ".implode( ' OR ' , $groupstr )." )")
	->order('display_order ASC')
	->findAll();
	//将结果重新以ID为下标的数组
	$return = array();
	foreach ($myapps as $app){
		$return[$app['app_id']] = $app;
	}
	
	return $return;
}
/**
 +----------------------------------------------------------
 * 根据应用ID获取应用积分相关信息
 +----------------------------------------------------------
 * @param number $appid
 * @return multitype:|Ambigous <string, number>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-25 上午9:24:19
 +----------------------------------------------------------
 */
function get_core($appid=0){
	if (empty($appid) || intval($appid) == 0) {
		return array();;
	}
	//评价相关信息
	$score=M('dsk_score')->field("pid,COUNT(pid) as starnum,SUM(star) as star,dateline")->where("id='{$appid}' and idtype='appid'")->find();
	if(empty($score)){
		$score=array('star'=>0);
	}
	//计算相关信息
	$score = compute_core($score['star'], $score['starnum']);
	return $score;
}
/**
 +----------------------------------------------------------
 * 计算评价评分数据
 +----------------------------------------------------------
 * @param number $star 总分数
 * @param number $starnum 安装次数
 * @return Ambigous <string, number>
 * @author 小波
 +----------------------------------------------------------
 * 创建时间：2013-4-25 上午10:59:08
 +----------------------------------------------------------
 */
function compute_core($star=0, $starnum=0){
	$score['corestyle'] = ( intval($star) / intval($starnum) ) * 20;
	//计算当前评分的百分比信息
	$star = (((( intval($star) / intval($starnum) ) * 20 - 1) * 10000 ) / 10000 )/ 10;
	$score['star'] = $star < 0 ? '0' : $star;
	return $score;
}

/**
 +----------------------------------------------------------
 * 获取用户安装的应用id列表
 +----------------------------------------------------------
 * @return Ambigous <string, number>
 * @author 小波 2013-6-13
 +----------------------------------------------------------
 */
function getmyappid(){
	global $ts;
	
	//获取已安装的所有应用
	$map['uid'] = array(array('eq',-1),array('eq',$ts['user']['uid']), 'or');
	$apps = M()->table(C('DB_PREFIX') . 'dsk_icos')
	->where($map)
	->findAll();

	foreach ($apps as $app){
		$myicos[$app['oid']] = $app['icoid'];
	}
	
	return $myicos;
}

/**
 +----------------------------------------------------------
 * 判断提交是否正确
 +----------------------------------------------------------
 * @return Ambigous <string, number>
 * @author 小波 2013-6-13
 +----------------------------------------------------------
 * */
function submitcheck($var) {
	if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
		if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))) {
			return true;
		} else {
			exit('Invalid Submit.');
		}
	} else {
		return false;
	}
}

/**
 +----------------------------------------------------------
 * 右键新建的链接
 +----------------------------------------------------------
 * @param 
 * @author 小波 2013-6-17
 +----------------------------------------------------------
 */
function linktourl($link,$uid,$friend,$container){
	global $ts;
	$parseurl=parse_url($link);
	$host=$parseurl['host'];
	$clink=array();
	if(!$clink=M('dsk_clink')->where("url='{$link}'")->find()){
		$arr=array();
		if($info=getmetatags($link)){
			$arr['title']=$info['title'];
			$arr['description']=is_null($info['description']) ? '' : $info['description'];
			$clink=array(
					'url'=>$link,
					'img'=>'',
					'desc' =>$arr['description'],
					'title' => $arr['title'],
					'copys' => 0,
					'dateline'=>time()
			);
			$clink['cid']=M("dsk_clink")->add($clink);
		}
	}
	$icondata=getUrlIcon($link,$uid);
	$sourcedata=array(
			'uid'=>$ts['user']['uid'],
			'username'=>$ts['user']['realname'],
			'url'=>$link,
			'img'=>$icondata['img'],
			'desc' =>$clink['desc'],
			'title' => $clink['title'],
			'copys' => 1,
			'cid'=>$clink['cid'],
			'did'=>$icondata['did'],
			'dateline'=>time()

	);
	if($sourcedata['lid']=M("dsk_link")->add(daddslashes($sourcedata))){
		M("dsk_clink")->where("cid='{$clink[cid]}'")->save(array('copys'=>$clink['copys']+1));
		$icoarr=array(
				'uid'=>$ts['user']['uid'],
				'username'=>$ts['user']['realname'],
				'oid'=>$sourcedata['lid'],
				'name'=>$sourcedata['title']?$sourcedata['title']:$host,
				'url'=>$sourcedata['url'],
				'img'=>$sourcedata['img'],
				'type'=>'link',
				'friend'=>$friend,
				'dateline'=>time(),
				'titlebuttons'=>'home,refresh,detail,min,max,close'
		);
		$icoarr['icoid']=M("dsk_icos")->add(daddslashes($icoarr));
	}

	if($icoarr['icoid'] ){
		if(addtoconfig($icoarr['icoid'],$container)){
			$icoarr['container']=$container;
			$icoarr['fsize']=formatsize($icoarr['size']);
			$icoarr['ftype']=getFileTypeName($icoarr['type'],$icoarr['ext']);
			$icoarr['fdateline']=date("Y-m-d",$icoarr['dateline']);
			return $icoarr;
		}else{
			dsk_delete_ico($icoarr['icoid']);
			return array('error' => L('unknow_error'));
		}
	}

}
/**
 +----------------------------------------------------------
 * 获取url meta 信息
 +----------------------------------------------------------
 * @author 小波 2013-6-17
 +----------------------------------------------------------
 */
function getmetatags($link){
	global $_G;
	$arr=array(
			'title'=>'',
			'charset'=>'',
			'keywords'=>'',
			'description'=>'',
	);
	$chararr=array('GBK','GB2312','UTF-8','BIG5','iso-8859-1');
	$str = dsk_file_get_contents($link);
	if(!$str) return array();
	$arr=array();
	if(	preg_match("/charset=\"*(.*?)\"/i",$str,$matches2)){
		$arr['charset']=$matches2[1];
	}elseif(function_exists('mb_convert_encoding')) {
		if($char=mb_detect_encoding($str,$chararr)){
			$arr['charset']=$char;
		}else{
			$arr['charset']='';
		}
	}

	if(preg_match("/<title>(.*?)<\/title>/ims",$str, $matches1)){
		$arr['title']=trim(getstr($matches1[1],80),'...');
		if($arr['charset']) $arr['title']= auto_charset($arr['title'], $arr['charset'], 'utf-8');
	}
	if(preg_match("/<meta\s+name=\"*description\"*\s+content=\"(.*?)\"/i",$str,$desc)||preg_match("/<meta\s+content=\"(.*?)\"\s+name=\"*description\"*/i",$str,$desc)){
		$arr['description']=$desc[1];
		if($arr['charset']) $arr['description']=auto_charset($arr['description'],$arr['charset'], 'utf-8');
	}
	if(preg_match("/<meta\s+name=\"*keywords\"*\s+content=\"(.*?)\"/i",$str,$keyword) || preg_match("/<meta\s+content=\"(.*?)\"\s+name=\"*keywords\"*/i",$str,$keyword)){
		$arr['keywords']=$keyword[1];
		if($arr['charset']) $arr['keywords']=auto_charset($arr['keywords'],$arr['charset'], 'utf-8');
	}
	return $arr;
}
/**
 +----------------------------------------------------------
 * 获取link的图标
 +----------------------------------------------------------
 * @author 小波 2013-6-17
 +----------------------------------------------------------
 */
function getUrlIcon($link,$uid){
	global $ts;
	$rarr=array();
	$parse_url=parse_url($link);
	$host=$parse_url['host'];
	$host=preg_replace("/^www./",'',$host);//strstr('.',$host);
	//��ѯ�û��Զ���������
	if($usericon=M("dsk_usericon")->where("uid='{$uid}' and domain='{$host}'")->find()){
		if($usericon['pdid']) M()->query("update ".C("DB_PREFIX").('dsk_icon')." set copys=copys+1 where did='{$usericon[pdid]}'");
		return array('img'=>$usericon['pic'],'did'=>$usericon['pdid']);
	}elseif($icon=M("dsk_icon")->where("domain='{$host}' and `check`>0")->order("`check`")->find()){
		M("dsk_icon")->where("did='{$icon[did]}'")->save(array('copys'=>$icon['copys']+1));
		return array('img'=>__THEME__."/desktop/images/".$icon['pic'],'did'=>$icon['did']);
	}else{
		$source='';
		$ico=$parse_url['scheme'].'://'.$host.'/favicon.ico';
		$ico_not_www=$parse_url['scheme'].'://www.'.$parse_url['host_not_www'].'/favicon.ico';
		if($ico && check_remote_file_exists($ico)) $source=$ico;
		elseif($ico_not_www && check_remote_file_exists($ico_not_www)) $source=$ico_not_www;
		if($source){
			$subdir = $subdir1 = $subdir2 = '';
			$subdir1 = date('Ym');
			$subdir2 = date('d');
			$subdir = $subdir1.'/'.$subdir2.'/';
			$target='icon/'.$subdir.''.$host.'_'.strtolower(random(8)).'.png';
			$target_attach=__THEME__."/desktop/images/".$target;
			$targetpath = dirname($target_attach);
			dmkdir($targetpath);
			ico_png($source,$target_attach);
			if(is_file($target_attach)){
				if($did=M("dsk_icon")->add(array('domain'=>$host,'pic'=>$target,'dateline'=>time(),'check'=>1,'uid'=>$uid,'username'=>$ts['user']['realname'],'copys'=>1))){
					return array('img'=>__THEME__."/desktop/images/".$target,'did'=>$did);
				}
			}
		}
	}
	return array('img'=>__THEME__.'/desktop/images/default/e.png','did'=>0);
}
/**
 +----------------------------------------------------------
 * 创建指定的文件夹
 +----------------------------------------------------------
 * @author 小波 2013-6-17
 +----------------------------------------------------------
 */
function dmkdir($dir, $mode = 0777, $makeindex = TRUE){
	if(!is_dir($dir)) {
		dmkdir(dirname($dir), $mode, $makeindex);
		@mkdir($dir, $mode);
		if(!empty($makeindex)) {
			@touch($dir.'/index.html'); @chmod($dir.'/index.html', 0777);
		}
	}
	return true;
}
/**
 +----------------------------------------------------------
 * ICO PNG
 +----------------------------------------------------------
 * @author 小波 2013-6-17
 +----------------------------------------------------------
 */
function ico_png($source,$target){
	require(SITE_PATH.'/addons/libs/ico.class.php');
	$oico=new Ico($source);
	$max=-1;
	$data_length=0;
	for($i=0; $i<$oico->TotalIcons(); $i++){
		$data=$oico->GetIconInfo($i);
		if($data['data_length']>$data_length){
			$data_length=$data['data_length'];
			$max=$i;
		}
	}
	if($max>=0 && imagepng($oico->GetIcon($max),$target)){
		return true;
	}else return false;
}
/**
 +----------------------------------------------------------
 * 检查文件是否存在
 +----------------------------------------------------------
 * @author 小波 2013-6-17
 +----------------------------------------------------------
 */
function check_remote_file_exists($url){
	set_time_limit(120);
	$u = parse_url($url);

	if(!$u || !isset($u['host'])) return false;

	$h = get_headers($url);
	if(!$h || !isset($h[0])) return false;
	$status = $h[0];
	return preg_match("/.*200\s{1}OK/i", $status) ? true : false;
}
/**
 +----------------------------------------------------------
 * 获取url内容
 +----------------------------------------------------------
 * @author 小波 2013-6-17
 +----------------------------------------------------------
 */
function dsk_file_get_contents($source){
	if(function_exists('curl_file_get_contents')!==false){
		if($data=curl_file_get_contents($source)){
			return $data;
		}else{
			return file_get_contents($source);
		}
	}else{
		return file_get_contents($source);
	}
	//$data=dfsockopen($source,  0, '',  '',  FALSE, '',  60,  TRUE);
}

/**
 +----------------------------------------------------------
 * 重新格式化url的地址
 +----------------------------------------------------------
 * @author 小波 2013-6-18
 +----------------------------------------------------------
 */
function replace_url($url){
	if(!empty($url)){
		if( !preg_match("/^(http|ftp|https|mms)\:\/\/(.+?)/i", $url)){
			return getSiteUrl().__ROOT__.'/'.$url;
		}
	}
	return $url;
}

?>