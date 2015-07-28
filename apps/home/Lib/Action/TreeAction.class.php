<?php
class TreeAction extends Action {

    function _initialize() {
       
    }
	
    /**
	 +----------------------------------------------------------
	 * 树状节点
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-5-10
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-10 上午11:07:09
	 +----------------------------------------------------------
	 */
	public function getKnowledgeTree() {
		$type=$_REQUEST['type'];
		$section=$_REQUEST['section'];
		$option['Grade']=$_REQUEST['Grade'];
		$option['Subject']=$_REQUEST['Subject'];
		$option['Publisher']=$_REQUEST['Publisher'];
		$option['Volume']=$_REQUEST['Volume'];
		$option['Cell']=$_REQUEST['Cell'];
		$data=model('Knowledge')->getTreeNode($type,$section,$option);
		echo json_encode($data);
	}
}
?>