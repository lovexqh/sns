<?php
class OauthAction extends Action {
	
	public function _initialize()
	{
		// 如果是应用的后台，检查用户是否具有节点权限
		if (APP_NAME != 'admin' && ! service('SystemPopedom')->hasPopedom($this->mid, null, false)) {
			$this->error('您无权限使用');
		}
	}

}