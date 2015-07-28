<?php
	/**
	 +------------------------------------------------------------------------------
	 * 广场首页Action
	 +------------------------------------------------------------------------------
	 * @category	square
	 * @package		Lib/Action
	 * @author		美美 <meimeili@gridinfo.com.cn>
	 * @version		1.0
	 +------------------------------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:36:13
	 +------------------------------------------------------------------------------
	 */
class IndexAction extends BaseAction{
   
   
	/**
	 +----------------------------------------------------------
	 * 广场首页
	 +----------------------------------------------------------
	 * @return	Array <返回类型(void的方法就不用该选项)>
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:35:44
	 +----------------------------------------------------------
	 */
	public function indexs()
	{
	  $this->setTitle('阳光广场');
	  $data['userInfo'] 	= 	$_SESSION['userInfo'];
	  $data['ucInfo'] 		= 	$_SESSION['ucInfo'];
	  //用户数量
	  $data['usercount']	=	M('user')->count();
	  //注册学校及班级数量
	  $data['count']		=	uc_get_schoolnum();
	  //社区动态
	  $data['dynainfo']		=	$this->dynamic();
	  //社区文摘
	  $data['com_blog']		=	$this->com_blog();
	  //社区明星
      $data['com_star']		= 	$this->com_star();
      //教育社团
      $data['edu_org']		=	$this->edu_org();
      // 社区精选
      $data['selected']		= 	$this->selected();
      //资源中心
      $data['resource']		= 	$this->resource();
      //社区投票
   	  $data['voting']		= 	$this->voting();
   	  //视频中心
      $data['video_center']	= 	$this->video_center();
      $this->assign($data);
   	  $this->display('index');
    }
	/**
	 +----------------------------------------------------------
	 * 社区文摘
	 +----------------------------------------------------------
	 * @return	Array 社区文摘信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:37:01
	 +----------------------------------------------------------
	 */
	private function com_blog()
	{
		$com_blog['cate']=M('square_category')->where('p_id=1')->findAll();
		$com_blog['main']=M('Blog')->order('commentCount DESC')->limit(6)->findAll();
	   //获取各分类中的博客信息
		foreach ($com_blog['cate'] as $v)
		{
			$com_blog['category'][$v['id']]=$this->com_blog_category($v['id']);
		}
		return $com_blog;
	}
	/**
	 +----------------------------------------------------------
	 *根据博客分类的ID获取该分类下的所有博客
	 +----------------------------------------------------------
	 * @param	int $id 栏目id
	 * @return	Array 所获取的博客信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:37:36
	 +----------------------------------------------------------
	 */
	private function com_blog_category($id)
	{	
		$ids = procExecute("call vcCategory(".$id.");");
    	$idss = $id;
		foreach ($ids as $k=>$v){
			$idss.=',';
			$idss .=$v['id'];
		}
		$map['id'] = array('in',$idss);
		$Squ = M()->table('ts_square_blog sblog,ts_blog blog')
					->where("blog.id=sblog.blog_id and sblog.square_id in(".$idss.")")
					->limit(6)
					->findAll();
		return $Squ;
	}
	/**
	 +----------------------------------------------------------
	 * 社区动态
	 +----------------------------------------------------------
	 * @return	Array 社区动态信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:39:32
	 +----------------------------------------------------------
	 */
	private function dynamic()
	{
		$data['dynainfo'] = uc_get_users_by_identity();
		foreach ($data['dynainfo'] as &$r){
			$map['uc_uid'] = array('in',$r['uc_ids']);
			$uids = M('ucenter_user_link')->field('uid')->where($map)->findAll();
			$return_uids = array();
			arrayFormat($uids,$return_uids);
			$ids = implode(',', $return_uids);
			$r['weibos'] = M()->query("SELECT * FROM (SELECT * FROM `".C('DB_PREFIX')."weibo` WHERE (`uid` IN ($ids)) ORDER BY ctime DESC) temp GROUP BY uid ORDER BY ctime DESC LIMIT 4");
			foreach ($r['weibos'] as &$obj){
				$uc_uid = M('ucenter_user_link')->field('uc_uid')->where('uid='.$obj['uid'])->find();
				//$deptinfo = uc_dept_get_uid($uc_uid['uc_uid']);
				//$obj['deptname'] = $deptinfo['DepartName'];
			}
		}//var_dump($data['dynainfo']);
		return $data['dynainfo'];
	}
	/**
	 +----------------------------------------------------------
	 * 社区明星
	 +----------------------------------------------------------
	 * @return	Array 社区明星信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:40:30
	 +----------------------------------------------------------
	 */
	private function com_star()
	{
		$hot_user  = D('Follow', 'weibo')->getTopFollowerUser(2,12);
		$hot_user = array_slice($hot_user, 0, 12);
		return $hot_user;
	}
	/**
	 +----------------------------------------------------------
	 * 教育社团
	 +----------------------------------------------------------
	 * @return	Array 教育社团信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:56:22
	 +----------------------------------------------------------
	 */
	private function edu_org()
	{
		//按时间查找教育社团
		$edu_org['main'] = M('group')->order('cTime DESC')->limit(4)->findAll();
		
		//获取社团分类前4个
		$group = M('')->query("select cid1 from ".C('DB_PREFIX')."group GROUP BY cid1 Having Count(*) > 0 limit 0,4");
		$split = $fids = '';
		foreach($group as $val){
			$fids .= $split.$val['cid1'];
			$split = ',';
		}
		
		//获取分类中教育社团信息
		$edu_org['cate'] = M('group_category')->where("id in (".$fids.")")->findAll();
		foreach ($edu_org['cate'] as $v){
			$edu_org[$v['id']] = $this->edu_org_category($v['id']);
		}
		return $edu_org;
	}
	/**
	 +----------------------------------------------------------
	 * 根据教育社团分类获取
	 +----------------------------------------------------------
	 * @param	int $id 社团分类id
	 * @return	Array 分类下社团信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:56:59
	 +----------------------------------------------------------
	 */
	private function edu_org_category($id)
	{	
		$Squ = M('group')->where('cid1 = '.$id)->limit(4)->findAll();
		return $Squ;
	}
	/**
	 +----------------------------------------------------------
	 *社区精选
	 +----------------------------------------------------------
	 * @return	Array 博客中点击率最高的信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:57:58
	 +----------------------------------------------------------
	 */
	private function selected()
	{
		$selected[1] = M('blog')->order('readCount DESC')->limit(1)->findAll();
		$selected[2] = M('blog')->order('readCount DESC')->limit('1,4')->findAll();
		return $selected;
	}
	/**
	 +----------------------------------------------------------
	 * 资源中心
	 +----------------------------------------------------------
	 * @return	Array 资源中心信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:59:05
	 +----------------------------------------------------------
	 */
	private function resource()
	{
		//按时间获取资源
		$resource['main'] = M('resource')->order('time DESC')->limit(5)->findAll();
		$resource['cate'] = M('category_dictionary')->where("DataType='Resource'")->findAll();
		
		//获取分类下的资源
		foreach ($resource['cate'] as $v)
		{
			$resource['category'][$v['DataCode']] = $this->category_resource($v['DataCode']);
		}
		return $resource;
	}
	/**
	 +----------------------------------------------------------
	 * 根据资源类型获取资源分页面
	 +----------------------------------------------------------
	 * @param	 String $stage 资源类型
	 * @return	Array 资源信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午04:59:42
	 +----------------------------------------------------------
	 */
	private function category_resource($stage){
	   $result = M('resource')->where("class='".$stage."'")->limit(5)->findAll();
	   return $result;
	}
	/**
	 +----------------------------------------------------------
	 * 社区投票
	 +----------------------------------------------------------
	 * @return	Array 投票中点击率最高的投票
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午05:00:17
	 +----------------------------------------------------------
	 */
	private function voting()
	{
		//获取最新一条投票
		$voting['title'] = M('vote')->order('rTime DESC')->limit(1)->find();
		//投票的所有选项
		$voting['opt']   = M('vote_opt ')->where('vote_id='.$voting['title']['id'])->findAll();
		//每个选项所占比例计算
		foreach ($voting['opt'] as $k=>$v)
		{
			$voting['opt'][$k]['num'] = $v['num']*100/$voting['title']['vote_num'];
			$voting['opt'][$k]['num'] = number_format($voting['opt'][$k]['num'], 2, '.', '');
		}
		return $voting;
	}
	/**
	 +----------------------------------------------------------
	 * 视频中心
	 +----------------------------------------------------------
	 * @return	Array 视频展示信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午05:02:03
	 +----------------------------------------------------------
	 */
	private function video_center()
	{	//视频中心显示
		$video_center['main'] = M('Video')->order('readCount DESC')->limit(9)->findAll();
		//查找视频中心分类
		$video_center['cate'] = M('square_category')->where('p_id=2')->findAll();
		//获取各分类中的视频信息
		foreach ($video_center['cate'] as $v)
		{
			$video_center['category'][$v['id']] = $this->video_category($v['id']);
		}
		return $video_center;
	}
	/**
	 +----------------------------------------------------------
	 * 根据视频中心分类获取视频
	 +----------------------------------------------------------
	 * @param	int $id 栏目id
	 * @return	Array 该栏目下视频的信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午05:03:07
	 +----------------------------------------------------------
	 */
	private function video_category($id)
	{	
		$Squ =  procExecute("call vcTree(".$id.",9,'".C('DB_PREFIX')."video.readCount');");
		return $Squ;
		
	}
 
}
?>