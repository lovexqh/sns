<?php
/**
+------------------------------------------------------------------------------
 * 迷你博客Model层。操作相关迷你博客的数据业务逻辑Model
+------------------------------------------------------------------------------
 * @category	BLOG
 * @package		Lib/Model
 * @author		美美 <meimeili@gridinfo.com.cn>
 * @version		1.0
+------------------------------------------------------------------------------
 * 创建时间：	2013-3-3 下午01:19:33
+------------------------------------------------------------------------------
 */
class BlogModel extends Model {
    public $config = null;
	protected $mid = '';

	/**
	 * Model初始化操作
	 */
	public function _initialize() {
        global $ts;
        $this->config = model('Xdata')->lget('blog');
        $this->mid = $ts['user']['uid'];
    }

	/**
	 * 得到博客列表信息
	 * @param null $map
	 * @param null $field
	 * @param null $order
	 * @param null $limit
	 * @param $uid
	 * @return mixed
	 */
	public function getBlogList($map = null, $field=null, $order = 'cTime DESC', $limit = 10,$ifpage=true) {
        $limit = isset($this->config['limitpage']) ? $this->config['limitpage'] : $limit ;     //连贯查询.获得数据集

		if(isset($map['private']) && empty($map['private'])){
			unset($map['private']);
		}else{
			$map['private'] = 0;
		}

		if($ifpage){
			$result = $this->where($map)->field($field)->order($order)->findPage($limit);
		}else{
			$result = $this->where($map)->field($field)->order($order)->limit($limit)->select();
		}
        return $result;
    }


	public function getFollowBlog($uid,$limit="10"){

		// 获取博主关注的UID
		$blogAuthorUids = M('weibo_follow')->where('fid='.$uid)->field('uid')->findAll();
		$blogAuthorUids = getSubByKey($blogAuthorUids, 'uid');
		$authorMap = implode(',', $blogAuthorUids);
		$map['_string'] = ' private = 0 OR (uid IN ('.$authorMap.') AND private = 2)';
		$result = M('blog')->where($map)->field('id,title')->order('mTime DESC')->limit($limit)->select();
		return $result;

	}

	/**返回博客的内容信息
	 * @param $id
	 * @return bool
	 */
	public function getBloginfo($map=null,$setnum = false){
		//$map['private'] = 0;
		//$map['status'] = 1;
		//$map['id'] = $id;
		$blog = $this->where($map)->find();
		if($setnum){
			$this->where($map)->setInc('readCount');
		}
		return $blog;

	}

	/**
	 * 得到用户排行榜
	 * @param int $limit
	 * @return mixed
	 */
	public function getHotUser($limit = 10){
		$hot_user = D('blog')->query("SELECT uid, count( 1 ) AS count FROM `".C('DB_PREFIX')."blog` WHERE uid in (SELECT uid FROM `".C('DB_PREFIX')."user` ) GROUP BY uid ORDER BY count DESC limit ".$limit);
		return $hot_user;
	}

	/**
	 * 得到对应分类的博客信息
	 */
	public function getBlogByCateid($cid,$page = 15){
		//$map['status'] = 1;
		$map['category'] = $cid;
		$blog = M('blog')->where($map)->order('cTime DESC')->findpage($page);
		return $blog;
	}

	public function getUserByuserId($uid,$isself = false){
		$count['following'] = M('weibo_follow')->where("uid=$uid AND type=0")->count();
		$count['follower'] = M('weibo_follow')->where("fid=$uid AND type=0")->count();
		$map['_string'] = " uid=$uid ";
		if(!$isself){
			$map['_string'] .= ' AND private = 0 ';
		}
		$count['blog']=M('blog')->where($map)->count();
		$count['readCount']= M('blog')->where("uid=$uid")->sum('readCount');

		$data['count']=$count;
		$data['uid']=$uid;
		$data['usercate'] = M('blog_category')->where('uid = '.$uid)->select();
		return $data;
	}

	/*
	 * 按order顺序显示num数量的博客信息
	 */
	public function getSpaceBlogList($num = 8,$order= 'mTime DESC'){
		$blog = M('blog')->where('private = 0')->order($order)->limit($num)->select();
		return $blog;
	}
	
	/**
	 * 获取社交圈成员的博客
	 * @param string $bjid
	 * @param string $zyid
	 * @param string $nj
	 * @param string $yxid
	 * @return unknown
	 */
	function getBlogForSociety($param,$limit=10){
		if($param['uidstr'] != null)
			$map['bl.uid'] =array('in',$param['uidstr']);
		if($param['bjid'] != null)
			$map['bl.bjid'] = $param['bjid'];
		if($param['zyid'] != null)
			$map['bl.zyid'] = $param['zyid'];
		if($param['nj'] != null)
			$map['bl.nj']   = $param['nj'];
		if($param['yxid'] != null)
			$map['bl.yxid'] = $param['yxid'];
		if($param['depid'] != null)
			$map['bl.depid'] = $param['depid'];
		$map['b.private'] = 0;
		$blog_result = M()->table("".C('DB_PREFIX')."blog_link as bl left join ".C('DB_PREFIX')."blog as b on bl.blogid=b.id")
		->field("b.id,b.uid,b.title,b.readCount,b.commentCount,b.content,b.tags,b.cTime")
		->where($map)
		->order("b.cTime desc")
		->findPage($limit);
		return $blog_result;
	}
	/**
	 * 获取社交圈成员的博客 for api
	 * @param string $bjid
	 * @param string $zyid
	 * @param string $nj
	 * @param string $yxid
	 * @return unknown
	 */
	function getBlogForSocietyForApi($param,$page=1,$count=10){
		$limit = ($page-1)*$count;
		$limit = $limit.",".$count;
		if($param['uidstr'] != null)
			$map['bl.uid'] =array('in',$param['uidstr']);
		if($param['bjid'] != null)
			$map['bl.bjid'] = $param['bjid'];
		if($param['zyid'] != null)
			$map['bl.zyid'] = $param['zyid'];
		if($param['nj'] != null)
			$map['bl.nj']   = $param['nj'];
		if($param['yxid'] != null)
			$map['bl.yxid'] = $param['yxid'];
		if($param['depid'] != null)
			$map['bl.depid'] = $param['depid'];
		$map['b.private'] = 0;
		$blog_result = M()->table("".C('DB_PREFIX')."blog_link as bl left join ".C('DB_PREFIX')."blog as b on bl.blogid=b.id")
		->field("b.id,b.uid,b.title,b.readCount,b.commentCount,b.content,b.tags,b.cTime")
		->where($map)
		->order("b.cTime desc")
		->limit($limit)
		->select();
		return $blog_result;
	}
	
	/**
	 * 获取所有博客列表    同步社交圈blog_link表
	 * ----------------------------------
	 * @author ssq     2014-2-12
 	 */
	function getBlogLists(){
		$result = D('blog')
				->field("id,uid")
				->order('uid')
				->select();
		return $result;
	}
}
