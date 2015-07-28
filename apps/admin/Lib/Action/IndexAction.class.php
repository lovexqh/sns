<?php
class IndexAction extends AdministratorAction {
    //后台框架页
    public function index() {
    	$this->assign('channel', $this->_getChannel());
    	$this->assign('menu',    $this->_getMenu());
        $this->display();
    }

    //后台首页
    public function main() {
    	echo '<h2>这里是后台首页</h2>';
    	$this->display();
    }

    protected function _getChannel() {
    	$channel = array(
    		'index'			=>	'首页',
    		'global'		=>	'全局',
    		'content'		=>	'内容',
    		'category'		=>	'数据类型',
    		'user'			=>	'用户',
    		'apps'			=>	'应用',
    		'extension'		=>	'插件',
			'square'		=>	'广场',
    		//'space'			=>	'空间',
    	);
    	//开启ucenter控制面板
    	if(UC_SYNC&&1==2){
    		$channel['ucenter'] = '统一身份';
    	}
    	return $channel;
    }

    protected function _getMenu() {
    	$menu = array();
    	//注意顺序！！

    	// 后台管理首页
    	$menu['index'] 		=   array(
    		'首页'			=>	array(
    			'首页'		=>	U('admin/Home/statistics'),
                '缓存更新'    =>  SITE_URL . '/cleancache.php?all',
    			/*'系统升级'	 =>	U('admin/Home/update'),*/
                '数据备份'    =>  U('admin/Tool/backup'),
                'CNZZ统计'   =>  U('admin/Tool/cnzz'),
            ),
    	);

    	//全局
    	$menu['global'] 	=   array(
    		'全局配置'		=>  array(
    			'站点配置'	=>	U('admin/Global/siteopt'),
    			'注册配置'	=>	U('admin/Global/register'),
    			'邀请配置'	=>	U('admin/Global/invite'),
    			'积分配置'	=>	U('admin/Global/credit'),
    			'公告配置'	=>	U('admin/Global/announcement'),
    			'邮件配置'	=>	U('admin/Global/email'),
    			'附件配置'	=>	U('admin/Global/attachConfig'),
				'文章配置'	=>	U('admin/Global/document'),
    			'审核配置'	=>	U('admin/Global/audit'),
                '地区配置'    =>  U('admin/Tool/area'),
                '被关注的排行榜配置' =>  U('admin/User/follower'),
    		),
    	);

    	//内容
    	$menu['content'] 	=   array(
    		'内容管理'		=>  array(
				'广告管理'	=>	U('admin/Content/ad'),
    			'模板管理'	=>	U('admin/Content/template'),
    			'附件管理'	=>	U('admin/Content/attach'),
    			'评论管理'	=>	U('admin/Content/comment'),
				'公告管理'	=>	U('admin/Content/annocontent'),
    			'私信管理'	=>	U('admin/Content/message'),
    			'通知管理'	=>	U('admin/Content/notify'),
    			'动态管理'	=>	U('admin/Content/feed'),
    			'举报管理'	=>	U('admin/Content/denounce'),
    			'管理日志'   =>	U('admin/Content/adminLog'),
			    //'导航管理'   =>  U('admin/Content/topnav'),
    		),
    	);

    	//分类
    	$menu['category'] 	=   array(
    		'数据类型管理'		=>  array(
    			'基础数据类型'	=>	U('admin/Category/datadict'),
				'知识点管理'		=>	U('admin/Category/knowledge'),

    		),
    	);

    	//用户
    	$menu['user']		=	array(
    		'用户'			=>	array(
    			'用户管理'	=>	U('admin/User/user'),
    			'用户类型'	=>	U('admin/User/userGroup'),
    			'资料配置'	=>	U('admin/User/setField'),
    			'消息群发'	=>	U('admin/User/message'),
                '邀请统计'    =>  U('admin/Tool/inviteRecord'),
			    '登陆日志'	=>	U('admin/Login/index'),
    		),
    		'权限'			=>	array(
    			'节点管理'	=>	U('admin/User/node'),
    			'权限管理'	=>	U('admin/User/popedom'),
    		),
    	);

    	//应用
    	$menu['apps'] 		=	array(
    		'应用管理'		=>	array(
    			'应用列表'	=>	U('admin/Apps/applist'),
    			'应用安装'	=>	U('admin/Apps/install'),
    			'应用分组'	=>	U('admin/Apps/appGroup'),
    			//'漫游平台'	=>  SITE_URL . '/apps/myop/myop.php?my_suffix=' . urlencode('/appadmin/list')
    		),
    	);

        //移动管理-徐程亮添加
        $menu['mobile'] 		=	array(
            '移动管理'		=>	array(
                '版本管理'	=>	U('admin/Mobile/mobileMange'),
            ),
        );
    	
		$menu['apps']['系统应用']['微广播'] = U('weibo/Admin/index');
		
		$apas=model('App')->field('app_id,app_name,app_alias,admin_entry')->findAll();	
		foreach ($apas as $apass){
			$menu['apps']['系统应用'][$apass['app_alias']] =U($apass['app_name'].'/'.$apass['admin_entry']);
		}
    
		/**$appgroups = model('AppGoup')->field('group_id,group_name')->order('display_order ASC')->findAll();	
	    foreach ($appgroups as $g)
		{
			$apas=model('App')->where("FIND_IN_SET('".$g['group_id']."',`group_id`)")->field('app_id,app_name,app_alias,admin_entry')->findAll();	
			if($apas){
				foreach ($apas as $apass){
					$menu['apps'][$g['group_name']][$apass['app_alias']] =U($apass['app_name'].'/'.$apass['admin_entry']);
				}
			}
			else 
				$menu['apps'][$g['group_name']] =U('admin/Apps/applist');	
		}**/
		
        
        $menu['extension']	=	array(
    		'插件'			=>	array(
    			'插件列表'   =>  U('admin/Addons/index'),
    		),
    	);

		$addons = model('Addons')->getAddonsAdmin();
    	foreach($addons as $value){
			$menu['extension']['插件'][$value[0]] = U('admin/Addons/admin',array('pluginid' => $value[1]));
		}

        //广场
    	$menu['square'] 	=   array(
    		'广场管理'		=>  array(
				'栏目管理'	=>	U('square/Admin/category'),
    			'文章管理'	=>	U('square/Admin/blog'),
    			'视频管理'	=>	U('square/Admin/video'),
    			//'资源管理'	=>	U('square/Admin/resource'),
    		),
    	);
    	
    	//空间
    	$menu['space'] 	=   array(
    		'班级空间'		=>  array(
				'权限管理'	=>	U('space/Admin/power'),
    			'班干部管理'	=>	U('space/Admin/leader'),
				'每周之星管理'	=>	U('space/Admin/glory'),
    		),
    		'学校空间'		=>  array(
				'权限管理'	=>	U('space/Admin/category'),
    			'班干部管理'	=>	U('space/Admin/blog'),
    		),
    	);


    	//UCENTER管理
    	if(UC_SYNC){
			$menu['ucenter']	=	array(
	    		'UCenter'	=>	array(
	    			'首页'	=>  UC_API.'/admin.php?m=frame&a=main&iframe=1',
	    		)
	    	);
	    	/*$ucmenu = uc_menu_list();
	    	foreach ($ucmenu as $m) {
	    		$menu['ucenter']['UCenter'][$m['name']] = UC_API.'/admin.php?m='.$m['mod'].'&a='.$m['func'];
	    	}*/
    	}

    	return $menu;
    }
}