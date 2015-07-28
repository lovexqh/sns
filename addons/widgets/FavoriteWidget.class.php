<?php
/**
 * 收藏插件Widget
 */
class FavoriteWidget extends Widget{
	
	
	/**
	 * 收藏插件Widget
	 * 
	 * $data的参数:
	 * array(
	 * 	'appname'(可选)	=> '应用名', 
	 *  'action'=>'Acton名',
	 *  'method'=>'方法名'，
	 *  'fid'=>'收藏的ID'
	 * )
	 * 
	 * @see Widget::render()
	 */
	public function render($data){	
		
		//参数获取d
		$data['title'] = $data['title'] ? $data['title'] : '';
		$data['myuid'] = $data['myuid'] ? $data['myuid'] : '';
		$data['mid'] = $_SESSION['mid'];
		$data['type'] = $data['type'] ? $data['type'] : 'normal';
		$data['count'] = 1 == $data['count'] ? $data['count'] : 0;
		switch($data['type']){
		    case 'link':
				$data['app'] = $data['app'] ? $data['app'] : APP_NAME;
				$data['mod'] = $data['mod'] ? $data['mod'] : MODULE_NAME;
				$data['act'] = $data['act'] ? $data['act'] : ACTION_NAME;
		        break;
		        case 'picture':
		        	$data['app'] = $data['app'] ? $data['app'] : APP_NAME;
		        	$data['mod'] = $data['mod'] ? $data['mod'] : MODULE_NAME;
		        	$data['act'] = $data['act'] ? $data['act'] : ACTION_NAME;
		        	break;
			default:
				$data['app'] = APP_NAME;
				$data['mod'] = MODULE_NAME;
		        $data['act'] = ACTION_NAME;
		}
		$data['appconfig'] =  $data['appconfig'] ? $data['appconfig'] : $data['app'];
		//判断该数据是否已收藏
		$data['id'] =  model('Favorite')->check_favorite($data['app'],$data['mod'],$data['act'],$data['appconfig'],$data['fid']);
		if($data['count']) $data['num'] =  model('Favorite')->count_favorite($data['app'],$data['fid']);
		//widget模版路径
		$templateFile = ADDON_PATH . '/widgets/Favorite.html';		
		//渲染widget数据
		return $this->renderFile($templateFile, $data);
    }

}
?>