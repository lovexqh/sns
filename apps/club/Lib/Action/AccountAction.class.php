<?php
class AccountAction extends Action {
	const INDEX_TYPE_WEIBO = 0;
	const INDEX_TYPE_GROUP = 1;
	const INDEX_TYPE_ALL = 2;
	function _initialize() {
		
	}
	
	function init() {
		echo './Public/miniblog.js';
	}
	
	//财务
	public function index(){
		$clubid = $_GET['id'];
		if(!$clubid){
			$this->error('社团错误!');
		}
		if(!isClubMember($this->mid, $clubid)){
			$this->error('您还不是该社团成员!');
		}
		
		$starttime = strtotime( $_POST['starttime'] );
		$endtime = strtotime( $_POST['endtime'] );
		if( $starttime && $endtime ){
			$map['addtime'] = array('between', array($starttime, $endtime));
		}
		
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
		
		//查询余额,从最后一次记录获取
		$balance = D( 'Club' )->getClubBalanceById($clubid);
		
		//获取财务名目列表
		$map['clubid'] = $clubid;
		$accountList = D( 'ClubAccount' )->getAccountList( $map );
		foreach($accountList['data'] as &$value){
			if($value['type'] == 1){
				$value['typename'] = '收入';
			}else if($value['type'] == 2){
				$value['typename'] = '支出';
			}
		}
		//dump($accountList);exit;
		$this->assign( $headData );
		$this->assign('balance', $balance['balance']);
		$this->assign('accountList', $accountList);
		$this->display();
	}
	
	//发布财务名目
	public function doAddAccount(){
		$clubid = $_POST['clubid'];
		$map['clubid'] = $clubid;
		$map['title'] = $_POST['title'];
		$map['addtime'] = strtotime( $_POST['addtime'] );
		$map['type'] = $_POST['type'];
		$map['adduid'] = $this->mid;
		$map['updateuid'] = $this->mid;
		
		$addRs = D( 'ClubAccount' )->addAccount( $map );
		if($addRs){
			$data['num'] = 1;
			$data['accountid'] = $addRs;
			$data['clubid'] = $clubid;
		}else{
			$data['num'] = 0;
		}
		echo json_encode($data);
	}
	
	//删除名目
	public function delAccount(){
		$id = $_POST['id'];
		$uid = $this->mid;
		$delRs = D( 'ClubAccount' )->delAccount($id, $uid);
		if($delRs){
			//删除名目下款项的信息
			$delItemRs = D( 'ClubAccountItem' )->delItemByAccountid($id, $this->mid);
			//更新余额
			$accountInfo = D( 'ClubAccount' )->getAccountById( $id );
			$updateBalance = D( 'Club' )->updateDelBalance($accountInfo['clubid'], $accountInfo['totalmoney'], $accountInfo['type']);
			echo 1;
		}else{
			echo 0;
		}
	}
	
	//财务名目明细
	public function accountDetail(){
		$clubid = $_GET['clubid'];
		//页面头部信息
		$headData = getClubInfo($clubid, $this->mid);
		
		//获取该名目的信息
		$id = $_GET['id'];
		$accountInfo = D( 'ClubAccount' )->getAccountById( $id );
		
		//获取名目明细信息
		$accountItem = D( 'ClubAccountItem' )->getAccountItem( $id );
		
		$this->assign( $headData );
		$this->assign('accountInfo', $accountInfo);
		$this->assign('accountItem', $accountItem);
		$this->display();
	}
	
	//删除财务名目中的单条款项
	public function delAccountItem(){
		$itemid = $_POST['itemid'];
		$accountid = $_POST['accountid'];
		
		$itemInfo = D( 'ClubAccountItem' )->getAccountItemById( $itemid );
		$accountInfo = D( 'ClubAccount' )->getAccountById( $accountid );
		$clubid = $accountInfo['clubid'];
		$money = $itemInfo['money'];
		$type = $accountInfo['type'];
		//删除该条款项
		$delItemRs = D( 'ClubAccountItem' )->delAccountItem($itemid, $this->mid);
		
		if($delItemRs){
			//更新名目的总计金额
			$updateMoneyRs = D( 'ClubAccount' )->updateDelMoney($accountid, $money, $this->mid);
			//更新余额
			$updateBalance = D( 'Club' )->updateDelBalance($clubid, $money, $type);
			echo 1;
		}else{
			echo 0;
		}
		
	}
	
	//添加财务名目中的款项
	public function doAddAccountItem(){
		$clubid = $_POST['clubid'];
		$accountid = $_POST['accountid'];
		$map['clubid'] = $clubid;
		$map['accountid'] = $accountid;
		$map['title'] = $_POST['title'];
		$map['money'] = $_POST['money'];
		$map['remark'] = $_POST['remark'];
		$map['useperson'] = $_POST['useperson'];
		$map['addtime'] = time();
		$map['adduid'] = $this->mid;
		$addRs = D( 'ClubAccountItem' )->addAccountItem($map);
		if($addRs){
			//更新收入或支出总金额
			$updateAccRs = D( 'ClubAccount' )->updateAddMoney($accountid, $map['money'], $this->mid);
			//更新余额
			$accountInfo = D( 'ClubAccount' )->getAccountById( $accountid );
			$type = $accountInfo['type'];
			$updateBalance = D( 'Club' )->updateAddBalance($clubid, $map['money'], $type);
			echo 1;
		}else{
			echo 0;
		}
		
	}
	
	
}
?>