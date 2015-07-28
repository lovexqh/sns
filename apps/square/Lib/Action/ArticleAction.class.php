<?php
/**
 +------------------------------------------------------------------------------
 * 广场博客中心Action
 +------------------------------------------------------------------------------
 * @category	square
 * @package		Lib/Action
 * @author		美美 <meimeili@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-1 上午03:03:17
 +------------------------------------------------------------------------------
 */
class ArticleAction extends BaseAction
{
	/**
	 +----------------------------------------------------------
	 * 初始化加载
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:04:43
	 +----------------------------------------------------------
	 */
    public function _initialize() {
    	parent::_initialize();
		//dump($_SESSION['ucInfo']);
    	//标题信息加载
		$titles	=	D('SquareCategory')->where('p_id=1')->order('display_order ASC')->findAll();
		//左侧分类树
		$sqcategory = D('SquareCategory')->getTrees(1);	
    	$this->assign('sqcategory',$sqcategory);
    	
		$this->assign('titles',$titles);
    }
	/**
	 +----------------------------------------------------------
	 * 博客中心主页面
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:04:43
	 +----------------------------------------------------------
	 */
	public function index()
	{
		//exit();
		$this->setTitle('博客中心');
		//热门博客
		$data['blog_hot']		=	$this->blog_hot();
		//最新更新
		$data['blog_class']		=	$this->blog_new();
		$data['blog_class']['html']=str_replace("href=", "url=", $data['blog_class']['html']);
		foreach ($data['blog_class']['data'] as $key=>$value){
			$data['blog_class']['data'][$key]['content']=$this->blog_summary(htmlspecialchars_decode($value['content']),300);
			if (!empty($value['tags'])) $data['blog_class']['data'][$key]['tags']=explode(',', $value['tags']);
		}
		
		//分区博客展示
		//$data['part']			=	$this->part();
		//推荐用户
		$data['user_hot']		=	$this->user_hot();
	
		$this->assign($data);
		
		// 否异步加载
		$p=$_GET['p'];
		if(!empty($_GET['p'])){
			$this->display(blog_list);
			exit();
		}
		$this->display();
	}
	/**
	 +----------------------------------------------------------
	 * 博客分区展示
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:04:43
	 +----------------------------------------------------------
	 */
	private function part()
	{
		//获取所有一级分类
		$data['title']=D('SquareCategory')->where('p_id=1')->order('display_order ASC')->findAll();
		
		foreach($data['title'] as $v)
		{
			//分别获取分类下的博客
			$data[$v['category_name']] =$this->categoryList($v['id'],'1','readCount',6);
		}
		return $data;
	}
	/**
	 +----------------------------------------------------------
	 * 按栏目检索博客AJAX
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	Array <返回类型(void的方法就不用该选项)>
	 * @author	美美2013-6-21
	 +----------------------------------------------------------
	 * 创建时间：	2013-6-21 上午03:12:50
	 +----------------------------------------------------------
	 */
	public function searchs(){
		$id = intval($_POST['id']);
		$data['blog_class'] = $this->searchcate($id,'1');
		$data['path'] = arrayKeyTolower(procExecute("call vcPath(".$id.");"));
		$this->assign($data);
		$this->display();
	}
	/**
	 +----------------------------------------------------------
	 * 获取分区内分类的博客
	 +----------------------------------------------------------
	 * @param int $id 栏目id
 	 * @param String $where 搜索条件
 	 * @param String $order 排序方式
 	 * @param String $limit 获取条数
	 * @return	array 博客信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:07:07
	 +----------------------------------------------------------
	 */
    private function categoryList($id,$where,$order,$limit) {
		$ids = procExecute("call vcCategory(".$id.");");
    	$idss = $id;
		foreach ($ids as $k=>$v){
			$idss.=',';
			$idss .=$v['id'];
		}
		$map['id'] = array('in',$idss);
		$prefix=C('DB_PREFIX');
		$where = service('ForeAdmin')->getAuditStatus("blog.private=0 and blog.id=sblog.blog_id and sblog.square_id in(".$idss.") AND blog.status=1", 0, 'blog');
		$categoryList = M()->table($prefix."square_blog sblog,".$prefix."blog blog")
								->where($where)
								->order($order)
								->limit($limit)
								->findAll();
		return $categoryList;
    }
	/**
	 +----------------------------------------------------------
	 * 最新博客展示
	 +----------------------------------------------------------
	 * @return	Array 最新博客信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:10:17
	 +----------------------------------------------------------
	 */
	private function blog_new()
	{
		$where = service('ForeAdmin')->getAuditStatus('a.private=0', 0, 'blog');
		/* $blog_new=M()->query("
				SELECT a.id,a.uid,a.title,a.readCount,a.content,a.tags,a.cTime,b.square_id FROM ".C('DB_PREFIX')."blog a,".C('DB_PREFIX')."square_blog b WHERE a.id in (
				SELECT blog_id FROM ".C('DB_PREFIX')."square_blog ) AND a.id=b.blog_id and ".$where."
				ORDER BY cTime DESC limit 10"); */
		$limit=10;
		$blog_new=M()->table("".C('DB_PREFIX')."blog a,".C('DB_PREFIX')."square_blog b")
		->field("a.id,a.uid,a.title,a.readCount,a.content,a.tags,a.cTime,b.square_id")
		->where("a.id in ( SELECT blog_id FROM ".C('DB_PREFIX')."square_blog ) AND a.id=b.blog_id and ".$where)
		->order('a.cTime DESC')
		->findPage($limit);
		//echo M()->getlastsql();
		return $blog_new;
	}
	/**
	 +----------------------------------------------------------
	 * 热门博客展示
	 +----------------------------------------------------------
	 * @return	Array 热门博客信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:11:26
	 +----------------------------------------------------------
	 */
	private function blog_hot()
	{
		$where = service('ForeAdmin')->getAuditStatus('private=0', 0, 'blog');
		$blog_hot=M()->query("SELECT * FROM ".C('DB_PREFIX')."blog WHERE id in (
							  SELECT blog_id FROM ".C('DB_PREFIX')."square_blog ) and ".$where."
							  ORDER BY readCount DESC limit 10");
		return $blog_hot;
	}
	/**
	 +----------------------------------------------------------
	 * 推荐用户展示
	 +----------------------------------------------------------
	 * @return	Array 推荐用户信息
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:12:40
	 +----------------------------------------------------------
	 */
	private function user_hot()
	{
		$hot_user = D('blog')->query("SELECT uid, count( 1 ) AS count FROM `".C('DB_PREFIX')."blog` GROUP BY uid ORDER BY count DESC limit 10");
		return $hot_user;
	}
	/**
	 +----------------------------------------------------------
	 * 博客内容展示
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:13:28
	 +----------------------------------------------------------
	 */
	public function context()
	{
		$blog_id	=	intval($_GET['id']);//获取博客id
		
		if ($blog_id) {
			//博客所有信息
			$data['blog_content']	=	D('Blog')->where('id='.$blog_id)->find();
			
			$data['blog_content']['count']    = D('Blog')->where( "uid = '".$data['blog_content']['uid']."' AND status = 1 " )->count();
            $data['blog_content']['num']      = D('Blog')->where( 'id <'.$data['blog_content']['id']." AND status = 1 AND uid =".$uid )->count()+1;
            $data['blog_content']['content']  = htmlspecialchars_decode($data['blog_content']['content']);
            
			//博客点击数加1
			$data['blog_content']['readCount']	=	$data['blog_content']['readCount']+1;
			D('Blog')->where("id = $_GET[id]")->save($data['blog_content']);
			
			//博客所属用户拥有的博客数
			$data['count']			=	D('Blog')->where('status in(0,1) and uid='.$data['blog_content']['uid'])->count();
			$this->setTitle($data['blog_content']['title']);
			
			//相关博客
			$category = D('SquareBlog')->where('blog_id='.$blog_id)->find();
			$data['path'] = arrayKeyTolower(procExecute("call vcPath(".$category['square_id'].");"));
			$where = service('ForeAdmin')->getAuditStatus("blog.private=0 and blog.id=sblog.blog_id and sblog.square_id =".$category['square_id'], 0, 'blog');
			$data['relation'] = M()->table("".C('DB_PREFIX')."square_blog sblog,".C('DB_PREFIX')."blog blog")
								->where($where)
								->limit(5)
								->findAll();
								
			
			$this->assign($data);
			$this->display();
		}else 
			$this->error('非法操作！');
	}
	/**
	 +----------------------------------------------------------
	 * 博客中心分区页面
	 +----------------------------------------------------------
	 * @author	美美2013-3-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-1 上午03:14:13
	 +----------------------------------------------------------
	 */
	public function myblog()
	{
		$tags	=	$_REQUEST['tags'];
		if($tags==''||$tags=='请输入您要搜索的博客关键词，例小学语文')//如果不是关键字搜索
		{
			//通过栏目id查找
			if($_GET['id']){
				$id					=	intval($_GET['id']);
				$data['sc']			=	model('SquareCategory')->where('id='.$id)->find();
				$data['blog_class']	=	$this->searchcate($id,'1');
				$data['blog_class']	=   $data['blog_class']['content'];
				$data['titshow']    =   $data['sc']['category_name'];
				$data['path'] = arrayKeyTolower(procExecute("call vcPath(".$id.");"));
				$this->setTitle($data['sc']['category_name']);
				
			}elseif ($_GET['blogid']){//通过博客分类的分类id查找
				//找到该博客的分类id
				$ids				=	D('SquareBlog')->field('square_id')->where('blog_id='.$_GET['blogid'])->find();
				$id					=	$ids['square_id'];
				$data['path'] = arrayKeyTolower(procExecute("call vcPath(".$id.");"));
				$data['sc']			=	model('SquareCategory')->where('id='.$id)->find();
				$data['blog_class']	=	$this->searchcate(id,'1');
				
				$this->setTitle($data['sc']['category_name']);
			}else {//无查找条件
				
				$this->setTitle('博客中心');
				$data['sc']			=	model('SquareCategory')->where('id=1')->find();
				$data['blog_class']	=	$this->searchcate(1,'1');
				$data['blog_class'] =   $data['blog_class']['content'];
			}
		}else 
		{
			$this->setTitle('搜索结果');
			$data['sc']['category_name']	=	'搜索结果';
			$data['blog_class']				=	$this->search($tags);
		}	
		
		//左侧树形结构展示
		$data['sqcategory']=D('SquareCategory')->getTrees(1);
		
		$data['blog_class']['html']=str_replace("href=", "url=", $data['blog_class']['html']);
		foreach ($data['blog_class']['data'] as $key=>$value){
			$data['blog_class']['data'][$key]['content']=$this->blog_summary(htmlspecialchars_decode($value['content']),300);
			if (!empty($value['tags'])) $data['blog_class']['data'][$key]['tags']=explode(',', $value['tags']);
		}
		$this->assign($data);
		$this->display();
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
			foreach ($tags as $v){
				$where .= "and ".C('DB_PREFIX')."square_category.category_name like '%".$v."%'";
				$whblog .= "and (title like '%".$v."%' or tags like '%".$v."%')";
			}
			$ids = M()->query("select blog_id FROM ".C('DB_PREFIX')."square_blog,".C('DB_PREFIX')."square_category where 
									".C('DB_PREFIX')."square_blog.square_id=".C('DB_PREFIX')."square_category.id ".$where);
			$ids1= M("blog")->field("id")->where(service('ForeAdmin')->getAuditStatus('private=0', 0, 'blog').$whblog)->findAll();

			foreach ($ids as $k=>$v){
				if($k!==0)$idss.=',';
				$idss .=$v['blog_id'];
			}
			foreach ($ids1 as $k=>$v){
				if(!empty($idss))$idss.=',';
				$idss .=$v['id'];
			}
			$map['id'] = array('in',$idss);
			$where = "id in(".$idss.") AND ".service('ForeAdmin')->getAuditStatus('private=0', 0, 'blog');

			$data['squr'] = D('Blog')->where($where)->findPage(25);
			return $data['squr'];
		}
	}
	/**
	 +----------------------------------------------------------
	 * 排行榜
	 +----------------------------------------------------------
	 * @author	美美2013-6-21
	 +----------------------------------------------------------
	 * 创建时间：	2013-6-21 上午05:32:53
	 +----------------------------------------------------------
	 */
	public function chart(){
		$id=1;
		$data['blogcontent'] = $this->searchcate($id,'1');
		$data['blogcontent'] = $data['blogcontent']['content'];
		$data['titshow']    =   '排行榜';
		$data['path'][0]['cname'] = '排行榜';
		$this->assign($data);
		$this->display();
	}
	/**
	 +----------------------------------------------------------
	 * 排行榜搜索
	 +----------------------------------------------------------
	 * @author	美美2013-6-24
	 +----------------------------------------------------------
	 * 创建时间：	2013-6-24 上午06:52:05
	 +----------------------------------------------------------
	 */
	public function chartsearch(){
		if(empty($_POST['id'])) 
			$id=1;
		else 
			$id=$_POST['id'];
		$data['blogcontent'] = $this->searchcate($id,'1');
		$data['blogcontent'] = $data['blogcontent']['content'];
		$data['path'] = arrayKeyTolower(procExecute("call vcPath(".$id.");"));
		$this->assign($data);
		$this->display();
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
    private function searchcate($id,$where) {
    	$ids = procExecute("call vcCategory(".$id.");");
    	$idss = $id;
		foreach ($ids as $k=>$v){
			$idss.=',';
			$idss .=$v['id'];
		}
		$map['id'] = array('in',$idss);
		$prefix=C('DB_PREFIX');
		$where = service('ForeAdmin')->getAuditStatus("blog.private=0 and blog.id=sblog.blog_id and sblog.square_id in(".$idss.")",0,'blog');
		$categoryList['content'] = M('')->table($prefix."square_blog sblog,".$prefix."blog blog")
								->where($where)
								->order('blog.readCount DESC')
								->findpage(25);
		return $categoryList;
    }	
    
    
    /**
     * 生成摘要
     * @param (string) $body
     *  正文
     * @param (int) $size
     *  摘要长度
     * @param (int) $format
     *  输入格式 id
     */
    
    function blog_summary($body, $size, $format = NULL){
    	$_size = mb_strlen($body, 'utf-8');
    
    	if($_size <= $size) return $body;
    
    	// 输入格式中有 PHP 过滤器
    	//  if(!isset($format) && filter_is_php($format)){
    	//   return $body;
    	//  }
    
    	$strlen_var = strlen($body);
    
    	// 不包含 html 标签
    	if(strpos($body, '<') === false){
    		return mb_substr($body, 0, $size);
    	}
    
    	// 包含截断标志，优先
    	if($e = strpos($body, '<!-- break -->')){
    		return mb_substr($body, 0, $e);
    	}
    
    	// html 代码标记
    	$html_tag = 0;
    
    	// 摘要字符串
    	$summary_string = '';
    
    	/**
    	 * 数组用作记录摘要范围内出现的 html 标签
    	 * 开始和结束分别保存在 left 和 right 键名下
    	 * 如字符串为：<h3><p><b>a</b></h3>，假设 p 未闭合
    	 * 数组则为：array('left' => array('h3', 'p', 'b'), 'right' => 'b', 'h3');
    	 * 仅补全 html 标签，<? <% 等其它语言标记，会产生不可预知结果
    	 */
    	$html_array = array('left' => array(), 'right' => array());
    	for($i = 0; $i < $strlen_var; ++$i) {
    		if(!$size){
    			break;
    		}
    
    		$current_var = substr($body, $i, 1);
    
    		if($current_var == '<'){
    			// html 代码开始
    			$html_tag = 1;
    			$html_array_str = '';
    		}else if($html_tag == 1){
    			// 一段 html 代码结束
    			if($current_var == '>'){
    				/**
    				 * 去除首尾空格，如 <br /  > < img src="" / > 等可能出现首尾空格
    				 */
    				$html_array_str = trim($html_array_str);
    				/**
    				 * 判断最后一个字符是否为 /，若是，则标签已闭合，不记录
    				*/
    				if(substr($html_array_str, -1) != '/'){
    					// 判断第一个字符是否 /，若是，则放在 right 单元
    					$f = substr($html_array_str, 0, 1);
    					if($f == '/'){
    						// 去掉 /
    						$html_array['right'][] = str_replace('/', '', $html_array_str);
    					}else if($f != '?'){
    						// 判断是否为 ?，若是，则为 PHP 代码，跳过
    						/**
    						 * 判断是否有半角空格，若有，以空格分割，第一个单元为 html 标签
    						 * 如 <h2 class="a"> <p class="a">
    						 */
    						if(strpos($html_array_str, ' ') !== false){
    							// 分割成2个单元，可能有多个空格，如：<h2 class="" id="">
    							$html_array['left'][] = strtolower(current(explode(' ', $html_array_str, 2)));
    						}else{
    							/**
    							 * * 若没有空格，整个字符串为 html 标签，如：<b> <p> 等
    							 * 统一转换为小写
    							 */
    							$html_array['left'][] = strtolower($html_array_str);
    						}
    					}
    				}
    
    				// 字符串重置
    				$html_array_str = '';
    				$html_tag = 0;
    			}else{
    				/**
    				 * 将< >之间的字符组成一个字符串
    				 * 用于提取 html 标签
    				 */
    				$html_array_str .= $current_var;
    			}
    		}else{
    			// 非 html 代码才记数
    			--$size;
    		}
    		$ord_var_c = ord($body{$i});
    		switch (true) {
    			case (($ord_var_c & 0xE0) == 0xC0):
    				// 2 字节
    				$summary_string .= substr($body, $i, 2);
    				$i += 1;
    				break;
    			case (($ord_var_c & 0xF0) == 0xE0):
    				// 3 字节
    				$summary_string .= substr($body, $i, 3);
    				$i += 2;
    				break;
    			case (($ord_var_c & 0xF8) == 0xF0):
    				// 4 字节
    				$summary_string .= substr($body, $i, 4);
    				$i += 3;
    				break;
    			case (($ord_var_c & 0xFC) == 0xF8):
    				// 5 字节
    				$summary_string .= substr($body, $i, 5);
    				$i += 4;
    				break;
    			case (($ord_var_c & 0xFE) == 0xFC):
    				// 6 字节
    				$summary_string .= substr($body, $i, 6);
    				$i += 5;
    				break;
    			default:
    				// 1 字节
    				$summary_string .= $current_var;
    		}
    	}
    
    	if($html_array['left']){
    		/**
    		 * 比对左右 html 标签，不足则补全
    		 */
    
    		/**
    		 * 交换 left 顺序，补充的顺序应与 html 出现的顺序相反
    		 * 如待补全的字符串为：<h2>abc<b>abc<p>abc
    		 * 补充顺序应为：</p></b></h2>
    		 */
    		$html_array['left'] = array_reverse($html_array['left']);
    
    		foreach($html_array['left'] as $index => $tag){
    			// 判断该标签是否出现在 right 中
    			$key = array_search($tag, $html_array['right']);
    			if($key !== false){
    				// 出现，从 right 中删除该单元
    				unset($html_array['right'][$key]);
    			}else{
    				// 没有出现，需要补全
    				$summary_string .= '</'.$tag.'>';
    			}
    		}
    	}
    	return $summary_string;
    }
    
    
}