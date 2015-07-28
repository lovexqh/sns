<?php
class ClubDeptModel extends Model {
	
	public function getDeptByClubid($clubid){
		$map['clubid'] = $clubid;
		$map['isdel'] = 0;
		$deptList = $this->where( $map )->order('id asc')->select();
		return $deptList;
	}
	
	/**
	 * @title  更新部门信息
	 * @description
	 * @param
	 * @return
	 * @author	xiawei 2013-12-5
	 */
	public function updateDept( $id, $map ){
		$rs = $this->where( 'id='.$id )->save( $map );
		return $rs;
	}
	
	/**
	 * @title  删除部门
	 * @description 
	 * @param  部门id
	 * @return
	 * @author	xiawei 2013-12-6
	 */
	public function delDept( $id ){
		$rs = $this->where( 'id='.$id )->save( array('isdel'=>1) );
		return $rs;
	}
	
	/**
	 * @title  添加部门
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-6
	 */
	public function addDept( $map ){
		$rs = $this->add( $map );
		return $rs;
	}
	
	public function getDeptById($id){
		$deptInfo = $this->where('id='.$id)->find();
		return $deptInfo;
	}
	
	/**
	 * @title  验证部门名是否已存在
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-2-19
	 */
	public function chkDeptName($clubid, $deptname, $deptid){
		if($deptid){
			$map['id'] = array('neq', $deptid);
		}
		$map['clubid'] = $clubid;
		$map['deptname'] = $deptname;
		$map['isdel'] = 0;
		$dept = $this->where($map)->find();
		if($dept){
			return true;
		}else{
			return false;
		}
	}

}