<?php
class CanlendarAddons extends SimpleAddons
{
	protected $version = '1.0';
	protected $author  = 'Rickeryu';
	protected $site    = 'http://hi.baidu.com/y284663247';
	protected $info    = '课程表应用';
	protected $pluginName = '课程表应用';
	protected $tsVersion  = "2.5";         // ts核心版本号

    /**
     * getHooksInfo
     * 获得该插件使用了哪些钩子聚合类，哪些钩子是需要进行排序的
     * @access public
     * @return void
     */
    public function getHooksInfo(){
        $hooks['list'] = array('CanlendarHooks');
        return $hooks;
    }

    public function adminMenu(){
        $menu = array(
					'CanlendarSet'	=> '课程表设置',
				);
        return $menu;
    }
	
    public function start()
    {
		 return true;
    }
	
    /**
     +----------------------------------------------------------
     * 挂件展示 - 课程表
     +----------------------------------------------------------
     * @author RickerYu 2013-7-24
     +----------------------------------------------------------
     */
    public function index(){
    	$weekname = array('1'=>'星期一','2'=>'星期二','3'=>'星期三','4'=>'星期四','5'=>'星期五','6'=>'星期六','0'=>'星期日');
    	//得到后台设置的周的起始日期
    	$candar = M('canlendar')->findAll();
    	$firstdate = $candar[0]['firstdate'];
    
    	//计算初始是周几
    	$firstwk = date("w",$firstdate) == 0 ? date("w",$firstdate) + 7 :date("w",$firstdate);
    	//计算今天与初始相差几天
    	$time = time() - strtotime($firstdate);
    	$day=(int)(($time-86400*365*$year-86400*30*$month)/86400);
    
    	//计算今天是第几个周的周几 ($firstwk+$day) % 7,这个地方为了和数组对应起来0表示第一个周，1表示第二个周
    	//第几周
    	$data['weeknum'] = (int)(($firstwk+$day)%7) == 0 ? (int)(($firstwk+$day)/7)-1 : (int)(($firstwk+$day)/7);
    	//周几
    	$data['thiswk'] = date("w",time());
    	$data['weekname'] = $weekname[$data['thiswk']];
    	$data['ajaxurl'] = Addons::createAddonUrl('Canlendar','home_index_ajax_canlendar');
    
    	$courseList = D('User', 'home')->getCourseByUid($this->mid);
    	$data['total'] = count($courseList);
    
    	if($data['total'] ){
    		//print_r($courseList);
    		ksort($courseList[$data['weeknum']][$data['thiswk']]);
    		$this->assign('courseList',$courseList[$data['weeknum']][$data['thiswk']]);
    	}
    	$this->assign($data);
    	$this->display('index');
    
    }
    
    //显示课程表时间设置的界面
    function CanlendarSet(){
    	$result = M('canlendar')->findAll();
    	$this->assign('firstdate', $result[0]['firstdate']);
    	$this->display('canlendarset');
    }
    /**
     *
     * @Title: upCanlendar
     * @Description: 后台更新课程表的初始日期
     * @author Ricker lhyfe@sohu.com
     */
    function upCanlendar(){
    	$candar = M('canlendar');
    	$result = $candar->findAll();
    	$map['firstdate'] = $_POST['firstdate'];
    	if(count($result)){
    		//后来进行的是更新的操作
    		$candar->where('id='.$result[0]['id'])->save($map);
    	}else{
    		//第一次操作，进行插入的操作
    		$candar->add($map);
    	}
    	//echo $candar->getLastSql();
    	//exit();
    }
    /**
     * @Title: CanlendarShow
     * @Description: 课程表显示操作
     * @author Ricker lhyfe@sohu.com
     */
//     function home_index_right_canlendar(){
//     	$courseList = D('User', 'home')->getCourseByUid($this->mid);
//     	$total = count($courseList);
//     	//print_r($courseList);
//     	if($total){
    
//     		//得到后台设置的周的起始日期
//     		$candar = M('canlendar')->findAll();
//     		$firstdate = $candar[0]['firstdate'];
    
//     		//计算初始是周几
//     		$firstwk = date("w",$firstdate) == 0 ? date("w",$firstdate) + 7 :date("w",$firstdate);
//     		//计算今天与初始相差几天
//     		$time = time() - strtotime($firstdate);
//     		$day=(int)(($time-86400*365*$year-86400*30*$month)/86400);
    
//     		//计算今天是第几个周的周几 ($firstwk+$day) % 7,这个地方为了和数组对应起来0表示第一个周，1表示第二个周
//     		//第几周
//     		$weeknum = (int)(($firstwk+$day)%7) == 0 ? (int)(($firstwk+$day)/7)-1 : (int)(($firstwk+$day)/7);
//     		//周几
//     		$thiswk = date("w",time());
    
//     		$ajaxurl = Addons::createAddonUrl('Canlendar','home_index_ajax_canlendar');
//     		//print_r($courseList);
//     		$this->assign('ajaxurl',$ajaxurl);
//     		$this->assign('weeknum',$weeknum);
//     		$this->assign('thiswk',$thiswk);
//     		$this->assign('total',$total);
//     		$this->assign('courseList',$courseList);
//     		$this->display('canlendarshow');
//     	}
//     }
    
//     function home_index_ajax_canlendar(){
//     	$wknum = isset($_POST['wknum']) ? $_POST['wknum'] : -1;
//     	if($wknum != -1){
//     		$courseList = D('User', 'home')->getCourseByUid($this->mid);
//     		$out = '<table width="100%" border="1" bordercolor="#CCCCCC" cellspacing="0" cellpadding="0">';
//     		for($i=0;$i<7;$i++){
//     			if($i == 0){
//     				$out .='<tr style="background:#b8abfa;">';
//     				$out .='	<td width="12">&nbsp;</td>';
//     				$out .='	<td width="31" align="center">一</td>';
//     				$out .='	<td width="32" align="center">二</td>';
//     				$out .='	<td width="33" align="center">三</td>';
//     				$out .='	<td width="30" align="center">四</td>';
//     				$out .='	<td width="30" align="center">五</td>';
//     				$out .='	<td width="32" align="center">六</td>';
//     				$out .='</tr>';
//     			}else{
//     				$out .="<tr>";
//     				$out .='<td width="12" style="background:#b8abfa;text-align:center;">'.$i.'</td>';
//     				for($j=1;$j<=6;$j++){
//     					$out .='<td align="center" >';
//     					if($courseList[$wknum][$j.$i]['kcm'] !=''){
//     						//$out .='	<div style="width:100%;height:15px;line-height: 15px;overflow:hidden">'.$courseList[$wknum][$j.$i]['kcm'].'</div>';
//     						$out .='<a href="#" onmouseover="javascipt:showCourse('.$j.$i.','.$wknum.');"  onmouseout="javascript:closeCourse();" title="'.$courseList[$wknum][$j.$i]['kcm'].'" class="aCourse" id="aCourse'.$j.$i.'">'.$courseList[$wknum][$j.$i]['kcm'].'</a>';
//     						$out .='<div class="divCourse" id="show$j.$i">';
//     						$out .='<div class="floatCourse" id="ShowCourse'.$j.$i.'">';
//     						$out .='<table width="100%" border="0" cellspacing="0" cellpadding="0">';
//     						$out .='<tr>';
//     						$out .='<td height="30" width="91" bgcolor="#B8ABFA"><strong>课程名称：</strong></td>';
//     						$out .='</tr>';
//     						$out .='<tr>';
//     						$out .='<td height="30">'.$courseList[$wknum][$j.$i]['kcm'].'</td>';
//     						$out .='</tr>';
//     						$out .='<tr>';
//     						$out .='<td height="30" bgcolor="#B8ABFA"><strong>授课老师：</strong></td>';
//     						$out .='</tr>';
//     						$out .='<tr>';
//     						$out .='<td height="30">'.$courseList[$wknum][$j.$i]['jsxm'].'</td>';
//     						$out .='</tr>';
//     						$out .='<tr>';
//     						$out .='<td height="30" bgcolor="#B8ABFA"><strong>上课地点：</strong></td>';
//     						$out .='</tr>';
//     						$out .='<tr>';
//     						$out .='<td height="30">'.$courseList[$wknum][$j.$i]['jxlm'].' '.$courseList[$wknum][$j.$i]['jsm'].'</td>';
//     						$out .='</tr>';
//     						$out .='</table>';
//     						$out .='</div>';
//     						$out .='</div>';
//     					}
    
//     					$out .='</td>';
//     				}
//     				$out .="</tr>";
//     			}
//     		}
//     		$out .='</table>';
//     		echo $out;
//     	}
//     }
    
    
    
	public function install()
	{
		$db_prefix = C('DB_PREFIX');
		$sql = array(
			"CREATE TABLE IF NOT EXISTS `{$db_prefix}canlendar` (
			  `id` int(11) NOT NULL auto_increment,
			  `firstdate` date NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
		);

		foreach ($sql as $v)
			$res = M()->execute($v);
		if (false !== $res) {
			return true;
		}
		return true;
	}

	public function uninstall()
	{
		$db_prefix = C('DB_PREFIX');
		$sql = array(
			"DROP TABLE IF EXISTS `{$db_prefix}canlendar`;"
		);

		foreach ($sql as $v)
			$res = M()->execute($v);

		if (false !== $res) {
			return true;
		}
	}
}