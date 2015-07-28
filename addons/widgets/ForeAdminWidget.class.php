<?php
/**
 * 用户权限左侧列表Widget
 *
 * @author 杨志明
 */
class ForeAdminWidget extends Widget {

	/**
	 * 用户权限左侧列表Widget
	 *
	 * $data接受的参数:
	 * arrary(
	 * 	'app_name'(可选)	 => $app_name, // 应用名
	 *  'uid' => $uid, // 用户uid
	 * )
	 *
	 * @see Widget::render()
	 */
	public function render($data) {
		$mid = intval($_SESSION['mid']);
		
		$data['app_name'] = $data['app_name'] ? $data['app_name'] : APP_NAME;
		$data['title'] = $data['title'] ? $data['title'] : '';
		$data['uid'] = $data['uid'] ? $data['uid'] : $mid;
		
		if ($data['uid']) {
			$data['popedom'] = service('ForeAdmin')->getPopedom($data['uid'], $data['app_name']);
			if($data['type'] == 'leftlist') {
				$data['config'] = D('ForeAdmin',APP_NAME)->getConfig();
				$data['isAuditApp'] = service('ForeAdmin')->isAuditApp();
				
				$content = $this->renderFile(ADDON_PATH . '/widgets/ForeAdmin.html', $data);
			} else if($data['type'] == 'rightbtns') {
				if(strpos($data['popedom'],'I')!==false) $content = '<a href="javascript:;" onclick="tabs.content(\'创建'.$data['title'].'\',\''.$data['url'].'\');" class="easyui-linkbutton medium-primary">创建'.$data['title'].'</a>';
			}
		}

		return $content;
	}
}