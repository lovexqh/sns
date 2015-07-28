<?php
/**
 +------------------------------------------------------------------------------
 * 空中课堂的管理控制器
 +------------------------------------------------------------------------------
 * @category	空中课堂
 * @package	Lib/ForeAdminAction
 * @author		杨志明
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午11:24:19
 +------------------------------------------------------------------------------
 */
class ForeAdminAction extends ForeAdministratorAction {
	public $config;
	
	public function _initialize() {
		parent::_initialize ();
		
		$action = strtolower(ACTION_NAME);
		$action = substr($action, -6) == '_admin' ? substr($action, 0, -6) : $action;
		$config = D('ForeAdmin',APP_NAME)->getConfig();
		$this->config = $config[$action];
		
		$this->assign ( "config", $this->config );
		$this->assign ( "action_name", ACTION_NAME );
	}
	
	/**
	 * +----------------------------------------------------------
	 * 应用内容列表
	 * +----------------------------------------------------------
	 * 
	 * @author 杨志明 2013-6-4
	 *         +----------------------------------------------------------
	 */
	public function applist() {
		parent::applist();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 管理应用内容，修改、删除、关闭、审核
	 * +----------------------------------------------------------
	 * 
	 * @author 杨志明 2013-6-4
	 *         +----------------------------------------------------------
	 */
	public function applist_admin() {
		$config = $this->config;
		
		$id = $_POST ['id'];
		$actions = $_POST ['actions'];
		
		if(!service('ForeAdmin')->checkPopedom($actions)) {
			echo -9; //无权限
			exit();
		}
		
		$M = M ( $config['table'] );
		$where[$config['idFieldName']] = array('in',explode(',',$id));
		
		$data [$config['statusFieldName']] = $config[$actions];
		
		switch ($actions) {
			case 'pass':
				$M->where ( $where )->save( $data );
				echo 1; // 通过成功1
				break;
			case 'unpass':
				$msg = $_POST ['msg'];
				
				$M->where ( $where )->save( $data );
				
				if($msg){
					$info = $M->where ( $where )->find ();
					$arr['to'] = $info['uid'];
					$arr['title'] = '您发布的活动被驳回';
					$arr['content'] = '您发布的活动“'.U('event/Index/index',array("id"=>$info['id'])).'”,理由：'.$msg ;
					$res = model('Message')->postMessage($arr, $this->mid);
				}
				
				echo 2; // 驳回成功2
				break;
			case 'del':
				$M->where ( $where )->delete ();
				echo 3; // 删除成功3
				break;
			case 'close':
				$info = $M->where ( $where )->find ();

				if ($info ['etime'] > time ()) {
					echo -1; // 关闭课堂失败，课堂已经关闭3
					exit ();
				}

				$data ['etime'] = time ();
				$M->where ( $where )->save ( $data );
				echo 4; // 关闭成功4
				break;
			default:
				echo 0; // 操作失败
		}
	}


}
?>