<?php
class ClubDocumentModel extends Model {
	
	//获取文档列表
	public function getFileList($clubid){
		$map['clubid'] = $clubid;
		$map['isdel'] = 0;
		$docList = $this->where( $map )->order('ctime desc')->findPage(20);
		return $docList;
	}
	
	//获取已使用
	public function getUsedSpace($clubid){
		return $this->where('clubid=' . $clubid . ' AND isdel=0')->sum('filesize');
	}
	
	//获取文档数
	public function getFileCount($clubid){
		$map['clubid'] = $clubid;
		$map['isdel'] = 0;
		$fileCount = $this->where( $map )->count();
		return $fileCount;
	}
	
	/**
	 * @title  下载文档次数加1
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-25
	 */
	public function addDowncount($id){
		$map['downcount'] = array('exp', 'downcount+1');
		$this->where('id='.$id)->save($map);
	}
	
	/**
	 * @title  删除文档
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-25
	 */
	public function delDocument($idstr){
		$rs = $this->where("id IN (".$idstr.")")->save( array('isdel'=>1) );
		return $rs;
	}
	
	//获取文件
	/**
	 * getDocumentList
	 *
	 */
	public function getDocumentList($html=1,$map = null,$fields=null,$order = null,$limit = null,$isDel=0) {
		//处理where条件
		if(!$isDel)$map[] = 'isdel=0';
		else $map[] = 'isdel=1';
	
		$map = implode(' AND ',$map);
		//连贯查询.获得数据集
		$result         = $this->where( $map )->field( $fields )->order( $order )->findPage( $limit);
// 		$map .= ' AND a.id IS NOT NULL';
// 		$result = M()->Table('`'.C('DB_PREFIX').'group_attachment` AS ga LEFT JOIN `'.C('DB_PREFIX').'attach` AS a ON a.id = ga.attachId')
// 		->field('ga.*')
// 		->where($map)
// 		->order($order)
// 		->findPage($limit);
	
		if($html) return $result;
		return $result['data'];
	}
	
	//回收站 文件，包括附件
	function remove($id) {
		$id = is_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收	
		$result = D('ClubDocument')->where('id IN'.$id)->delete();
		return $result;
	}
	
	public function getDocById($id){
		$docInfo = $this->where('id='.$id.' AND isdel=0')->find();
		return $docInfo;
	}
	
	public function getDocListForMobile($cid,$start,$num){
		$map['clubid'] = $cid;
		$map['isdel'] = 0;
		$docList = $this->where($map)->order('ctime desc')->field('id,clubid,uid,title,filesize,filetype,savepath,savename,downcount,ctime')->limit($start.','.$num)->select();
		return $docList;
	}

}