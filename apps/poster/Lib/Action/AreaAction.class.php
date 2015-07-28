<?php
/**
 * NetworkAction
 * 地区网络
 * @uses Action
 * @package
 * @version $id$
 * @copyright 2009-2011 SamPeng
 * @author SamPeng <sampeng87@gmail.com>
 * @license PHP Version 5.2 {@link www.sampeng.cn}
 */
class AreaAction extends Action {
	//地区网络
    public function area() {
        //已选地区
        $selectedArea = explode(',',$_GET['selected']);
        if(!empty($selectedArea[0])) {
            $this->assign('selectedarea',$_GET['selected']);
        }
        $pNetwork = D('Area');
        $list = $pNetwork->getNetworkList(0);
        $this->assign('list',json_encode($list));
        $this->display();
    }
    
//     public function ajaxArea(){
//     	$map['pid'] = isset($_GET['pid']) ? $_GET['pid'] : '';
//     	if(empty($map['pid'])){
//     		$map['pid'] = 0;
//     	}
//     	$result = M('area')->where($map)->select();
//     	//echo M('area')->getLastSql();
//     	print_r(json_encode($result));
//     }
    public function ajaxArea(){
    	(string)$pid = isset($_GET['pid']) ? $_GET['pid'] : -1 ;
    	$pid = (string)$pid ;
    	if ($pid!=null) {
    		$map['pid'] = $pid;
    	}else{
    		$map['pid'] = '-1';
    	}
    	$result = M('area')->where($map)->select();
    	for ($i = count($result)-1; $i >= 0; $i--) {
    		$result[$i+1]['area_id'] = $result[$i]['area_id'];
    		$result[$i+1]['title'] = $result[$i]['title'];
    	}
    	$result[0]['area_id']='';
    	$result[0]['title']='请选择';
    	print_r(json_encode($result));
    }
}