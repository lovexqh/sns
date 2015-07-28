<?php
class SearchAction extends Action {
	/**
	* @Title: unique_arr
	* @Description: 二维数组去重复操作
	* @author RickerYu rickeryu@gridinfo.com.cn
	 */
	function unique_arr($array2D, $stkeep = false, $ndformat = true) {
		// 判断是否保留一级数组键 (一级数组键可以为非数字)
		if ($stkeep)
			$stArr = array_keys ( $array2D );
			// 判断是否保留二级数组键 (所有二级数组键必须相同)
		if ($ndformat)
			$ndArr = array_keys ( end ( $array2D ) );
			// 降维,也可以用implode,将一维数组转换为用逗号连接的字符串
		foreach ( $array2D as $v ) {
			$v = join ( ",", $v );
			$temp [] = $v;
		}
		// 去掉重复的字符串,也就是重复的一维数组
		$temp = array_unique ( $temp );
		// 再将拆开的数组重新组装
		foreach ( $temp as $k => $v ) {
			if ($stkeep)
				$k = $stArr [$k];
			if ($ndformat) {
				$tempArr = explode ( ",", $v );
				foreach ( $tempArr as $ndkey => $ndval )
					$output [$k] [$ndArr [$ndkey]] = $ndval;
			} else
				$output [$k] = explode ( ",", $v );
		}
		return $output;
	}
	/**
	 * @Title: search
	 * ription: 显示班级搜索页面
	 * 
	 * @param
	 *        	设定文件
	 * @return return_type
	 * @author RickerYu rickeryu@gridinfo.com.cn
	 */
	public function search() {
		$uid = $this->mid;
		$uc_id = M ( 'ucenter_user_link' )->where ( 'uid=' . $uid )->find ();
		$members = uc_get_user ( $uc_id ['uc_uid'], 1 );
		$data ['myclass'] = get_class_by_teacher_uid ( $uc_id ['uc_uid'] );
		if ($members ['identityType'] == '3') {
			$data ['myclass'] = get_class_by_student_uid ( $uc_id ['uc_uid'] );
		} else if ($members ['identityType'] == '4') {
			$data ['myclass'] = get_class_by_parent_uid ( $uc_id ['uc_uid'] );
		}
		$data ['myclass'] = $this->unique_arr($data ['myclass']);
		$this->assign ( $data );
		$this->display ();
	}
	
	/**
	 * @Title: search_by_xd
	 * ription: 根据学段Id找到学校信息
	 * 
	 * @param
	 *        	设定文件
	 * @return return_type
	 * @author RickerYu rickeryu@gridinfo.com.cn
	 */
	public function search_by_xd() {
		$param ['xd'] = $_POST ['xd'] . '';
		$return = service_call ( 'Usch009', $param );
		$out = '';
		foreach ( $return as $key => $val ) {
			$out .= '<li class="listy2"><a class="yzylisty" onclick="javascript:clickf(this,\'xx\');return false;" lang="' . $param ['xd'] . '_' . $val ['id'] . '"  href="javascript:void(0);" >' . $val ['xxmc'] . '</a></li>';
		}
		print_r ( $out );
	}
	
	/**
	 * @Title: search_by_xx
	 * ription: 根据学校ID找到级部信息
	 * 
	 * @param
	 *        	设定文件
	 * @return return_type
	 * @author RickerYu rickeryu@gridinfo.com.cn
	 */
	public function search_by_xx() {
		$param ['xd'] = $_POST ['xd'] . '';
		$param ['schoolid'] = $_POST ['xx'] . '';
		$return = service_call ( 'Usch012', $param );
		$out = '';
		foreach ( $return as $key => $val ) {
			$out .= '<li class="listy2"><a class="yzylisty" href="javascirpt:void(0);" onclick="javascript:clickf(this,\'nj\');return false;" lang="' . $val ['id'] . '" >' . $val ['njmc'] . '</a></li>';
		}
		print_r ( $out );
	}
	
	/**
	 * @Title: search_by_xx
	 * ription: 根据级部ID找到班级信息
	 * 
	 * @param
	 *        	设定文件
	 * @return return_type
	 * @author RickerYu rickeryu@gridinfo.com.cn
	 */
	public function search_by_nj() {
		$param ['jbid'] = $_POST ['nj'] . '';
		$return = service_call ( 'Usch013', $param );
		$out = '';
		foreach ( $return as $key => $val ) {
			$out .= '<li><a class="liasty" href="javascirpt:void(0)" onclick="top.OpenBrowser(\'' . U ( 'space/Class/index', array (
					'classid' => $val ['id'] 
			) ) . '\',\'' . $val ['bj'] . '\', \'width=1024,height=600,titlebutton=close|max|min\');return false;" >' . $val ['bj'] . '</a></li>';
		}
		print_r ( $out );
	}
	public function service_class_by_student($uid) {
		$param ['uid'] = $uid;
		$return = service_call ( 'Ustu013', $param );
		return $return;
	}
	public function service_class_by_parent($uid) {
		$param ['uid'] = $uid;
		$return = service_call ( 'Ustu014', $param );
		return $return;
	}
}
?>