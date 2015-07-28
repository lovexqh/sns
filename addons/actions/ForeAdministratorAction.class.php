<?php
class ForeAdministratorAction extends Action {
	public $config;
	public $actions;
	
	public function _initialize()
	{
		// $this->success(); 和 $this->error();
		
		// 检查用户是否登录管理后台, 有效期为$_SESSION的有效期
		if (!service('Passport')->isLogged())
			redirect( U('home/Public/login') );
		
		$actions = $_POST ['actions'];
		
		$action = strtolower(ACTION_NAME);
		$action = substr($action, -6) == '_admin' ? substr($action, 0, -6) : $action;
		$configs = D('ForeAdmin',APP_NAME)->getConfig();
		$this->config = $configs[$action];
		
		$this->assign ( "config", $this->config );
		$this->assign ( "action_name", ACTION_NAME );
		
		$config = $this->config;
		$id = $_POST ['id'];
		switch ($actions) {
			case 'pass':
				model("favorite")->up_favorite($config['table'], $id, 1);
				//D('ForeAdmin',APP_NAME)->sendMessage($id);
				break;
			case 'unpass':
				model("favorite")->up_favorite($config['table'], $id, 0);
				break;
			case 'del':
				model("favorite")->del_favorite_table($config['table'], $id);
				break;
		}
	}
	
	protected function _getSearchMap($fields)
	{
		// 为使搜索条件在分页时也有效，将搜索条件记录到SESSION中
		if (!empty($_POST)) {
			$_SESSION['admin_search_attach'] = serialize($_POST);
		} else if (isset($_GET[C('VAR_PAGE')])) {
			$_POST = unserialize($_SESSION['admin_search_attach']);
		} else {
			unset($_SESSION['admin_search_attach']);
		}
		
		// 组装查询条件
		$map = array();
		foreach ($fields as $k => $v) {
			foreach ($v as $field) {
				if (isset($_POST[$field]) && $_POST[$field] != '') {
					if($k == 'in')
						$map[$field] = array($k, explode(',', $_POST[$field]));
					else
						$map[$field] = array($k, $_POST[$field]);					
				}
			}
		}
		
		return $map;
	}

	public function applist($tpl = '', $where = array()) {
		$config = $this->config;

		$popedom = service('ForeAdmin')->getPopedom();
		if(strpos($popedom,'C')===false) {
			$schoolid = $_SESSION['ucInfo']['schoolid'];
			$where['schoolid'] = $schoolid;
		}

		$admin = $_GET ['admin'];
		if($admin == 'audit')
			$where[$config['statusFieldName']] = $config['noaudit'];
		else if($admin == 'audited')
			$where[$config['statusFieldName']] = array('NEQ',$config['noaudit']);

		$list = M ( $config['table'] )->where ( $where )->order($config['idFieldName']." desc")->findPage (10);

		$list = service('ForeAdmin')->showAdminBtns($list);
		
		$this->assign ( "admin", $admin );
		$this->assign ( "list", $list );
		$this->display ( $tpl );
	}
}