<?php
/**
 +------------------------------------------------------------------------------
 * 社区基础平台相关的小部件操作Action
 +------------------------------------------------------------------------------
 * @category	home
 * @package		Lib/Action
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-5 上午08:01:19
 +------------------------------------------------------------------------------
 */
class WidgetAction extends Action
{
	private $__type_website = '0';
	
	public function _initialize()
	{
		// 验证是否允许匿名访问微广播广场
		if ($this->mid <= 0 && intval(model('Xdata')->get('siteopt:site_anonymous_square')) <= 0) {
			redirect(U('home'));
		}

        $expend_menu = array();
        Addons::hook('home_square_tab', array('menu' => & $expend_menu));
        $this->assign('expend_menu', $expend_menu);
	}

	/**
	 +----------------------------------------------------------
	 * widget调用过滤
	 +----------------------------------------------------------
	 * @return	string	返回调用Widget后的结果
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:02:14
	 +----------------------------------------------------------
	 */
	public function renderWidget()
	{
		//非登录下widget调用过滤
		if(!$this->mid){
			$access_widget = array();
			if(!in_array($_REQUEST['name'],$access_widget))exit;
		}
		$_REQUEST['name']  = t($_REQUEST['name']);
		$_REQUEST['param'] = unserialize(urldecode($_REQUEST['param']));
		send_http_header('utf8');
		echo empty($_REQUEST['name']) ? 'Invalid Param.' : W($_REQUEST['name'], $_REQUEST['param']);
	}

	/**
	 +----------------------------------------------------------
	 * 插件的请求转发
	 +----------------------------------------------------------
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:05:30
	 +----------------------------------------------------------
	 */
	public function addonsRequest()
	{
		Addons::addonsHook(t($_REQUEST['addon']),t($_REQUEST['hook']));
	}

	/**
	 +----------------------------------------------------------
	 * 关注“可能感兴趣的人”
	 +----------------------------------------------------------
	 * @return	echo 0或者html
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:05:51
	 +----------------------------------------------------------
	 */
	public function doFollowRelatedUser()
	{
		$_POST['uid'] = intval($_POST['uid']);

		if ($_POST['uid'] <= 0) {
			echo 0;
		} else {

			$related_user = unserialize($_SESSION['_widget_related_user']);
			$uid = $this->mid;
			$data = D('Follow', 'weibo')->where('uid ='.$uid)->findAll();
			// dump($GLOBALS['ts']['isSystemAdmin']);
			$count = count($data);
			if (empty($related_user)) {
				echo '';
			}
			if($count >= $GLOBALS['ts']['site']['max_following'] && $GLOBALS['ts']['isSystemAdmin'] == false){
				echo '14';
			}else {
				D('Follow', 'weibo')->dofollow($this->mid, $_POST['uid']);
				$shifted_user = array_shift($related_user);
				if(isset($shifted_user['uid'])){
					$_SESSION['_widget_related_user'] = serialize($related_user);
					$html = '';
		            $html .= '<li id="related_user_' . $shifted_user['uid'] . '">';
		            $html .= 	'<div class="userPic">';
		            $html .= 		'<a title="" href="'.U("home/Space/index",array("uid"=>$shifted_user['uid'])).'">';
		            $html .= 			'<img src="'.getUserFace($shifted_user['uid'],'m').'" card="1">';
		            $html .=		'</a>';
		            $html .= 	'</div>';
		            $html .=	'<div class="interest_info">';
	            	$html .= 		'<p><a href="'.U("home/Space/index",array("uid"=>$shifted_user['uid'])).'">'.getUserName($shifted_user['uid']).'</a></p>';
		            $html .= 		'<p><a href="javascript:void(0);" class="guanzhu" onclick="subscribe('.$shifted_user['uid'].')">加关注</a></p>';
		            $html .=		'<p class="cGray2">'.$shifted_user['reason'].'</p>';
		            $html .= 	'</div>';
		            $html .= '</li>';
		            echo $html;
		        }
			}
		}
	}

	/**
	 +----------------------------------------------------------
	 * 换一换可能感兴趣的人
	 +----------------------------------------------------------
	 * @return	echo 0或者html
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:06:47
	 +----------------------------------------------------------
	 */
	public function replaceRelatedUser()
	{
		$related_user = unserialize($_SESSION['_widget_related_user']);
		if (empty($related_user)) {
			echo '0';
		} else {
			$html  = '';
			$limit = min(intval($_POST['limit']), count($related_user));

			for ($i = 1; $i <= $limit; $i++) {
				$shifted_user = array_shift($related_user);
		        $html .= '<li id="related_user_' . $shifted_user['uid'] . '">';
		        $html .= 	'<div class="userPic">';
		        $html .= 		'<a title="" href="'.U("home/Space/index",array("uid"=>$shifted_user['uid'])).'">';
		        $html .= 			'<img src="'.getUserFace($shifted_user['uid'],'m').'" card="1">';
		        $html .=		'</a>';
		        $html .= 	'</div>';
		        $html .=	'<div class="interest_info">';
	            $html .= 		'<p><a class="fn" target="_self" rel="face" uid="'.$shifted_user['uid'].'" href="'.U("home/Space/index",array("uid"=>$shifted_user['uid'])).'">'.getUserName($shifted_user['uid']).'</a></p>';
		        $html .= 		'<p><a href="javascript:void(0);" class="guanzhu" onclick="subscribe('.$shifted_user['uid'].')">加关注</a></p>';
		        $html .=		'<p class="cGray2">'.$shifted_user['reason'].'</p>';
		        $html .= 	'</div>';
		        $html .= '</li>';
			}

			$_SESSION['_widget_related_user'] = serialize($related_user);
	        echo $html;
		}
	}

	/**
	 +----------------------------------------------------------
	 * 发微广播
	 +----------------------------------------------------------
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:07:29
	 +----------------------------------------------------------
	 */
	public function weibo() {
		// 解析参数
		$_REQUEST['param']	= unserialize(urldecode($_REQUEST['param']));
		$active_field	= $_REQUEST['param']['active_field'] == 'title' ? 'title' : 'body';
		$this->assign('has_status', $_REQUEST['param']['has_status']);
		$this->assign('is_success_status', $_REQUEST['param']['is_success_status']);
		$this->assign('status_title', t($_REQUEST['param']['status_title']));

		// 解析模板(统一使用模板的body字段)
		$_REQUEST['data']	= unserialize(urldecode($_REQUEST['data']));
		$content		= model('Template')->parseTemplate(t($_REQUEST['tpl_name']), array($active_field=>$_REQUEST['data']));
		//$content		= preg_replace_callback('/((?:https?|ftp):\/\/(?:www\.)?(?:[a-zA-Z0-9][a-zA-Z0-9\-]*\.)?[a-zA-Z0-9][a-zA-Z0-9\-]*(?:\.[a-zA-Z0-9]+)+(?:\:[0-9]*)?(?:\/[^\x{2e80}-\x{9fff}\s<\'\"“”‘’]*)?)/u','getContentUrl', $content);
		$this->assign('content', h($content[$active_field]));
		$this->assign('source',$_REQUEST['data']['source']);
		$this->assign('sourceUrl',$_REQUEST['data']['url']);
		$this->assign('type',$_REQUEST['data']['type']);
		$this->assign('type_data',$_REQUEST['data']['type_data']);
		$this->assign('button_title', t(urldecode($_REQUEST['button_title'])));
		$this->assign('addon_info',urldecode($_REQUEST['addon_info']));
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 站外资源分享到微广播
	 +----------------------------------------------------------
	 * 须提供以下$_GET参数:
	 * <code>
	 * url:         站外资源的URL地址 (需经过urlencode)
	 * title:       站外资源的标题    (需经过urlencode)
	 * sourceTitle: 来源站点名称	   (需经过urlencode)
	 * sourceUrl:   来源站点的URL地址 (需经过urlencode)
	 * picUrl:      附带图片的URL地址 (需经过urlencode)
	 * </code>
	 +----------------------------------------------------------
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:07:51
	 +----------------------------------------------------------
	 */
	public function share()
	{
		$data['content']	= urldecode($_GET['title']) . ' ' . getShortUrl(urldecode($_GET['url']));
		$data['source']		= urldecode($_GET['sourceTitle']);
		$data['sourceUrl']	= urldecode($_GET['sourceUrl']);

		// 获取远程图片 => 生成临时图片
		if ($pic_url = urldecode($_GET['picUrl'])) {
            $imageInfo = getimagesize($pic_url);
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
            if ('bmp' != $imageType) { // 禁止BMP格式的图片
	        	$save_path = SITE_PATH . '/data/uploads/temp'; // 临时图片地址
	    		$filename  = md5($pic_url) . '.' . $imageType; // 重复刷新时, 生成的文件名应一致
			    $img       = file_get_contents($pic_url);
			    $filepath  = $save_path.'/'.$filename;
			    $result    = file_put_contents($filepath, $img);
			    if ($result) {
					$data['type']	   = 1;
					$data['type_data'] = 'temp/' . $filename;
			    }
            }
		}

		$this->assign($data);
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 分享的操作
	 +----------------------------------------------------------
	 * @return	echo 0或者1
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:08:55
	 +----------------------------------------------------------
	 */
	public function doShare()
	{
		$data['content'] = $_POST['content'];
		$data['type'] = $type	= intval($_POST['type']);
		$data['type_data'] = $type_data = $_POST['type_data'];

		// 来自"来源站点"
		if ($_POST['source'] && $_POST['sourceUrl']) {
        	$from_data = serialize(array('source' => $_POST['source'], 'url' => $_POST['sourceUrl']));
		}

        $id = D('Weibo','weibo')->publish($this->mid, $data, $this->__type_website, $type, $type_data, '', $from_data);

        // 移除临时生成的图片文件
        if (strpos($type_data, 'temp/')) {
        	unlink(SITE_PATH . '/data/uploads/' . $type_data);
        }

        if ($id) {
        	X('Credit')->setUserCredit($this->mid,'share_to_weibo');
        	echo '1';
        } else {
        	echo '0';
        }
	}

	/**
	 +----------------------------------------------------------
	 * 举报操作
	 +----------------------------------------------------------
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:09:50
	 +----------------------------------------------------------
	 */
	public function denounce(){
		$post['from'] = $_POST['from'];
		$post['aid'] = $_POST['aid'];
		$post['uid'] = $_POST['uid'];
		$post['fuid'] = $_POST['fuid'];
		$post['content'] = $_POST['content'];
		$this->assign($post);
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 查看是否已经有此条数据
	 +----------------------------------------------------------
	 * @return	echo 0或者1
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:10:
	 +----------------------------------------------------------
	 */
	public function doDenounce(){
		//查看是否已经有此条数据
		$map['from'] = trim( $_POST['from'] );
		$map['aid'] = intval( $_POST['aid'] );
		$map['uid'] = intval( $_POST['uid'] );
		$map['fuid'] = intval( $_POST['fuid'] );
		if( $isDenounce = M( 'Denounce' )->where( $map )->count() ){
			echo 'ui.error("您已经举报过此条信息。");';
		}else{
			$map['content'] = trim( $_POST['content'] );
			$map['reason'] = trim( $_POST['reason'] );
			$map['ctime'] = time();
			if( $id = M( 'Denounce' )->add( $map ) ){
				echo 1;
			}else{
				echo 0;
			}
		}
	}

	/**
	 +----------------------------------------------------------
	 * 微广播秀
	 +----------------------------------------------------------
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:10:44
	 +----------------------------------------------------------
	 */
	public function weiboShow()
	{
    	// 微广播秀样式
    	$data['style']['width']  = $_GET['width'] < 190 ? 190 : ($_GET['width'] > 1024 ? 1024 : intval($_GET['width']));
    	$data['style']['height'] = $_GET['height'] < 75 ? 75 : ($_GET['height'] > 800 ? 800 : intval($_GET['height']));
    	$data['style']['skin']   = t($_GET['skin']);

		// 用户基本信息
		$data['user'] = D('User', 'home')->getUserByIdentifier($this->uid);
		// 微广播列表
		$user_data['user_id'] = $this->uid;
		$data['weibolist'] = api('Statuses')->data($user_data)->user_timeline();
    	// 被关注列表
    	$data['follower'] = D('Follow','weibo')->getList($this->uid, 'follower', 0, null, 20);

    	$data['uid'] = $this->uid;

    	$this->assign($data);
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 评论箱
	 +----------------------------------------------------------
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:11:02
	 +----------------------------------------------------------
	 */
	public function webpageComment()
	{
		$url = t(urldecode($_GET['url']));
		if (!$url) {
			exit('URL参数不可为空');
		}
		// 获取已生成的包含该地址的微广播ID
		$hash = md5($url);
		$webpage_model = M('webpage');
		$webpage_info = $webpage_model->where("`hash`='{$hash}'")->find();
		// 若不存在对应的微广播，则创建之
		if (!$webpage_info) {
			$content = file_get_contents($url);
			// 抓取内容失败，则退出
			if (!$content) {
				exit('网页不存在');
			}
			// 网页标题
			preg_match("/<title>\s*(.+)\s*<\/title>/i", $content, $title);
			$title = $title[1];
			// 拼装微广播内容
			$weibo_content = array(
				'content' => $title . ' ' . $url,
			);
			// // 评论箱UID
			// $uid = 10315;
			// // 生成微广播
			// $weibo_id = D('Weibo', 'weibo')->publish(10315, $weibo_content);
			// if (false == $weibo_id) {
			// 	exit('添加微广播失败');
			// }
			// 保存信息
			$webpage_info = array(
				'url'  => $url,
				'hash' => $hash,
				'title' => t($title),
			);
			$webpage_id = $webpage_model->add($webpage_info);
			if (false === $webpage_id) {
				exit('添加网页失败');
			}
			$webpage_info['webpage_id'] = $webpage_id;
		}

    	// 微广播秀样式
    	$data['style']['width']  = $_GET['width'] < 190 ? 190 : ($_GET['width'] > 1024 ? 1024 : intval($_GET['width']));
    	$data['style']['skin']   = t($_GET['skin']);

    	$this->assign('webpage_info', $webpage_info);
    	$this->assign($data);
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 批量关注挂件
	 +----------------------------------------------------------
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 上午08:11:19
	 +----------------------------------------------------------
	 */
	public function bulkFollow()
	{
		$uids = t($_GET['uids']);
		$uids = $uids ? explode(',', $uids) : array();
		$uids = array_unique(array_filter(array_map('intval', $uids)));

		$map['uid'] = array('IN', $uids);
		$user_list  = D('User', 'home')->getUserByMap($map, null, null, null, false);

		// 按照$_GET['uids'] 的顺序排序
		$_user_list = array_combine($uids, $uids);
		foreach ($user_list as $user) {
			$_user_list[$user['uid']] = $user;
		}
		// 过滤不存在的uid
		$user_list = array_diff($_user_list, $uids);

    	// 微广播秀样式
    	$data['style']['width']  = $_GET['width'] < 190 ? 190 : ($_GET['width'] > 1024 ? 1024 : intval($_GET['width']));
    	$data['style']['skin']   = t($_GET['skin']);

    	$this->assign('user_list', $user_list);
    	$this->assign($data);
		$this->display();
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 我的好友，挂件
	 +----------------------------------------------------------
	 * @author 小波 2013-6-19
	 +----------------------------------------------------------
	 */
	public function myFriend(){
		$data['list'] = D('Follow','weibo')->getList($this->uid,'following',0,'all','5');
		$data['h2_title'] = '我的好友';
		$this->assign($data);
		$this->display();
	}
	
	/**
	 +----------------------------------------------------------
	 * 个人消息挂件
	 +----------------------------------------------------------
	 * @author 小波 2013-6-21
	 +----------------------------------------------------------
	 */
	public function myNotify(){
		//获取私信
		$list = model('Message')->getMessageListByUid($this->mid, array(1, 2));
		$map['list_id'] = array('IN', getSubByKey($list['data'], 'list_id'));
		$map['is_del']  = '0';
		$countlist=M('message_content')->where($map)->field("count(list_id) AS count,list_id")->group('list_id')->findall();
		foreach($countlist as $k=>$v){
			$newcount[$v['list_id']]=$v['count'];
		}
		foreach($list['data'] as &$value){
			$value['message_num']=$newcount[$value['list_id']];
			$value['last_message']['content'] = h($value['last_message']['content']);
		}
		$data['data'][] = $list['data'];
		
		//获取系统通知
		
		$this->setTitle($type = L('my_private_message'));
		$this->assign($data);
		$this->display();
	}

}
