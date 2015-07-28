<?php
/**
 +------------------------------------------------------------------------------
 * 社区我的博客Action
 +------------------------------------------------------------------------------
 * @category	blog
 * @package		Lib/Action
 * @author		美美 <meimeili@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-1 上午05:36:47
 +------------------------------------------------------------------------------
 */
class IndexAction extends Action {
	/**
	 * 博客初始化操作，获取配置文件及应用信息
	 */
	public function _initialize() {
		$titles	=	D('SquareCategory')->where('p_id=1')->order('display_order ASC')->findAll();
		$this->assign('titles',$titles);
		$data['newblog'] = D('Blog','blog')->getBlogList(null,null,'readCount DESC',20,false);
		//$data['myblog'] = D('Blog','blog')->getFollowBlog($this->mid,10);
		$data['hot_user'] = D('Blog','blog')->getHotUser();
		$this->assign($data);
	}

	/**
	 *
	 */
	public function index() {
		$data['bloglist'] = D('Blog','blog')->getBlogList();
		$this->assign('selfuid',$this->mid);
		$this->assign($data);
		$this->display();
	}

	public function show(){
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$map['id'] = $id;
		$blog = D('Blog','blog')->getBloginfo($map,true);
		//print_r($blog);
		$this->assign('selfuid',$this->mid);
		$this->assign('blog',$blog);
		$this->display();
	}

	public function category(){
		$tags	=	$_REQUEST['tags'];
		if($tags==''||$tags=='请输入您要搜索的博客关键词')//如果不是关键字搜索
		{
			//通过栏目id查找
			if($_GET['id']){
				$id					=	intval($_GET['id']);
				$data['id'] = $id;
				$data['sc']			=	model('Category')->where('id='.$id)->find();
				$map['square_id'] = $id;
				$data['blog_class']	=	D('Blog','blog')->getBlogList($map);
				$data['titshow']    =   $data['sc']['category_name'];
				$data['path'] = arrayKeyTolower(procExecute("call vcPath(".$id.");"));
				$this->setTitle($data['sc']['category_name']);

			}else {//无查找条件

				$this->setTitle('博客中心');
				$data['sc']			=	model('Category')->where('id=1')->find();
				$data['blog_class']	=	D('Blog','blog')->getBlogList($map);
			}
		}else
		{
			$this->setTitle('搜索结果');
			$data['sc']['category_name']	=	'搜索结果';
			$data['blog_class']				=	$this->search($tags);
			$this->assign('tags',$tags);
		}
		$this->assign('selfuid',$this->mid);
		$this->assign($data);
		$this->display();
	}

	/**
	 * 发表博客的界面
	 */
	public function post(){
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if(!empty($id)){
			$map['uid'] = $this->mid;
			$map['id'] = $id;
			$blog = D('Blog','blog')->getBloginfo($map);
			$this->assign('blog',$blog);
		}
		$category  = D('Category')->getCategory($this->mid);
		if(count($category) == 0){
			$map['uid'] = $this->mid;
			$map['name'] = getUserName($this->mid).'的分类';
			$cate = D('Category')->addCategory($map);
			if($cate){
				$category  = D('Category')->getCategory($this->mid);
			}
		}
		$square  =  procExecute("call vcCategory(1);");
		$this->assign('blog_category',$category);
		$this->assign( 'square_category',$square );
		$this->display();
	}

	/**
	 * 个人中心界面
	 */
	public function usercate(){
		$mid = $this->mid;
		if(empty($mid)){
			$this->redirect('home/Public/login');
		}
		$cid = isset($_GET['cid']) ? $_GET['cid'] : '';
		if(empty($cid)){
			$uid = isset($_GET['uid']) ? $_GET['uid'] : $mid;
		}

		if(!empty($cid)){
			$blogList = D('Blog','blog')->getBlogByCateid($cid);
			$cateinfo=D('Category','blog')->getUserByCid($cid);
			if($cateinfo ==  false){
				redirect(U('blog/Index/usercate'));
			}
			$uid=$cateinfo['uid'];
			$title=$cateinfo['name'];
		}else if (!empty($uid)) {
			$map['uid'] = $uid;
			if($this->mid == $uid){
				$map['private'] = '';
			}
			$blogList = D('Blog','blog')->getBlogList($map);
			$title=getUserName($uid).'的博客';
		}
		$userinfo = getUserInfo($uid);

		if($userinfo['sex'] == '男'){
			$userinfo['xb'] = 1;
		}else{
			$userinfo['xb'] = 0;
		}
		$userinfo['data'] = D('Blog','blog')->getUserByuserId($uid,($uid == $this->mid));

		$data['followstate'] = D('Follow','weibo')->getState($this->mid, $uid, 0);
		//print_r($videoList);
		$this->assign('userinfo',$userinfo);
		$this->assign('bloglist',$blogList);
		$this->assign('uid',$uid);
		$this->assign('thisuid',$this->mid);
		$this->assign('cid',$cid);
		$this->assign('title',$title);
		$this->assign($data);
		$this->display();
	}

	/**
	 * 创建分类
	 */
	public function createcategory(){
		$cid = isset($_GET['cid']) ? $_GET['cid'] : '';
		if(!empty($cid)){
			$cateinfo=D('Category','blog')->getUserByCid($cid);
			$this->assign($cateinfo);
		}
		$this->display();
	}

	/**
	 * ajax添加分类名
	 */
	public function do_create_category(){
		$catename = isset($_POST['catename']) ? $_POST['catename'] : '';
		$cid = isset($_POST['cid']) ? $_POST['cid'] : '';
		if(!empty($catename)){
			$data['name'] = $map['name'] = $catename;
			$map['uid'] = $this->mid;
			$category = D('Category')->getCategoryInfo($map);
			if($category == false){
				if(empty($cid)){
					$cate = D('Category')->addCategory($map);
					if($cate){
						$re['status'] = 1;
						$re['info'] = '添加分类成功！';
						$re['cateid'] = $cate['id'];
						$re['catename'] = $cate['name'];
						echo json_encode($re);
						exit();
					}
				}else{
					unset($map['name']);
					$map['id'] = $cid;
					$cate = D('Category')->upCategory($map,$data);
					if($cate){
						$re['status'] = 1;
						$re['info'] = '更新分类成功！';
						echo json_encode($re);
						exit();
					}
				}

			}else{
				echo json_encode($this->showinfo('分类名称已经存在！'));
				exit();
			}
		}else{
			echo json_encode($this->showinfo('请输入分类名称！'));
			exit();
		}
	}

	/**
	 * 添加博客的操作
	 */
	public function doupdate(){
		$title =  addslashes($_POST['title']);
		$content = stripslashes($_POST['content']);

		if(empty($title)) {
			echo json_encode($this->showinfo('请填写标题'));
			exit();
		}

		if( mb_strlen($title, 'UTF-8') > 25 ) {
			echo json_encode($this->showinfo('标题不得大于25个字符'));
			exit();
		}
		//检查是否为空
		if( empty( $content ) || this_tags_html($content) == ''  ) {
			echo json_encode($this->showinfo('请填写文字内容'));
			exit();
		}

		//检查是否为空
		if( strlen(this_tags_html($content)) < 100) {
			echo json_encode($this->showinfo('博客内容不能小于100字！'));
			exit();
		}


		//处理发博客的数据
		$data = $this->__getPost();
		$category_name = M('blog_category')->where('id ='.t($_POST['category']))->find();
		$data['category_title'] = $category_name['name'];

		if(isset($data['id']) && !empty($data['id'])){
			//更新博客的操作
			$update = D('Blog')->where('id = '.$data['id'])->save($data);
			if( $update ) {
				$info['title'] = $title;
				$info['status'] = 1;
				$info['url'] = U('blog/Index/usercate');
				echo json_encode($info);
				exit();
			}else {
				$info['status'] = 0;
				$info['msg'] = '更新博客失败！';
				echo json_encode($info);
				exit();
			}

		}else{
			//添加博客的操作
			$data['cTime'] = time();
			$add = D('Blog')->add($data);

			if( $add ) {
				//社交圈专用start
				$ucInfo   = get_baseinfo_by_uid ( getUcUid ( getMid() ) ); // 获取用户UIA信息
				$param = array();
				$param['blogid'] = $add;
				$param['uid']    = getMid();
				$param['bjid']   = $ucInfo['bjid'];
				$param['zyid']   = $ucInfo['zyid'];
				$param['nj']     = $ucInfo['nj'];
				$param['yxid']   = $ucInfo['yxid'];
				$param['depid']  = $ucInfo['depid'];
				D('BlogLink')->addBlodLink($param);
				//社交圈专用end
				X('Credit')->setUserCredit($this->mid,'add_blog');
				$html = '【'.text($data['title']).'】'.getShort($content,80).U('blog/Index/show',array('id'=>$add,'mid'=>$this->mid));
				$images = matchImages($data['content']);
				$image  = $images[0]?$images[0]:false;
				$this->ajaxData = array('url'=>U('blog/Index/show',array('id'=>$add,'mid'=>$this->mid)),
					'id' =>$add,
					'html'=>$html,
					'image'=>$image,
					'title'=>t($_POST['title']),
				);
				$info['title'] = $title;
				$info['status'] = 1;
				$info['url'] = U('blog/Index/usercate');
				echo json_encode($info);
				exit();
			}else {
				$info['status'] = 0;
				$info['msg'] = '发表博客失败！';
				echo json_encode($info);
				exit();
			}
		}

	}



	public function delete(){
		$id = isset($_POST['bid']) ? $_POST['bid'] : '';
		if(!empty($id)){
			$map['uid'] = $this->mid;
			$map['id'] = $id;
			$blog = D('Blog')->where($map)->delete();
			if($blog){
				D('BlogLink')->delBlog(array('blogid'=>$id));
				$info['status'] = 1;
				$info['msg'] = '删除博客成功！';
				echo json_encode($info);
				exit();
			}else{
				$info['status'] = 0;
				$info['msg'] = '删除博客失败或你没有删除的权限！';
				echo json_encode($info);
				exit();
			}
		}
	}

	public function delcategory(){
		$cid = isset($_POST['cid']) ? $_POST['cid'] : '';
		if(!empty($cid)){
			$map['uid'] = $this->mid;
			$map['id'] = $cid;
			//ssq  2014-02-22  删除专辑    blog_link
			$blogids = D('blog')->field('id')->where(array('category'=>$cid))->findAll();
			foreach ($blogids as $key => $value) {
				$blogids_s[] = $value['id'];
			}
			$param['blogid'] = array('in',$blogids_s);
			D('BlogLink','blog')->delBlog($param);
// 			exit();
			///end
			$result = D('Category')->delCategroy($map);
			if($result){
				$info['status'] = 1;
				$info['msg'] = '删除分类成功！！';
				$info['url'] = U('blog/Index/usercate');
				echo json_encode($info);
				exit();
			}else{
				$info['status'] = 0;
				$info['msg'] = '删除分类失败或你没有删除权限！！';
				echo json_encode($info);
				exit();
			}
		}
	}
	private function __getPost() {
		$data['content']  = addslashes(this_tags_html(stripslashes($_POST['content'])));
		$data['uid']      = $this->mid;
		$data['category'] = intval($_POST['category']);
		$data['password'] = text($_POST['password']);
		$data['mention']  = $_POST['fri_ids'];
		$data['title']    = !empty($_POST['title']) ? addslashes($_POST['title']):"无标题";
		$data['private']  = intval($_POST['private']);
		if($data['private'] == 0){
			$data['square_id'] = $_POST['square'];
		}
		$data['canableComment'] = intval(t($_POST['cc']));
		$data['tags'] = text($_POST['tags']);
		$data['mTime'] = time();
		$data['name'] = getUserName($this->mid);
		if(!empty($_POST['id'])){
			$data['id'] = $_POST['id'];
		}
		if(empty($data['tags'])) unset($data['tags']);
		return $data;
	}
	/**
	+----------------------------------------------------------
	 * 按栏目搜索的搜索结果
	+----------------------------------------------------------
	 * @param int $id 所要显示的分组id
	 * @param String $where 搜索条件
	 * @return	Array 搜索到的视频的信息
	 * @author	美美2013-3-1
	+----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:27:01
	+----------------------------------------------------------
	 */
	private function searchcate($id,$order = 'mTime DESC',$page = 25) {
		$ids = procExecute("call vcCategory(".$id.");");
		$idss = $id;
		foreach ($ids as $k=>$v){
			$idss.=',';
			$idss .=$v['id'];
		}
		$map['square_id'] = array('in',$idss);
		$categoryList['content'] = M('blog')->where($map)->order($order)->findpage($page);
		return $categoryList;
	}

	/**
	+----------------------------------------------------------
	 * 根据title关键字分页查找
	+----------------------------------------------------------
	 * @param	String $tags 关键字
	 * @return	Array 查找到的博客信息
	 * @author	美美2013-3-1
	+----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:14:43
	+----------------------------------------------------------
	 */
	private function search($tagss)
	{

		$tags = explode(' ',$tagss);
		if($tags){
			$map['_string'] = ' private = 0 ';
			foreach ($tags as $v){
				$map['_string'] .= "and (title like '%".$v."%' or tags like '%".$v."%')";
			}

			$data['squr'] = M('blog')->where($map)->findPage(15);
			return $data['squr'];
		}
	}

	/**
	 * 显示信息
	 * @param $msg
	 * @return a
	 */
	private function showinfo($msg){
		$result = array('status'=>0,'msg'=>$msg);
		return $result;
	}


}