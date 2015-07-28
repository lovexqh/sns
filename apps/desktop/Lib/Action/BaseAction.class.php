<?php
/**
 +------------------------------------------------------------------------------
 * Web Desktop应用 - BaseAction 公共基础Action
 +------------------------------------------------------------------------------
 * @category	desktop
 * @package		Lib/Action
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-18 下午1:57:47
 +------------------------------------------------------------------------------
 */
class BaseAction extends Action{

	var $appName;
	var $style = array();
	var $space = array();
	var $config = array();
	var $setting = array();
	var $prefix;
	var $formhash;
	protected $userName;
	
	/**
	 +----------------------------------------------------------
	 * 执行应用初始化
	 +----------------------------------------------------------
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-18 下午1:57:23
	 +----------------------------------------------------------
	 */
	public function _initialize() {
		$this->prefix = C('DB_PREFIX');
		global $ts,$data;
		
		$this->userName = $ts['user']['realname'];
		
		//2013/05/13小波修改支持自动判断是否大桌面调用并调用相应组的模板
		$_SESSION['system'] = 'desktop';
		
		//获取浏览器版本
		$agent=$_SERVER["HTTP_USER_AGENT"];
		if(strpos($agent,"MSIE 6.0"))  $data['browser']='ie6';
		if(strpos($agent,"MSIE 7.0"))  $data['browser']='ie7';
		if(strpos($agent,"MSIE 8.0"))  $data['browser']='ie8';
		
		$data['basescript'] = 'dsk';
		
		//配置相关array
		$data['config']['cookie']['cookiepre'] = C ( 'SECURE_CODE' ) . '_';
		$data['config']['cookie']['cookiedomain'] = '';
		$data['config']['cookie']['cookiepath'] = '/';
		
		//当前版本号
		$data['desktop_version'] = $ts['site']['site_system_version_number'];
		
		//暂时不知道是个什么东西
		$data["currenturl_encode"]="aHR0cDovLzEyNy4wLjAuMS9kaXNjdXovZHp6LnBocA==";

		//当前的样式数据
		$data['style'] = empty($this->style) ? $this->style = $this->_getStyle() : $this->style;
		
		//当前的space配置
		global $space;
		$data['space'] = empty($this->space) ? $this->space = $this->_getSpace() : $this->space;
		$space = $this->space;
		
		//获取setting相关配置
		$data['setting'] = empty($this->setting) ? $this->setting = $this->_getSetting() : $this->setting;
		
		//获取setting相关配置
		$data['config'] = empty($this->config) ? $this->config = $this->_getConfig() : $this->config;
		
		$data['setting']['dsk_sysconfig'] = serialize(get_config());	//获取系统配置
		
		//获取公告信息
		$announce = M('announce')->order('cTime DESC')->findAll();
		$data['announce']['cookie'] = 3600;
		$data['announce']['data'] = $announce;
		
		//特殊变量赋值
		$data['verhash'] = $data['style']['verhash'];
		
		$this->formhash = '8c2d36c1';
		
		$this->assign($data);
		
		$this->appName = $ts['app']['app_alias'];
		if($this->mid==$this->uid){
			$userName = '我';
		}else{
			$userName = getUserName($this->uid);
		}
		$data['userName'] = $userName;
		$this->setTitle($userName . '的' . $this->appName);
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取style相关设置
	 +----------------------------------------------------------
	 * @return array
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-18 下午5:32:23
	 +----------------------------------------------------------
	 */
	private function _getStyle(){
		$style = array(
			'styleid'=>'1',
    		'name'=>'默认风格',
		    'available'=>'',
   			'templateid'=>'1',
    		'extstyle'=>array(
								array("./template/default/style/t1","红","#BA350F"),
								array("./template/default/style/t2","青","#429296"),
								array("./template/default/style/t3","橙","#FE9500"),
								array("./template/default/style/t4","紫","#9934B7"),
								array("./template/default/style/t5","蓝","#0053B9")
							),
	          'tplname'=>'默认模板套系',
	          'directory'=>'./template/default',
	          'copyright'=>'山东锐杰网格信息技术有限公司',
	          'tpldir'=>'./template/default',
	          'menuhoverbgcolor'=>'#005AB4',
	          'lightlink'=>'#FFF',
	          'floatbgcolor'=>'#FFF',
	          'dropmenubgcolor'=>'#FEFEFE',
	          'floatmaskbgcolor'=>'#000',
	          'dropmenuborder'=>'#DDD',
	          'specialbg'=>'#E5EDF2',
	          'specialborder'=>'#C2D5E3',
	          'commonbg'=>'#F2F2F2',
	          'commonborder'=>'#CDCDCD',
	          'inputbg'=>'#FFF',
	          'stypeid'=>'1',
	          'inputborderdarkcolor'=>'#848484',
	          'headerbgcolor'=>'',
	          'headerborder'=>'0',
	          'sidebgcolor'=>'',
	          'msgfontsize'=>'14px',
	          'bgcolor'=>'#FFF',
	          'noticetext'=>'#F26C4F',
	          'highlightlink'=>'#369',
	          'link'=>'#333',
	          'lighttext'=>'#999',
	          'midtext'=>'#666',
	          'tabletext'=>'#444',
	          'smfontsize'=>'0.83em',
	          'threadtitlefont'=>'Tahoma,Helvetica,SimSun,sans-serif',
	          'threadtitlefontsize'=>'14px',
	          'smfont'=>'Tahoma,Helvetica,sans-serif',
	          'titlebgcolor'=>'#E5EDF2',
	          'fontsize'=>'12px/1.5',
	          'font'=>'Tahoma,Helvetica,SimSun,sans-serif',
	          'styleimgdir'=>'static/image/common',
	          'imgdir'=>'static/image/common',
	          'boardimg'=>'static/image/common/logo.png',
	          'headertext'=>'#444',
	          'footertext'=>'#666',
	          'menubgcolor'=>'#2B7ACD',
	          'menutext'=>'#FFF',
	          'menuhovertext'=>'#FFF',
	          'wrapbg'=>'#FFF',
	          'wrapbordercolor'=>'#CCC',
	          'contentwidth'=>'630px',
	          'contentseparate'=>'#C2D5E3',
	          'inputborder'=>'#E0E0E0',
	          'menuhoverbgcode'=>'background: #005AB4 url("static/image/common/nv_a.png") no-repeat 50% -33px',
	          'floatbgcode'=>'background: #FFF',
	          'dropmenubgcode'=>'background: #FEFEFE',
	          'floatmaskbgcode'=>'background: #000',
	          'headerbgcode'=>'',
	          'sidebgcode'=>'background: url("static/image/common/vlineb.png") repeat-y 0 0',
	          'bgcode'=>'background: #FFF url("static/image/common/background.png") repeat-x 0 0',
	          'titlebgcode'=>'background: #E5EDF2 url("static/image/common/titlebg.png") repeat-x 0 0',
	          'menubgcode'=>'background: #2B7ACD url("static/image/common/nv.png") no-repeat 0 0',
	          'boardlogo'=>'<img src="static/image/common/logo.png" alt="Discuz! Board" border="0" />',
	          'bold'=>'bold',
	          'defaultextstyle'=>'',
	          'verhash'=>'58b'
		);		
		return $style;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取用户的个人相关设置
	 +----------------------------------------------------------
	 * @return number
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-4-19 上午10:25:05
	 +----------------------------------------------------------
	 */
	private function _getSpace(){
		global $ts;
		unset($space);
		//此处可以根据不同的ID看不同人的桌面，目前只做本人桌面
		$space = dskgetspace($this->mid);
		$space['uid'] = $this->mid;
		$space['self'] = intval($space['self']);
		if($ts['isSystemAdmin']==true) $space['self']=2;
		else $space['self']=2;
		
		return $space;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取相关的配置参数
	 +----------------------------------------------------------
	 * @return array
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-19 下午1:19:14
	 +----------------------------------------------------------
	 */
	private function _getConfig(){
		$config=array(
			    'memory'=>
				    array(
				      'prefix'=> "9ymAU7_",
				      'redis'=>
					      array(
					        'server'=> "",
					        'port'=>6379,
					        'pconnect'=>1,
					        'timeout'=> "0",
					        'serializer'=>1
					      ),
				      'memcache'=>
					      array(
					        'server'=> "",
					        'port'=>11211,
					        'pconnect'=>1,
					        'timeout'=>1,
					      ),
				      'apc'=>1,
				      'xcache'=>1,
				      'eaccelerator'=>1,
				    ),
			    'server'=>
				    array(
				      'id'=>1,
				    ),
			    'download'=>
				    array(
				      'readmod'=>2,
				      'xsendfile'=>
				      array(
				        'type'=> "0",
				        'dir'=> "/down/"
				      )
				    ),
			    'cache'=>
				    array(
				      'type'=> "sql"
				    ),
			    'output'=>
				    array(
				      'charset'=> "utf-8",
				      'forceheader'=>1,
				      'gzip'=> "0",
				      'tplrefresh'=>1,
				      'language'=> "zh_cn",
				      'staticurl'=> "static/",
				      'ajaxvalidate'=> "0",
				      'iecompatible'=> "0"
				    ),
			    'cookie'=>
				    array(
				      'cookiepre'=> "a1IO_2132_",
				      'cookiedomain'=> "",
				      'cookiepath'=> "/"
				    ),
			    'security'=>
			    array(
			      'authkey'=> "674467IdT8bcPbqc",
			      'urlxssdefend'=>1,
			      'attackevasive'=> "0",
			      'querysafe'=>
			      array(
			        'status'=>1,
			        'dfunction'=>
				        array(
				          '0'=> "load_file",
				          '1'=> "hex",
				          '2'=> "substring",
				          '3'=> "if",
				          '4'=> "ord",
				          '5'=> "char"
				        ),
			        'daction'=>
				        array(
				          '0'=> "intooutfile",
				          '1'=> "intodumpfile",
				          '2'=> "unionselect",
				          '3'=> "(select",
				          '4'=> "unionall",
				          '5'=> "uniondistinct"
				        ),
			        'dnote'=>
				        array(
				          '0'=> "/*",
				          '1'=> "*/",
				          '2'=> "#",
				          '3'=> "--",
				          '4'=> '"'
				        ),
			        'dlikehex'=>1,
			        'afullnote'=> "0"
			      )
			    ),
			    'admincp'=>
				    array(
				      'founder'=> "1",
				      'forcesecques'=> "0",
				      'checkip'=>1,
				      'runquery'=> "0",
				      'dbimport'=>1
				    ),
			    'remote'=>
				    array(
				      'on'=> "0",
				      'dir'=> "remote",
				      'appkey'=> "62cf0b3c3e6a4c9468e7216839721d8e",
				      'cron'=> "0"
				    ),
			    'input'=>
				    array(
				      'compatible'=>1
				    )
			  );
		return $config;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取setting 相关的参数
	 +----------------------------------------------------------
	  * @return array
	  * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-19 下午1:03:59
	 +----------------------------------------------------------
	 */
	private function _getSetting(){
		$setting = array(
		    'accessemail' => '',
		    'activityforumid' => '0',
		    'activityfield' => 'a:3:{s:8:"realname";s:12:"真实姓名";s:6:"mobile";s:6:"手机";s:2:"qq";s:5:"QQ号";}',
		    'activityextnum' => '0',
		    'activitypp' => '8',
		    'activitycredit' => '1',
		    'activitytype' => '朋友聚会
				    出外郊游
				    自驾出行
				    公益活动
				    线上活动',
			'adminemail' => 'admin@admin.com',
			'adminipaccess' => '',
		    'adminnotifytypes' => 'verifythread,verifypost,verifyuser,verifyblog,verifydoing,verifypic,verifyshare,verifycommontes,verifyrecycle,verifyrecyclepost,verifyarticle,verifyacommont,verifymedal,verify_1,verify_2,verify_3,verify_4,verify_5,verify_6,verify_7',
			'anonymoustext' => '匿名',
		    'advtype'=>array(),
		    'allowattachurl' => '0',
		    'allowdomain' => '0',
		    'alloweditpost' => '0',
		    'allowswitcheditor' => '1',
		    'allowviewuserthread'=> -1,
		    'archiver' => '1',
		    'archiverredirect' => '0',
		    'attachbanperiods' => '',
		    'attachdir' => 'H:/www/discuz/./data/attachment/',
		    'attachexpire' => '',
		    'attachimgpost' => '1',
		    'attachrefcheck' => '0',
		    'attachsave' => '3',
		    'attachurl' => 'data/attachment/',
		    'authkey' => '674467IdT8bcPbqc',
		    'authoronleft' => '1',
		    'autoidselect' => '0',
		    'avatarmethod' => '0',
		    'bannedmessages' => '1',
		    'bbclosed' => '1',
		    'bbname' => 'Discuz! Board',
		    'bbrules' => '0',
		    'bbrulesforce' => '0',
		    'bbrulestxt' => '',
		    'bdaystatus' => '0',
		    'binddomains' => 'a:0:{}',
		    'boardlicensed' => '0',
		    'cacheindexlife' => '0',
		    'cachethreaddir' => 'data/threadcache',
		    'cachethreadlife' => '0',
		    'censoremail' => '',
		    'censoruser' => '',
		    'closedallowactivation' => '0',
		    'commentfirstpost' => '1',
			'commentitem' => array('','','','','',''),
		    'commentnumber' => 0,
    		'creditnotice' => '1',
    		'creditsformula' => '$member[posts]+$member[digestposts]*5+$member[extcredits1]*2+$member[extcredits2]+$member[extcredits3]',
    		'creditsformulaexp' => '<u>总积分</u>=<u>发帖数</u>+<u>精华帖数</u>*5+<u>威望</u>*2+<u>金钱</u>+<u>贡献</u>',
		    'creditspolicy'=>
			    array(
		    		'post'=>array(),
		    		'reply'=>array(),
		    		'digest'=>array('1'=>10),
		    		'postattach'=>array(),
		    		'getattach'=>array(),
		    		'sendpm'=>array(),
		    		'search' =>array(),
		    		'promotion_visit' =>true,
		    		'promotion_register' =>true,
		    		'tradefinished' =>array(),
		    		'votepoll'=>array(),
		    		'lowerlimit'=>array()
				),
			'creditstax' => '0.2',
			'creditstrans' => '2',
			'dateconvert' => '1',
		    'dateformat' => 'Y-n-j',
		    'debateforumid' => '0',
		    'debug' => '1',
		    'defaulteditormode' => '1',
		    'delayviewcount' => '0',
		    'deletereason' => '',
		    'disallowfloat' => 'newthread',
		    'domainroot' => '',
		    'doublee' => '1',
		    'dupkarmarate' => '0',
		    'ec_account' => '',
		    'ec_contract' => '',
		    'ec_credit' =>
		    	array(
		     		'maxcreditspermonth' =>6,
				    'rank' =>
					      array(
					        '1'=>4,
					        '2'=>11,
					        '3'=>41,
					        '4'=>91,
					        '5'=>151,
					        '6'=>251,
					        '7'=>501,
					        '8'=>1001,
					        '9'=>2001,
					        '10'=>5001,
					        '11'=>10001,
					        '12'=>20001,
					        '13'=>50001,
					        '14'=>100001,
					        '15'=>200001
					      )
					    ),
		    'ec_maxcredits' => '1000',
		    'ec_maxcreditspermonth' => '0',
		    'ec_mincredits' => '0',
		    'ec_ratio' => '0',
		    'ec_tenpay_bargainor' => '',
		    'ec_tenpay_key' => '',
		    'postappend' => '0',
		    'editedby' => '1',
		    'editoroptions' => '6',
		    'edittimelimit' => '',
		    'exchangemincredits' => '100',
		    'extcredits'=>
			    array(
			      '1'=>array(
			        'img'=> "",
			        'title'=> "威望",
			        'unit'=> "",
			        'ratio'=>0,
			        'showinthread'=>NULL,
			        'allowexchangein'=>NULL,
			        'allowexchangeout'=>NULL
			      ),
			      '2'=>array(
			        'img'=> "",
			        'title'=> "金钱",
			        'unit'=> "",
			        'ratio'=>0,
			        'showinthread'=>NULL,
			        'allowexchangein'=>NULL,
			        'allowexchangeout'=>NULL
			      ),
			      '3'=>array(
			        'img'=> "",
			        'title'=> "贡献",
			        'unit'=> "",
			        'ratio'=>0,
			        'showinthread'=>NULL,
			        'allowexchangein'=>NULL,
			        'allowexchangeout'=>NULL
			      )
			   ),
		    'fastpost' => '1',
		    'forumallowside' => '0',
		    'fastsmilies' => '1',
		    'feedday' => '7',
		    'feedhotday' => '2',
		    'feedhotmin' => '3',
		    'feedhotnum' => '3',
		    'feedmaxnum' => '100',
		    'showallfriendnum' => '8',
		    'feedtargetblank' => '1',
		    'floodctrl' => '15',
		    'forumdomains' => 'a:0:{}',
		    'forumjump' => '0',
		    'forumlinkstatus' => '1',
		    'forumseparator' => '1',
		    'frameon' => '0',
		    'framewidth' => '180',
		    'friendgroupnum' => '8',
		    'ftp' =>
			    array(
			      'on' => '0',
			      'ssl' => '0',
			      'host' => '',
			      'port' => '21',
			      'username' => '',
			      'password' => '',
			      'attachdir' => '.',
			      'attachurl' => '/',
			      'hideurl' => '0',
			      'timeout' => '0',
			      'connid' =>0
				),
			'globalstick' => '1',
			'targetblank' => '0',
			'google' => '1',
			'groupstatus' => '0',
			'portalstatus' => '0',
			'followstatus' => '0',
			'collectionstatus' => '0',
			'guidestatus' => '0',
			'feedstatus' => '0',
			'blogstatus' => '0',
			'doingstatus' => '0',
			'albumstatus' => '0',
			'sharestatus' => '0',
			'wallstatus' => '0',
			'rankliststatus' => '0',
			'homestyle' => '0',
			'homepagestyle' => '0',
			'group_allowfeed' => '1',
			'group_admingroupids' => 'a:1:{i:1;s:1:"1";}',
			'group_imgsizelimit' => '512',
			'group_userperm' => 'a:21:{s:16:"allowstickthread";s:1:"1";s:15:"allowbumpthread";s:1:"1";s:20:"allowhighlightthread";s:1:"1";s:16:"allowstampthread";s:1:"1";s:16:"allowclosethread";s:1:"1";s:16:"allowmergethread";s:1:"1";s:16:"allowsplitthread";s:1:"1";s:17:"allowrepairthread";s:1:"1";s:11:"allowrefund";s:1:"1";s:13:"alloweditpoll";s:1:"1";s:17:"allowremovereward";s:1:"1";s:17:"alloweditactivity";s:1:"1";s:14:"allowedittrade";s:1:"1";s:17:"allowdigestthread";s:1:"3";s:13:"alloweditpost";s:1:"1";s:13:"allowwarnpost";s:1:"1";s:12:"allowbanpost";s:1:"1";s:12:"allowdelpost";s:1:"1";s:13:"allowupbanner";s:1:"1";s:15:"disablepostctrl";s:1:"0";s:11:"allowviewip";s:1:"1";}',
			'heatthread' =>
			    array(
			      'type' => '2',
			      'reply' =>5,
			      'recommend' =>3,
			      'period' => '15',
			      'iconlevels' =>
				      array(
				        '2'=>'200',
						'1'=>'100',
				        '0'=>'50'
				      )
		    	),
		    'guide' => 'a:2:{s:5:"hotdt";i:604800;s:8:"digestdt";i:604800;}',
		    'hideprivate' => '1',
		    'historyposts' => '0	7',
		    'hottopic' => '10',
		    'icp' => '',
		    'imagelib' => '0',
		    'imagemaxwidth' => 600,
		    'watermarkminheight' => 'a:3:{s:6:"portal";s:1:"0";s:5:"forum";s:1:"0";s:5:"album";s:1:"0";}',
		    'watermarkminwidth' => 'a:3:{s:6:"portal";s:1:"0";s:5:"forum";s:1:"0";s:5:"album";s:1:"0";}',
		    'watermarkquality' => 'a:3:{s:6:"portal";s:2:"90";s:5:"forum";i:90;s:5:"album";i:90;}',
		    'watermarkstatus' => 'a:3:{s:6:"portal";s:1:"0";s:5:"forum";s:1:"0";s:5:"album";s:1:"0";}',
		    'watermarktext' =>
				array(
					'text' =>
					      array(
					        'portal' =>'',
					        'forum' =>'',
					        'album' =>''
					      ),
		      		'fontpath' =>
					      array(
					        'portal' =>'',
					        'forum' =>'',
					        'album' =>''
					      ),
		      		'size' =>
					      array(
							'portal' =>'',
							'forum' =>'',
							'album' =>''
					      ),
		      		'angle' =>
					      array(
					        'portal' =>'',
					        'forum' =>'',
					        'album' =>''
					      ),
		      		'color' =>
					      array(
					        'portal' =>'',
					        'forum' =>'',
					        'album' =>''
					      ),
				      'shadowx' =>
						  array(
							'portal' =>'',
							'forum' =>'',
							'album' =>''
						),
				      'shadowy' =>
					      array(
					        'portal' =>'',
					        'forum' =>'',
					        'album' =>''
					      ),
				      'shadowcolor' =>
					      array(
					        'portal' =>'',
					        'forum' =>'',
					        'album' =>''
					      ),
			      	'translatex' =>
						array(
							'portal' =>'',
							'forum' =>'',
							'album' =>''
						),
					'translatey' =>
					      array(
					        'portal' =>'',
					        'forum' =>'',
					        'album' =>''
					      ),
				      'skewx' =>
					      array(
					        'portal' =>'',
					        'forum' =>'',
					        'album' =>''
					      ),
				      'skewy' =>
						 array(
							'portal' =>'',
							'forum' =>'',
							'album' =>''
						),
				),
		    'watermarktrans' => 'a:3:{s:6:"portal";s:2:"50";s:5:"forum";i:50;s:5:"album";i:50;}',
			'watermarktype' =>
			    array(
			      'portal' => 'png',
			      'forum' => 'png',
			      'album' => 'png',
			    ),
		    'indexhot' =>
			    array(
			      'status' => '0',
			      'limit' => '10',
			      'days' => '7',
			      'expiration' => '900',
			      'messagecut' => '200',
			      'width' =>100,
			      'height' =>70
			    ),
		    'indextype' => 'classics',
		    'infosidestatus' =>false,
		    'initcredits' => '0,0,0,0,0,0,0,0,0',
		    'inviteconfig' =>
			    array(
					'invitecodeprompt' => ''
				),
			'ipaccess' => '',
			'jscachelife' => '1800',
			'jsdateformat' => '',
			'jspath' => 'static/js/',
			'jsrefdomains' => '',
			'jsstatus' => '0',
			'karmaratelimit' => '0',
			'losslessdel' => '365',
			'magicdiscount' => '85',
			'magicmarket' => '1',
			'magicstatus' => '1',
			'mail' => 'a:10:{s:8:"mailsend";s:1:"1";s:6:"server";s:13:"smtp.21cn.com";s:4:"port";s:2:"25";s:4:"auth";s:1:"1";s:4:"from";s:26:"Discuz <username@21cn.com>";s:13:"auth_username";s:17:"username@21cn.com";s:13:"auth_password";s:8:"password";s:13:"maildelimiter";s:1:"0";s:12:"mailusername";s:1:"1";s:15:"sendmail_silent";s:1:"1";}',
			'maxavatarpixel' => '120',
			'maxavatarsize' => '20000',
			'maxbdays' => '0',
			'maxchargespan' => '0',
			'maxfavorites' => '100',
			'maxincperthread' => '0',
			'maxmagicprice' => '50',
			'maxmodworksmonths' => '3',
			'maxonlinelist' => '0',
			'maxpage' => '100',
			'maxpolloptions' => '20',
			'maxpostsize' => '10000',
			'maxsigrows' => '100',
			'maxsmilies' => '10',
			'membermaxpages' => '100',
			'memberperpage' => '25',
			'memliststatus' => '1',
			'memory' =>
		    array(
				'common_member' =>0,
				'common_member_count' =>0,
				'common_member_status' =>0,
				'common_member_profile' =>0,
				'common_member_field_home' =>0,
				'common_member_field_forum' =>0,
				'common_member_verify' =>0,
				'forum_thread' =>172800,
				'forum_thread_forumdisplay' =>300,
				'forum_collectionrelated' =>0,
				'forum_postcache' =>300,
				'forum_collection' =>300,
				'home_follow' =>86400,
				'forumindex' =>30,
				'diyblock' =>300,
				'diyblockoutput' =>30,
		    ),
		    'minpostsize' => '10',
		    'mobile' =>
				    array(
						'allowmobile' =>0,
						'mobileforward' =>1,
						'mobileregister' =>0,
						'mobilecharset' => 'utf-8',
						'mobilesimpletype' =>0,
						'mobiletopicperpage' =>10,
						'mobilepostperpage' =>5,
						'mobilecachetime' =>0,
						'mobileforumview' =>0,
						'mobilepreview' =>1
				),
			'moddisplay' => 'flat',
			'modratelimit' => '0',
			'userreasons' => '很给力!
	神马都是浮云
	赞一个!
	山寨
	淡定',
			'modworkstatus' => '1',
			'msgforward' => 'a:3:{s:11:"refreshtime";i:3;s:5:"quick";i:1;s:8:"messages";a:14:{i:0;s:19:"thread_poll_succeed";i:1;s:19:"thread_rate_succeed";i:2;s:23:"usergroups_join_succeed";i:3;s:23:"usergroups_exit_succeed";i:4;s:25:"usergroups_update_succeed";i:5;s:20:"buddy_update_succeed";i:6;s:17:"post_edit_succeed";i:7;s:18:"post_reply_succeed";i:8;s:24:"post_edit_delete_succeed";i:9;s:22:"post_newthread_succeed";i:10;s:13:"admin_succeed";i:11;s:17:"pm_delete_succeed";i:12;s:15:"search_redirect";i:13;s:10:"do_success";}}',
			'msn' => '',
			'networkpage' => '0',
			'newbiespan' => '0',
			'newbietasks' => '',
			'newbietaskupdate' => '',
			'newspaceavatar' => '0',
			'nocacheheaders' => '0',
			'oltimespan' => '10',
			'onlinehold' =>900,
		    'onlinerecord' => '1	1341364893',
		    'pollforumid' => '0',
		    'postbanperiods' => '',
		    'postmodperiods' => '',
		    'postperpage' => '10',
		    'privacy' =>
		    array(
		      'view' =>
			    array(
					'index' =>0,
					'friend' =>0,
					'wall' =>0,
					'home' =>0,
					'doing' =>0,
					'blog' =>0,
					'album' =>0,
					'share' =>0
			    ),
		      'feed' =>
				array(
					'doing' =>1,
					'blog' =>1,
					'upload' =>1,
					'poll' =>1,
					'newthread' =>1
		      )
		    ),
		    'pvfrequence' => '60',
		    'pwdsafety' => '0',
		    'qihoo' =>
				array(
					'status' =>0,
					'searchbox' =>6,
					'summary' =>1,
					'jammer' =>1,
		      		'maxtopics' =>10,
		     		'keywords' => '',
		      		'adminemail' => '',
		     		'validity' =>1,
					'relatedthreads' =>
				      array(
				        'bbsnum' =>0,
				        'webnum' =>0,
						'type' =>
					        array(
					          'blog' =>'blog',
					          'news' =>'news',
					          'bbs' =>'bbs'
					        ),
				        'banurl' =>'',
				        'position' =>1,
				        'validity' =>1,
						)
				),
		    'ratelogon' => '1',
			'ratelogrecord' => '20',
			'relatenum' => '10',
			'relatetime' => '60',
			'realname' => '0',
			'recommendthread' =>
			    array(
			      'allow' =>0
			    ),
		    'regclosemessage' => '',
		    'regctrl' => '0',
		    'strongpw' =>false,
		    'regfloodctrl' => '0',
		    'regname' => 'register',
		    'reglinkname' => '立即注册',
		    'regstatus' => '1',
		    'regverify' => '0',
		    'relatedtag' =>false,
		    'report_reward' => 'a:2:{s:3:"min";i:-3;s:3:"max";i:3;}',
		    'rewardforumid' => '0',
		    'rewritecompatible' => '',
		    'rewritestatus' =>false,
		    'rssstatus' => '1',
		    'rssttl' => '60',
		    'runwizard' => '1',
		    'search' =>
				array(
					'portal' =>
				      array(
				        'status' =>1,
				        'searchctrl' =>10,
						'maxspm' =>10,
				        'maxsearchresults' =>500,
				      ),
			      'forum' =>
						array(
						'status' =>1,
				        'searchctrl' =>10,
				        'maxspm' =>10,
						'maxsearchresults' =>500
			      ),
			      'blog' =>
						array(
						'status' =>1,
				        'searchctrl' =>10,
				        'maxspm' =>10,
						'maxsearchresults' =>500
			      ),
				'album' =>
						array(
						'status' =>1,
				        'searchctrl' =>10,
				        'maxspm' =>10,
						'maxsearchresults' =>500
			      ),
		      'group'  =>
						array(
						'status' =>1,
				        'searchctrl' =>10,
				        'maxspm' =>10,
						'maxsearchresults' =>500
			      ),
		      'collection'  =>
						array(
						'status' =>1,
				        'searchctrl' =>10,
				        'maxspm' =>10,
						'maxsearchresults' =>500
			      ),
			),
		    'sphinxon' => '',
			'sphinxhost' => '',
			'sphinxport' => '',
			'sphinxsubindex' => 'threads,threads_mintue',
			'sphinxmsgindex' => 'posts,posts_minute',
			'sphinxmaxquerytime' => '',
			'sphinxlimit' => '',
			'sphinxrank' => '',
			'searchbanperiods' => '',
			'seccodedata' =>
		    array(
		      'minposts' => '',
		      'loginfailedcount' =>0,
		      'width' =>150,
		      'height' =>40,
		      'type' => '0',
		      'background' => '1',
		      'adulterate' => '1',
		      'ttf' => '0',
		      'angle' => '0',
		      'color' => '1',
		      'size' => '0',
		      'shadow' => '1',
		      'animator' => '0',
		    ),
		    'seccodestatus' => '16',
		    'seclevel' => '1',
		    'secqaa' =>
			    array(
			      'minposts' => '1',
			      'status' =>0
				),
			'sendmailday' => '0',
			'seodescription' =>false,
		    'seohead' => '',
		    'seokeywords' =>false,
		    'seotitle' =>
				array(
					'portal' => '门户',
					'forum' => '论坛',
					'group' => '群组',
					'home' => '家园',
					'userapp' => '应用'
				),
			'showavatars' => '1',
			'showemail' => '',
			'showimages' => '1',
			'shownewuser' => '0',
			'showsettings' => '7',
			'showsignatures' => '1',
			'sigviewcond' => '0',
			'sitemessage' =>
			    array(
			      'time' =>3000,
			      'register' =>array(),
					'login' =>array(),
			      'newthread' =>array(),
			      'reply' =>array()
					),
			'sitename' => 'Comsenz Inc.',
			'siteuniqueid' => 'DXMHEJRn8175VdoC',
			'siteurl' => 'http://www.comsenz.com/',
			'smcols' => '8',
			'smrows' => '5',
			'smthumb' => '20',
			'spacedata' =>
			    array(
			      'cachelife' => '900',
			      'limitmythreads' => '5',
			      'limitmyreplies' => '5',
			      'limitmyrewards' => '5',
			      'limitmytrades' => '5',
			      'limitmyvideos' => '0',
			      'limitmyblogs' => '8',
			      'limitmyfriends' => '0',
			      'limitmyfavforums' => '5',
			      'limitmyfavthreads' => '0',
			      'textlength' => '300',
			    ),
		    'spacestatus' => '1',
		    'srchhotkeywords' =>
		    	array('活动','交友','discuz'),
		    'starthreshold' => '2',
		    'statcode' => '',
		    'statscachelife' => '180',
		    'statstatus' => '',
		    'styleid' => '1',
		    'stylejump' => '1',
		    'subforumsindex' => '0',
		    'tagstatus' => '1',
		    'taskon' => '0',
		    'tasktypes' => 'a:3:{s:9:"promotion";a:2:{s:4:"name";s:18:"网站推广任务";s:7:"version";s:3:"1.0";}s:4:"gift";a:2:{s:4:"name";s:15:"红包类任务";s:7:"version";s:3:"1.0";}s:6:"avatar";a:2:{s:4:"name";s:15:"头像类任务";s:7:"version";s:3:"1.0";}}',
			'threadmaxpages' => '1000',
			'threadsticky' =>
	    		array('全局置顶','分类置顶','本版置顶'),
			'thumbwidth' => '400',
			'thumbheight' => '300',
			'sourcewidth' => '',
			'sourceheight' => '',
			'thumbquality' => '100',
			'thumbstatus' => '',
			'timeformat' => 'H:i',
			'timeoffset' => '8',
			'topcachetime' => '60',
			'topicperpage' => '20',
			'tradeforumid' => '0',
			'transfermincredits' => '1000',
			'uc' =>'',
		    'addfeed' =>1,
		    'ucactivation' => '1',
		    'updatestat' => '1',
		    'userdateformat' => 'Y-n-j
		Y/n/j
		j-n-Y
		j/n/Y',
		    'userstatusby' => '1',
		    'videophoto' => '0',
		    'video_allowalbum' => '0',
		    'video_allowblog' => '0',
		    'video_allowcomment' => '0',
		    'video_allowdoing' => '1',
		    'video_allowfriend' => '1',
		    'video_allowpoke' => '1',
		    'video_allowshare' => '0',
		    'video_allowuserapp' => '0',
		    'video_allowviewspace' => '1',
		    'video_allowwall' => '1',
		    'viewthreadtags' => '100',
		    'visitbanperiods' => '',
		    'visitedforums' => '10',
		    'vtonlinestatus' => '1',
		    'wapcharset' => '0',
		    'wapdateformat' => 'n/j',
		    'wapmps' => '500',
		    'wapppp' => '5',
		    'wapregister' => '0',
		    'wapstatus' => '0',
		    'waptpp' => '10',
		    'warningexpiration' => '30',
		    'warninglimit' => '3',
		    'welcomemsg' => '1',
		    'welcomemsgtitle' => '{username}，您好，感谢您的注册，请阅读以下内容。',
		    'welcomemsgtxt' => '尊敬的{username}，您已经注册成为{sitename}的会员，请您在发表言论时，遵守当地法律法规。
		如果您有什么疑问可以联系管理员，Email: {adminemail}。
				
				
		{bbname}
		{time}',
		    'whosonlinestatus' => '3',
		    'whosonline_contract' => '0',
		    'zoomstatus' => '1',
		    'my_app_status' => '0',
		    'my_siteid' => '',
		    'my_sitekey' => '',
		    'my_closecheckupdate' => '',
		    'my_ip' => '',
		    'my_search_data' =>
				    array(
						'status' =>0,
						'allow_hot_topic' =>1,
						'allow_thread_related' =>1,
						'allow_thread_related_bottom' =>0,
						'allow_forum_recommend' =>1,
						'allow_forum_related' =>0,
						'allow_collection_related' =>1,
						'allow_search_suggest' =>0,
						'cp_version' =>1,
						'recwords_lifetime' =>21600
		    ),
		    'rewardexpiration' => '30',
		    'stamplistlevel' => '3',
		    'visitedthreads' => '0',
		    'navsubhover' => '0',
		    'showusercard' => '1',
		    'allowspacedomain' => '0',
		    'allowgroupdomain' => '0',
		    'holddomain' => 'www|*blog*|*space*|*bbs*',
		    'domain' =>
		    array(
		      'defaultindex' => 'forum.php',
		      'holddomain' => 'www|*blog*|*space*|*bbs*',
		      'list' =>
					array(
				),
				'app' =>
		      array(
		        'portal' =>
		        '',
		        'forum' =>
		        '',
		        'group' =>
		        '',
		        'home' =>
		        '',
		        'default' =>
		        '',
		      ),
		      'root' =>
		      array(
		        'home' =>'',
		        'group' =>'',
		        'forum' =>'',
		        'topic' =>'',
		        'channel' =>''
		      )
		    ),
		    'ranklist' =>
				    array(
					'status' => '1',
					'cache_time' => '1',
					'index_select' => 'thisweek',
					'member' =>
		      array(
		        'available' =>
		        '1',
		        'cache_time' =>
		        '5',
		        'show_num' =>
		        '20',
		      ),
		      'thread' =>
		      array(
		        'available' =>
					'1',
		        'cache_time' =>
					'5',
		        'show_num' =>
					'20',
		      ),
		      'blog' =>
					array(
					'available' =>
		        '1',
					'cache_time' =>
		        '5',
					'show_num' =>
		        '20',
				),
				'poll' =>
		      array(
		        'available' =>
		        '1',
		        'cache_time' =>
		        '5',
		        'show_num' =>
		        '20',
		      ),
		      'activity' =>
		      array(
		        'available' =>'1',
		        'cache_time' =>'5',
		        'show_num' =>'20',
		      ),
		      'picture' =>
				      array(
					'available' =>
		        '1',
					'cache_time' =>
		        '5',
					'show_num' =>
		        '20'
				),
				'forum' =>
		      array(
		        'available' =>
		        '1',
		        'cache_time' =>
		        '5',
		        'show_num' =>
		        '20'
		      ),
		      'group' =>
		      array(
		        'available' =>'1',
		        'cache_time' =>'5',
		        'show_num' =>'20'
		      )
		    ),
		    'outlandverify' => '0',
		    'allowquickviewprofile' => '1',
		    'allowmoderatingthread' => '1',
		    'editperdel' => '1',
		    'defaultindex' => 'forum.php',
		    'ipregctrltime' => '72',
		    'verify' =>
				    array(
					'6'=>
				
					array(
						'title' =>
		        '实名认证',
						'available' =>
		        '0',
						'showicon' =>
		        '0',
						'viewrealname' =>
		        '0',
						'field' =>
		        array(
		          'realname' =>'realname',
		        ),
		        'icon' =>
		        false,
		      ),
		      'enabled' =>
						false,
						'1'=>
						array(
						'icon' =>
		        '',
					),
					'2'=>
					array(
						'icon' =>
		        '',
					),
					'3'=>
					array(
						'icon' =>
		        '',
					),
					'4'=>
					array(
						'icon' =>
		        '',
					),
					'5'=>
					array(
						'icon' =>
		        '',
					),
					'7'=>
					array(
						'title' =>
		        '视频认证',
						'available' =>
		        '0',
						'showicon' =>
		        '0',
						'viewvideophoto' =>
		        '0',
						'icon' =>
		        '',
					),
			),
			'focus' =>array(),
		    'robotarchiver' => '0',
		    'profilegroup' =>
		    array(
		      'base' =>
				      array(
						'available' =>1,
		        'displayorder' =>0,
		        'title' =>
						'基本资料',
		        'field' =>
						array(
						'realname' =>'realname',
						'gender' =>'gender',
						'birthday' =>'birthday',
						'birthcity' =>'birthcity',
						'residecity' =>'residecity',
						'residedist' =>'residedist',
						'affectivestatus' =>'affectivestatus',
						'lookingfor' =>'lookingfor',
						'bloodtype' =>'bloodtype',
						'field1' =>'field1',
						'field2' =>'field2',
						'field3' =>'field3',
						'field4' =>'field4',
						'field5' =>'field5',
						'field6' =>'field6',
						'field7' =>'field7',
						'field8' =>'field8',
					),
					),
					'contact' =>
		      array(
		        'title' =>
		        '联系方式',
				        'available' =>
		        '1',
				        'displayorder' =>
		        '1',
				        'field' =>
		        array(
		          'telephone' =>'telephone',
		          'mobile' =>'mobile',
		          'qq' =>'qq',
		          'msn' =>'msn',
		          'taobao' =>'taobao',
		          'icq' =>'icq',
		          'yahoo' =>'yahoo',
		        ),
		      ),
		      'edu' =>
		      array(
		        'available' =>1,
				'displayorder' =>2,
		        'title' =>
		        '教育情况',
				 'field' =>
			        array(
			          'graduateschool' =>'graduateschool',
			          'education' =>'education',
			        ),
		      ),
		      'work' =>
		      array(
		        'available' =>1,
				'displayorder' =>3,
		        'title' =>
		        '工作情况',
				        'field' =>
		        array(
		          'company' =>'company',
		          'occupation' =>'occupation',
		          'position' =>'position',
		          'revenue' =>'revenue',
		        ),
		      ),
		      'info' =>
		      array(
		        'title' =>'个人信息',
		        'available' =>'1',
		        'displayorder' =>'4',
		        'field' =>
				        array(
						'idcardtype' =>'idcardtype',
						'idcard' =>'idcard',
						'address' =>'address',
						'zipcode' =>'zipcode',
						'site' =>'site',
						'bio' =>'bio',
						'interest' =>'interest',
						'sightml' =>'sightml',
						'customstatus' =>'customstatus',
						'timeoffset' =>'timeoffset',
					),
				),
		),

			'onlyacceptfriendpm' => '0',
			'pmreportuser' => '1',
			'chatpmrefreshtime' => '8',
			'preventrefresh' => '1',
			'imagelistthumb' => '0',
			'regconnect' => '1',
			'connect' =>array(),
		    'allowwidthauto' => '0',
		    'switchwidthauto' => '1',
		    'leftsidewidth' => '130',
		    'card' =>
		    array(
		      'open' => '0',
		    ),
		    'report_receive' => 'a:2:{s:9:"adminuser";a:1:{i:0;s:1:"1";),s:12:"supmoderator";N;),',
		    'leftsideopen' => '0',
		    'showexif' => '0',
		    'followretainday' => '7',
		    'newbie' => '20',
		    'collectionteamworkernum' => '3',
		    'collectionnum' => '10',
		    'blockmaxaggregationitem' => '20000',
		    'moddetail' => '0',
		    'homestatus' => '0',
		    'article_tags' =>
				    array(
					'1'=>'原创',
		      '2'=>'热点',
				      '3'=>'组图',
		      '4'=>'爆料',
				      '5'=>'头条',
		      '6'=>'幻灯',
				      '7'=>'滚动',
		      '8'=>'推荐',
				),
				'upgrade' =>
		    false,

		    'dsiteuniqueid' => 'DzzNDIQvx8ff4Ayad',
		    'newusergroupid' => '10',
		    'forumfids' =>array(),
				    'version' => 'X2.5',
				    'cachethreadon' =>0,
		    'styles' =>
		    array(
		      '1'=>'默认风格',
				),
				'creditnames' => '1|威望|,2|金钱|,3|贡献|',
				'creditstransextra' =>
		    array(
		      '1'=>'2',
				      '2'=>'2',
		      '3'=>'2',
		      '4'=>'2',
				      '5'=>'2',
		      '6'=>'2',
		      '7'=>'2',
				      '8'=>'2',
		      '9'=>'2',
		      '10'=>'2',
				),
				'exchangestatus' =>
		    false,
		    'transferstatus' =>true,
		    'ucenterurl' => 'http://localhost/discuz/uc_server',
		    'tradeopen' =>1,
				    'medalstatus' =>0,
		    'specialicon' =>array(),
		    'threadplugins' =>array(),
			'hookscriptmobile' =>
		    array(
		      'global' =>
		      array(
		        'global' =>
				        array(
					'module' =>
		          array(
		            'mobile' =>
		            'mobile/mobile',
		          ),
		          'adminid' =>
		          array(
		            'mobile' =>
					'0',
		          ),
		          'funcs' =>
					array(
					'global_header_mobile' =>
		            	array(array( 'mobile','global_header_mobile' )),
		            'global_mobile' =>
						array(array('mobile','global_mobile')),
		          ),
		        ),
		        'common' =>
		        array(
		          'module' =>
				          array(
							'mobile' =>
		            'mobile/mobile',
						),
						'adminid' =>
		          array(
		            'mobile' =>
		            '0',
		          ),
		          'funcs' =>
		          array(
		            'common' =>
				        array(array('mobile','common' )),
		          ),
		        ),
		        'discuzcode' =>
		        array(
		          'module' =>
				          array(
									'mobile' =>
		            'mobile/mobile',
								),
								'adminid' =>
		          array(
		            'mobile' =>
		            '0',
		          ),
		          'funcs' =>
		          array(
		            'discuzcode' =>
				            array(array('mobile','discuzcode' ))
		          ),
		        ),
		      ),
		      'forum' =>
		      array(
		        'post' =>
				        array(
											'module' =>
		          array(
		            'mobile' =>
		            'mobile/mobile',
		          ),
		          'adminid' =>
		          array(
		            'mobile' =>
											'0',
		          ),
		          'messagefuncs' =>
						array(
						'post_mobile' =>array(array('mobile','post_mobile_message'))
		          ),
		        ),
		        'common' =>
											array(
											'module' =>
		          array(
		            'mobile' =>
		            'mobile/mobile',
		          ),
		          'adminid' =>
		          array(
		            'mobile' =>
											'0',
		          ),
		          'funcs' =>
					array(
							'common' =>array(array( 'mobile','common' )),
		          )
		        ),
		        'discuzcode' =>
					array(
						'module' =>
				          array(
				            'mobile' =>
				            'mobile/mobile',
				          ),
		          'adminid' =>
		          array(
		            'mobile' =>
											'0',
		          ),
		          'funcs' =>
						array(
						'discuzcode' =>array(array( 'mobile','discuzcode')),
		          ),
		        ),
		        'global' =>
											array(
											'module' =>
		          array(
		            'mobile' =>
		            'mobile/mobile',
		          ),
		          'adminid' =>
		          array(
		            'mobile' =>
											'0',
		          ),
		          'funcs' =>
							array('global_mobile' =>array(array('mobile','global_mobile'))
		          ),
		        ),
		      ),
		      'misc' =>
											array(
											'mobile' =>
		        array(
		          'module' =>
		          array(
		            'mobile' =>
											'mobile/mobile',
		          ),
		          'adminid' =>
									array(
									'mobile' => '0',
								),
										'funcs' =>array('mobile' =>array(array('mobile', 'mobile')))),
										'common' =>
		        array(
		          'module' =>
		          array(
		            'mobile' =>
				            'mobile/mobile',
		          ),
		          'adminid' =>
				          array(
											'mobile' =>
		            '0',
										),
										'funcs' =>
		          array(
		            'common' =>array(array( 'mobile', 'common')))),
					'discuzcode' =>
		        array(
		          'module' =>
		          array(
		            'mobile' =>
				            'mobile/mobile',
		          ),
		          'adminid' =>
				          array(
											'mobile' =>
		            '0',
										),
										'funcs' =>
		          array(
		            'discuzcode' =>array(array('mobile', 'discuzcode')))),
							'global' =>
		        array(
		          'module' =>
		          array(
		            'mobile' =>
				            'mobile/mobile',
		          ),
		          'adminid' => array('mobile' => '0',),
				'funcs' =>array('global_mobile' =>array(array('mobile','global_mobile')))
		        		
		),
				),
			),
			'hookscript' =>
				array(
						'global' =>
						array(
								'common' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'common' =>
												array(array('mobile','common',
												)
												)
										)
								),
								'discuzcode' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'discuzcode' =>
												array(array('mobile','discuzcode',
												)
												)
										)
								),
								'global' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'global_mobile' =>
												array(array('mobile','global_mobile',
												)
												)
										)
								)
						),
						'forum' =>
						array(
								'post' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'messagefuncs' =>
										array(
												'post_mobile' =>
												array(array('mobile','post_mobile_message'
												)
												)
										)
								),
								'common' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'common' =>
												array(array('mobile','common'
												)
												)
										)
								),
								'discuzcode' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'discuzcode' =>
												array(array('mobile','discuzcode'
												)
												)
										)
								),
								'global' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'global_mobile' =>
												array(array('mobile','global_mobile'
												)
												)
										)
								)
						),
						'misc' =>
						array(
								'mobile' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'mobile' =>
												array(array('mobile', 'mobile'
												)
												)
											)
				
										),
								'common' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
		
												'common' =>
												array(array('mobile', 'common'
												)
												)
											)
		
										),
								'discuzcode' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'discuzcode' =>
												array(array('mobile', 'discuzcode'
												)
												)
										)
								),
								'global' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'global_mobile' =>
												array(array('mobile','global_mobile'
												)
												)
										)
								)
						),
						'connect' =>
						array(
								'login' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'messagefuncs' =>
										array(
												'login_mobile' =>
												array(array('mobile','login_mobile_message'
												)
												)
										)
								),
								'common' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'common' =>
												array(array( 'mobile','common'
												)
												)
										)
								),
								'discuzcode' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'discuzcode' =>
												array(array('mobile','discuzcode'
												)
												)
										)
								),
								'global' =>
								array(
										'module' =>
										array(
												'mobile' =>
												'mobile/mobile',
										),
										'adminid' =>
										array(
												'mobile' =>
												'0',
										),
										'funcs' =>
										array(
												'global_mobile' =>
												array(array('mobile','global_mobile'
												)
												)
										)
								)
						)
				),
				'pluginlinks' =>array(),
				'plugins' =>
				array(
						'available' =>
						array('mobile'
						),
						'func' =>
						array(
								'hookscriptmobile' =>
								array(
										'common' =>true,
										'discuzcode' =>true
								),
								'hookscript' =>
								array(
										'common' =>true,
										'discuzcode' =>true
								),
						),
						'version' =>
						array(
								'mobile' =>
								'1.03',
						),
				),
				'navlogos' =>NULL,
				'navdms' =>array(),
				'navmn' =>
				array(
						'forum.php' => 'mn_forum',
						'userapp.php' => 'mn_userapp',
				),
				'navmns' =>
				array(
						'misc.php' =>
						array(array(array(
								'mod' =>
								'faq'
						),
								'1'=>'mn_N0a2c'
						)
						)
				),
				'menunavs' => '',
				'subnavs' =>array(),
				'navs' =>
				array(
						'2'=>
						array(
								'navname' =>
								'论坛',
								'filename' =>
								'forum.php',
								'available' =>
								'1',
								'navid' =>
								'mn_forum',
								'level' =>
								'0',
								'nav' =>
								'id="mn_forum" ><a href="forum.php" hidefocus="true" title="BBS"  >论坛<span>BBS</span></a',
						),
						'5'=>
						array(
								'navname' =>
								'游戏',
								'filename' =>
								'userapp.php',
								'available' =>0,
								'navid' =>
								'mn_userapp',
								'level' =>
								'0',
								'nav' =>
								'id="mn_userapp" ><a href="userapp.php" hidefocus="true" title="Manyou"  >游戏<span>Manyou</span></a',
						),
						'6'=>
						array(
								'navname' =>
								'插件',
								'filename' =>
								'#',
								'available' =>0
						),
						'7'=>
						array(
								'navname' =>
								'帮助',
								'filename' =>
								'misc.php?mod=faq',
								'available' =>
								'0',
								'navid' =>
								'mn_N0a2c',
								'level' =>
								'0',
								'nav' =>
								'id="mn_N0a2c" ><a href="misc.php?mod=faq" hidefocus="true" title="Help"  >帮助<span>Help</span></a',
						),
				),
				'footernavs' =>
				array(
						'stat' =>
						array(
								'available' =>'1',
								'navname' =>'站点统计',
								'code' =>'<a href="misc.php?mod=stat" >站点统计</a>',
								'type' =>'0',
								'level' =>'0',
								'id' =>'stat',
						),
						'report' =>
						array(
								'available' =>
								'1',
								'navname' =>
								'举报',
								'code' =>
								'<a href="javascript:;"  onclick="showWindow(\'miscreport\', \'misc.php?mod=report&url=\'+REPORTURL);return false;">举报</a>',
								'type' =>
								'0',
								'level' =>
								'0',
								'id' =>
								'report',
						),
						'archiver' =>
						array(
								'available' =>
								'1',
								'navname' =>
								'Archiver',
								'code' =>
								'<a href="archiver/" >Archiver</a>',
								'type' =>
								'0',
								'level' =>
								'0',
								'id' =>
								'archiver',
						),
						'mobile' =>
						array(
								'available' =>'1',
								'navname' =>'手机版',
								'code' =>'<a href="forum.php?mobile=yes" >手机版</a>',
								'type' =>'0',
								'level' =>'0',
								'id' =>'mobile',
						),
				),
				'spacenavs' =>
				array(
						'125'=>
						array(
								'available' =>
								'1',
								'navname' =>
								'{userpanelarea1},',
								'code' =>
								'userpanelarea1',
								'level' =>
								'0',
						),
						'126'=>
						array(
								'available' =>
								'1',
								'navname' =>
								'{hr},',
								'code' =>
								'</ul><hr class="da" /><ul>',
								'level' =>
								'0',
						),
						'127'=>
						array(
								'available' =>
								'1',
								'navname' =>
								'{userpanelarea2},',
								'code' =>
								'userpanelarea2',
								'level' =>
								'0',
						),
				),
				'mynavs' =>
				array(
						'friend' =>
						array(
								'available' =>'1',
								'navname' =>'好友',
								'code' =>'<a href="home.php?mod=space&do=friend" style="background-image:url(http://127.0.0.1/discuz/static/image/feed/friend_b.png) !important">好友</a>',
								'level' =>
								'0',
						),
						'thread' =>
						array(
								'available' =>
								'1',
								'navname' =>
								'帖子',
								'code' =>
								'<a href="forum.php?mod=guide&view=my" style="background-image:url(http://127.0.0.1/discuz/static/image/feed/thread_b.png) !important">帖子</a>',
								'level' =>
								'0',
						),
						'favorite' =>
						array(
								'available' =>
								'1',
								'navname' =>
								'收藏',
								'code' =>
								'<a href="home.php?mod=space&do=favorite&view=me" style="background-image:url(http://127.0.0.1/discuz/static/image/feed/favorite_b.png) !important">收藏</a>',
								'level' =>
								'0',
						),
						'magic' =>
						array(
								'available' =>
								'1',
								'navname' =>
								'道具',
								'code' =>
								'<a href="home.php?mod=magic" style="background-image:url(http://127.0.0.1/discuz/static/image/feed/magic_b.png) !important">道具</a>',
								'level' =>
								'0',
						),
						'medal' =>
						array(
								'available' =>
								'1',
								'navname' =>
								'勋章',
								'code' =>
								'<a href="home.php?mod=medal" style="background-image:url(http://127.0.0.1/discuz/static/image/feed/medal_b.png) !important">勋章</a>',
								'level' =>
								'0',
						),
						'task' =>
						array(
								'available' =>
								'1',
								'navname' =>
								'任务',
								'code' =>
								'<a href="home.php?mod=task" style="background-image:url(http://127.0.0.1/discuz/static/image/feed/task_b.png) !important">任务</a>',
								'level' =>
								'0',
						),
				),
				'topnavs' =>
				array(
					array(
						'sethomepage' =>
						array(
								'available' =>
								'1',
								'navname' =>
								'设为首页',
								'code' =>
								'<a href="javascript:;"  onclick="setHomepage(\'http://127.0.0.1/discuz/\');">设为首页</a>',
								'type' => '0',
								'level' =>
								'0',
								'id' =>
								'sethomepage',
						),
						'setfavorite' =>
						array(
								'available' =>'1',
								'navname' =>'收藏本站',
								'code' =>'<a href="http://127.0.0.1/discuz/"  onclick="addFavorite(this.href, \'Discuz! Board\');return false;">收藏本站</a>',
								'type' =>'0',
								'level' =>'0',
								'id' =>'setfavorite'
						)
					)
				),
				'allowsynlogin' =>1,
				'ucappopen' =>
				array(
						'UCHOME' =>1,
						'OTHER' =>1
				),
				'ucapp' =>array(),
				'uchomeurl' => 'http://localhost/uchome',
				'discuzurl' => 'http://localhost/discuz',
				'homeshow' => '0',
				'reginput' =>
				array(
						'username' => 'SggVNv',
						'password' => 'utWLuu',
						'password2' => '1qOQhX',
						'email' => 'zd8lmp',
				),
				'output' =>
				array(
						'str' =>array(),
						'preg' =>array()
				)
		);
		return $setting;
	}
	
	/**
	 +----------------------------------------------------------
	 * 显示大桌面内提交的状态显示
	 +----------------------------------------------------------
	 * @param $msg 要提示的信息
	 * @param $referer 来路
	 * @param $data 返回的数据
	 * @param $param参数
	 * @return array
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-19 下午1:03:59
	 +----------------------------------------------------------
	 */
	public function rightmessage($msg, $referer='', $data=array(), $param=array()){
		$param['status'] = 'right';
		$this->showmessage($msg, $referer, $data, $param);
	}
	public function errormessage($msg, $referer='', $data=array(), $param=array()){
		$param['status'] = 'error';
		if (empty($param['closetime'])) $param['closetime'] = 5;
		if (empty($param['showdialog'])) $param['showdialog'] = 1;
		if (empty($param['showmsg'])) $param['showmsg'] = true;
		$this->showmessage($msg, $referer, $data, $param);
	}
	public function infomessage($msg, $referer='', $data=array(), $param=array()){
		$param['status'] = 'notice';
		$this->showmessage($msg, $referer, $data, $param);
	}
	private function showmessage($msg, $referer='', $data=array(), $param=array()){
		$this->assign('msg', $msg);
		$this->assign('referer', $referer);
		$this->assign('data', $data);
		$param['handlekey'] = $_REQUEST['handlekey'];//要关闭的层
		$this->assign('param', $param);
		$this->display("../Public/_message");
	}
}
?>