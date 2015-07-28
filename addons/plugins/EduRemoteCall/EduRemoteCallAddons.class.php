<?php
/**
 +------------------------------------------------------------------------------
 * RemoteCall 实现了基于网格远程信息调用功能的插件
 * 只支持mysql
 +------------------------------------------------------------------------------
 */
class EduRemoteCallAddons extends SimpleAddons
{
	protected $version = '1.0';
	protected $author  = '孙晓波';
	protected $site    = 'http://www.zzstudio.net';
	protected $info    = '远程信息调用';
	protected $pluginName = '教育动态远程调用';
	protected $tsVersion  = "2.8";         // 核心版本号
	
	/**
	 +------------------------------------------------------------------------------
	 * getHooksInfo
	 * 获得该插件使用了哪些钩子聚合类，哪些钩子是需要进行排序的
	 * @access public
     * @return void
	 * author：sunxiaobo
	 * date：2012-11-15
	 +------------------------------------------------------------------------------
	 */
    public function getHooksInfo(){
    	$this->apply("home_edu_remote_dyna","home_edu_remote_dyna");
    }
    
    /**
     +------------------------------------------------------------------------------
     * 获取个人中心列表
     * @param $param
     * author：sunxiaobo
     * date：2012-11-19
     +------------------------------------------------------------------------------
     */
    public function home_edu_remote_dyna($param){
    	
		$this->assign('title','教育动态');
		$this->assign('morelink',$url);
		$this->assign('data',$result);
		$this->display('eduremotecall');
    }
    
    /**
     +------------------------------------------------------------------------------
     * 远程获取数据
     * author：sunxiaobo
     * date：2012-11-19
     +------------------------------------------------------------------------------
     */
    public function getDynaInfo(){
    	$obj = M("edu_remote_call");
		$finddata = $obj->order('display_order')->select();
		foreach ($finddata as &$item) {
			$item['value'] = str_replace('&amp;nbsp;',chr(32),$item['value']);
		}
		$data = array();
		foreach ($finddata as $obj){
			$data[$obj['field']] = $obj['value'];
		}
		if(empty($data['url']) || empty($data['encode']) || empty($data['geturl']) || empty($data['listurl']) || empty($data['getname'])){
			$data = null;
		}else{
			$url = htmlspecialchars_decode($data['url']);
			//获取远程链接的页面内容
			$contents = @file_get_contents($url); 
			
			$contents = str_replace("\\","/",$contents);//内容替换
	
			$charset = strtoupper($data['encode']);
			$text = $this->__doIconvVal($charset,"UTF8",$contents); //编码转换
			
			$listurlexp = htmlspecialchars_decode($data['listurl'], ENT_QUOTES);

			$explinkarr = explode('{listurl}',$listurlexp);
			$splink = str_replace('&nbsp;',chr(32),htmlspecialchars_decode($explinkarr[0], ENT_QUOTES));
			$r = explode($splink,$text);

			$urlexp = str_replace('&nbsp;',chr(32),htmlspecialchars_decode($data['geturl'], ENT_QUOTES));
			$expurlarr = explode('{url}',$urlexp);
			$nameexp = str_replace('&nbsp;',chr(32),htmlspecialchars_decode($data['getname'], ENT_QUOTES));
			$expnamearr = explode('{name}',$nameexp);
			$expdateexp = str_replace('&nbsp;',chr(32),htmlspecialchars_decode($data['getdate'], ENT_QUOTES));
			$expdatearr = explode('{time}',$expdateexp);
	
			$result = array();
			//循环输出远程调用的信息
			for($i=1;$i<count($r);$i++){
				$str = $r[$i];
				$item = array();
				//获取链接
				$item['url'] = $this->__cutStr($str,$expurlarr[0],$expurlarr[1]);
				if(!strstr($item['url'],'://'))
				{
					$item['url'] = $data['prefix'].$item['url'];
				}
				//获取标题
				$item['name'] = $this->__cutStr($str,$expnamearr[0],$expnamearr[1]);
				//获取时间
				$item['date'] = $this->__cutStr($str,$expdatearr[0],$expdatearr[1]);
				$result[] = $item;
			}
		}
    }

    /**
     +------------------------------------------------------------------------------
     * adminMenu
     * 设置后台管理菜单及控制器名称
     * author：sunxiaobo
     * date：2012-11-15
     +------------------------------------------------------------------------------
     */
    public function adminMenu(){
        $menu = array(
					'config' => '配置'
				);
        return $menu;
    }
    /**
     +------------------------------------------------------------------------------
     * 后台配置页面
     * author：sunxiaobo
     * date：2012-11-19
     +------------------------------------------------------------------------------
     */
	public function config(){
	    $obj = M("edu_remote_call");
		$result = $obj->order('display_order')->select();
		foreach ($result as &$item) {
			$item['value'] = str_replace('&amp;nbsp;',chr(32),$item['value']);
		}
		$this->assign('formlist',$result);
		$this->display('config');
	}
	
	
	/**
	 +------------------------------------------------------------------------------
	 * 执行提示config配置页面数据后的处理方法 ...
	 * author：sunxiaobo
	 * date：2012-11-15
	 +------------------------------------------------------------------------------
	 */
	public function saveConfig(){
		header("Content-Type: text/html;charset=utf-8");
		$_POST['status']		&&	$data['status']	=	intval($_POST['status']);
		$_POST['url']			&&	$data['url']	=	t($_POST['url']);
		$_POST['encode']		&&	$data['encode']	=	t($_POST['encode']);
		$_POST['prefix']		&&	$data['prefix']	=	h(t(trim($_POST['prefix'])));
		$_POST['geturl']		&&	$data['geturl']	=	h(t(trim(str_replace(chr(32),'&nbsp;',$_POST['geturl']))));
		$_POST['listurl']		&&	$data['listurl']=	h(t(trim(str_replace(chr(32),'&nbsp;',$_POST['listurl']))));
		$_POST['getname']		&&	$data['getname']=	h(t(trim(str_replace(chr(32),'&nbsp;',$_POST['getname']))));
		$_POST['getdate']		&&	$data['getdate']=	h(t(trim(str_replace(chr(32),'&nbsp;',$_POST['getdate']))));

		$flag = 0;
		foreach ($data as $k => $v){
			$map['field'] = $k;
			$res['value'] = $v;
			M("edu_remote_call")->where($map)->save($res);
		}
		if(!$flag){
			$this->success('数据更新成功！');
		}else{
			$this->error('数据更新失败！');
		}
		exit;
	}
	
	/**
	 +------------------------------------------------------------------------------
	 * 检查所添加的参数是否可以获取到所需要的值的方法
	 * author：sunxiaobo
	 * date：2012-11-16
	 +------------------------------------------------------------------------------
	 */
	public function preView(){
		header("Content-Type: text/html;charset=utf-8");
		
		$_POST['status']		&&	$data['status']	=	intval($_POST['status']);
		$_POST['url']			&&	$data['url']	=	$_POST['url'];
		$_POST['encode']		&&	$data['encode']	=	$_POST['encode'];
		$_POST['prefix']		&&	$data['prefix']	=	h(t(trim($_POST['prefix'])));
		$_POST['geturl']		&&	$data['geturl']	=	h(t(trim(str_replace(chr(32),'&nbsp;',$_POST['geturl']))));
		$_POST['listurl']		&&	$data['listurl']=	h(t(trim(str_replace(chr(32),'&nbsp;',$_POST['listurl']))));
		$_POST['getname']		&&	$data['getname']=	h(t(trim(str_replace(chr(32),'&nbsp;',$_POST['getname']))));
		$_POST['getdate']		&&	$data['getdate']=	h(t(trim(str_replace(chr(32),'&nbsp;',$_POST['getdate']))));

		if(empty($data['url'])){
			$this->error('远程抓取的网址不能为空！');
		};
		if(!$this->__checkCjUrl($data['url'])){
			$this->error('远程抓取的网址格式有误！');
		}

		$url = $data['url'];
		
		//获取远程链接的页面内容
		$contents = @file_get_contents($url); 
		
		$contents=str_replace("\\","/",$contents);//内容替换

		$charset = strtoupper($data['encode']);
		$text = $this->__doIconvVal($charset,"UTF8",$contents); //编码转换
		
		$listurlexp = $data['listurl'];
		if(empty($listurlexp)){
			$this->error('获取链接列表的正则不能为空！');
		}
		if(!stripos($listurlexp,"{listurl}")){
			$this->error('获取链接列表的正则必须包含{listurl}！');
		}
		$explinkarr = explode('{listurl}',$listurlexp);
		$splink = str_replace('&nbsp;',chr(32),htmlspecialchars_decode($explinkarr[0], ENT_QUOTES));
		$r = explode($splink,$text);
		
		$urlexp = str_replace('&nbsp;',chr(32),htmlspecialchars_decode($data['geturl'], ENT_QUOTES));
		$expurlarr = explode('{url}',$urlexp);
		$nameexp = str_replace('&nbsp;',chr(32),htmlspecialchars_decode($data['getname'], ENT_QUOTES));
		$expnamearr = explode('{name}',$nameexp);
		$expdateexp = str_replace('&nbsp;',chr(32),htmlspecialchars_decode($data['getdate'], ENT_QUOTES));
		$expdatearr = explode('{time}',$expdateexp);

		$result = array();
		//循环输出远程调用的信息
		for($i=1;$i<count($r);$i++){
			$str = $r[$i];
			$item = array();
			//获取链接
			$item['url'] = $this->__cutStr($str,$expurlarr[0],$expurlarr[1]);
			if(!strstr($item['url'],'://'))
			{
				$item['url'] = $data['prefix'].$item['url'];
			}
			//获取标题
			$item['name'] = $this->__cutStr($str,$expnamearr[0],$expnamearr[1]);
			//获取时间
			$item['date'] = $this->__cutStr($str,$expdatearr[0],$expdatearr[1]);
			$result[] = $item;
		}

		$this->assign('result',$result);
		$this->display('preview');
		exit;
	}
	
	/**
	 +------------------------------------------------------------------------------
	 * 检查URL是否合法
	 * @param unknown_type $url
	 * author：sunxiaobo
	 * date：2012-11-16
	 +------------------------------------------------------------------------------
	 */
	private function __checkCjUrl($url){
		if(!strstr($url,'://'))
		{
			return false;
		}
		return true;
	}
	
	/**
	 +------------------------------------------------------------------------------
	 * 按照规则截取字符串
	 * @param $str
	 * @param $start
	 * @param $end
	 * author：sunxiaobo
	 * date：2012-11-16
	 +------------------------------------------------------------------------------
	 */
	private function __cutStr($str,$start,$end){
		$i = stripos($str,$start);	//查询开始字符串位置
		$strtmp = substr($str,$i+strlen($start));	//从开始字符串处截取字符串
		$res = explode($end,$strtmp);	//通过结束字符串分割
		return $res[0];
	}
	
	
	/**
	 +------------------------------------------------------------------------------
	 * 编码转换方法
	 * @param $code
	 * @param $targetcode
	 * @param $str
	 * author：sunxiaobo
	 * date：2012-11-16
	 +------------------------------------------------------------------------------
	 */
	private function __doIconvVal($code,$targetcode,$str){
		@include_once(SITE_PATH."/addons/libs/Chinese.class.php");
		$iconv=new Chinese();
		$str=$iconv->Convert($code,$targetcode,$str);
		return $str;
	}
	

    public function start()
    {

    }
	
	/**
	 +------------------------------------------------------------------------------
	 * install方法
	 * 功能：创建数据表
	 * author：sunxiaobo
	 * date：2012-11-14
	 +------------------------------------------------------------------------------
	 */
	public function install()
	{
		$db_prefix = C('DB_PREFIX');
		$sql = array(
			"CREATE TABLE IF NOT EXISTS `{$db_prefix}edu_remote_call` (
			  `field` varchar(100) NOT NULL,
			  `type` varchar(50) NOT NULL default 'text',
			  `name` varchar(255) NOT NULL,
			  `value` varchar(255) default NULL,
			  `description` text,
			  `display_order` smallint(5) NOT NULL default '0',
			  `ctime` int(11) default NULL,
			  PRIMARY KEY  (`field`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;",
			"INSERT INTO `{$db_prefix}edu_remote_call` (`field`, `type`, `name`, `value`, `description`, `display_order`, `ctime`) VALUES
('status', 'checkbox', '是否开启调用', '1', '', 0, unix_timestamp()),
('url', 'text', '远程调用网址', 'http://www.ytedu.cn/cnet/dynamic/presentation/net_1/showclassitempagination.jsp?unitid=1&amp;clazz=77&amp;ignoreclassinformation=false&amp;branch=&amp;pageno=1&amp;rowsperpage=10', '', 1, unix_timestamp()),
('encode', 'text', '远程网页编码', 'GB2312', '例如：GBK、GB2312 统一为GB2312', 3, unix_timestamp()),
('prefix', 'text', '链接地址前缀', 'http://www.ytedu.cn/cnet/dynamic/presentation/net_1/', '例如：http://www.ruijie-grid.com/ 如地址前面没域名的话，系统会加上此前缀', 2, unix_timestamp()),
('listurl', 'text', '获取地址列表正则', '&lt;td&amp;nbsp;&amp;nbsp;class=&quot;td_list_clazz_1&quot;&amp;nbsp;height=&quot;18&quot;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&gt;{listurl}&lt;/td&gt;', '例如：实际代码为&lt;td class=&quot;link&quot;&gt;&lt;a href=&quot;http://www.ruijie-grid.com/article_1.html&quot;&gt;新闻标题&lt;/a&gt;&lt;span&gt;2012-10-11&lt;/span&gt;&lt;/td&gt; 我们要写入的正则为 &lt;td class=&quot;link&quot;&gt;{listurl}&lt;/td&gt;', 4, unix_timestamp()),
('getdate', 'text', '获取时间正则', '&lt;font&amp;nbsp;color=&quot;#999999&quot;&gt;{time}&lt;/font&gt;', '例如：&lt;font&gt;{time}&lt;/font&gt; 其中{time}表示的是要抓取的时间表达式', 7, unix_timestamp()),
('getname', 'text', '获取标题正则', 'title=&quot;{name}&quot;', '例如：target=&quot;_blank&quot;&gt;{name}&lt;/a&gt; 其中{time}表示的是要抓取标题表达式', 6, unix_timestamp()),
('geturl', 'text', '获取地址正则', '&lt;a&amp;nbsp;class=&quot;a_clazz_1&quot;&amp;nbsp;href=&quot;{url}&quot;', '例如：&lt;a href=&quot;{url}&quot; 其中{url}表示的是要抓取链接的表达式', 5, unix_timestamp());
			"
		);

		foreach ($sql as $v)
			$res = M()->execute($v);
		if (false !== $res) {
			return true;
		}
		return true;
	}

	/**
	 +------------------------------------------------------------------------------
	 * uninstall数据表的移除
	 * author：sunxiaobo
	 * date：2012-11-15
	 +------------------------------------------------------------------------------
	 */
	public function uninstall()
	{
		$db_prefix = C('DB_PREFIX');
		$sql = array(
			"DROP TABLE IF EXISTS `{$db_prefix}edu_remote_call`;"
		);

		foreach ($sql as $v)
			$res = M()->execute($v);

		if (false !== $res) {
			return true;
		}
	}
	
}
?>