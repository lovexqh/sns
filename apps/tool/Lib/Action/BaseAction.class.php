<?php
class BaseAction extends Action {
	
	public function _initialize() {
		$this->sendok();
    }
	
	
	//检测是否转码
	public function sendok(){
		$saveaddress = D('ToolFile')->field('saveaddress')->where('status=0')->group('saveaddress')->findAll();
		foreach ($saveaddress as $val){
			$filecode = D('ToolFile')->field('filecode')->where("status=0 and saveaddress='".$val['saveaddress']."'")->findAll();
			foreach ($filecode as $k=>$v){
				$filecode[$k]=$v['filecode'];;
			}
			$filecode=implode("-",$filecode);
			
			// 1. 初始化
			$ch = curl_init();
			// 2. 设置选项，包括URL
			curl_setopt($ch, CURLOPT_URL, $val['saveaddress']."manage.aspx?action=isconvert&filecode=".$filecode);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //这行是设定curl是否跟随header发送的location
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "");
			// 3. 执行并获取HTML文档内容
			$output = curl_exec($ch);
			// 4. 释放curl句柄
			curl_close($ch);
			$obj=json_decode($output);
			foreach ($obj as $v){
				if($v->result==true){
					$filecode=$v->filecode;
					$data['status']=1;
					$data['pagecount']=$v->pagecount;
					$data['thumb']=$v->thumb;
					D('ToolFile')->where("filecode='".$filecode."'")->save($data);
				}elseif ($v->result==failure){
					$filecode=$v->filecode;
					$data['status']=2;
					D('ToolFile')->where("filecode='".$filecode."'")->save($data);
				}
			}
		}
	}
}
