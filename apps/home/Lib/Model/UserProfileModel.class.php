<?php
/**
 +------------------------------------------------------------------------------
 * 用户设置相关Model
 +------------------------------------------------------------------------------
 * @category	home
 * @package		Lib/Model
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 14:52:59
 +------------------------------------------------------------------------------
 */
class UserProfileModel extends UserModel {
    var $tableName = 'user_profile';

    /* 见 home/UserModel/getUserList
    function getUserList(){
        return $this->table(C('DB_PREFIX').'user')->findall();
    }
    */

    /**
     +----------------------------------------------------------
     * 统一提取用户资料
     +----------------------------------------------------------
     * @param	bool	$space	是否为个人空间调用
     * @return	array	用户信息集合
     * @author	小波
     +----------------------------------------------------------
     * 创建时间：2013-3-4 14:53:47
     +----------------------------------------------------------
     */
    function getUserInfo($space = false){
        $userInfoList                      = $this->where('uid='.$this->uid)->field('id,uid,module,data,type')->findall();
        $userInfo                          = $this->dataProcess( $userInfoList ,$space);
        $userInfo['detail']		           = $this->table(C('DB_PREFIX').'user')->where("uid={$this->uid}")->find();
        $userInfo['base']['completeness']  = 100;
        return $userInfo;
    }

    /**
     +----------------------------------------------------------
     * 数据处理
     +----------------------------------------------------------
     * @param	array	$userInfoList	用户信息列表
     * @param	bool	$space			是否为个人空间调用
     * @return	array	返回用户信息集合
     * @author	小波
     +----------------------------------------------------------
     * 创建时间：2013-3-4 14:57:40
     +----------------------------------------------------------
     */
    private function dataProcess( $userInfoList,$space ){
        $fieldList = $this->data_field(false,$space);
        foreach ($userInfoList as $value){
            if( $value['type'] == 'info' ){
                $database[ $value['module'] ] = unserialize( $value['data'] );
            }else{
                $data[ 'profile' ]['list'][] = array_merge( array('module'=>$value['module'],'id'=>$value['id']) , unserialize($value['data']) );
            }
        }
        $data['profile']['completeness'] = round( count( array_unique( getSubByKey( $data[ 'profile' ]['list'] ,'module') ) ) / 2 , 2) *100;
        foreach ($fieldList as $key=>$value){
            foreach ( $value as $k=>$v){
                $t = $database[$key][$k];
                if( $t ) $complete++;
                $data[$key]['list'][]  = array('field' => $k,'name'  => $v,'value' => $t );

            }
            $data[$key]['completeness'] = round( $complete/count($value) , 2 ) * 100 ;
            unset($complete);
        }

        unset($userInfoList);
        unset($fieldList);
        unset($database);
        return $data;
    }

    /**
     +----------------------------------------------------------
     * 统一存储用户资料
     +----------------------------------------------------------
     * @param	string	$module		模型名称
     * @param	array	$savedata	要保存的用户信息
     * @param	$type	
     * @return	int		添加后返回的数据
     * @author	小波
     +----------------------------------------------------------
     * 创建时间：2013-3-4 14:59:56
     +----------------------------------------------------------
     */
    function doSave( $module , $savedata , $type='info' , $multi=false  ){
        if(!$module) return false;
        $data['uid']    = $this->uid;
        $data['module'] = $module;
        $data['type']   = $type;
        foreach ($savedata as $k=>$v){
        	$savedata[$k]=keyWordFilter($v);
        }
        if( $this->where($data)->count()!=0 && $multi==false){
            $this->setField( 'data' , serialize( $savedata) ,$data);
        }else{
            $data['data'] = serialize( $savedata );
            return $this->add( $data );
        }
    }

    /**
     +----------------------------------------------------------
     * 获取信息
     +----------------------------------------------------------
     * @param	int	$uid	用户ID
     * @return	array	用户的信息列表
     * @author	小波
     +----------------------------------------------------------
     * 创建时间：2013-3-4 13:00:33
     +----------------------------------------------------------
     */
    function getProfiles($uid){
        $list = $this->where( 'uid='.$uid )->order('id ASC')->findall();
        foreach ($list as $value){
            $unserData = unserialize( $value['data'] );
            $data[] = array_merge( array('module'=>$value['module'],'id'=>$value['id']) , $unserData );
        }
        return $data;
    }

    function delProfile($intId,$uid){
    	$result = $this->where("id='$intId' AND uid='$uid'")->delete();
    	//echo $this->getLastSql();
        return $result;
    }


	/**
	 +----------------------------------------------------------
	 * 更新个人情况
	 +----------------------------------------------------------
	 * @return	array 更新后的状态及调试信息
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 14:58:13
	 +----------------------------------------------------------
	 */
    function upintro(){
        $fieldList = $this->data_field( 'intro' );
        foreach ($fieldList as $key=>$value){
            $data[$key] = t( msubstr( $_POST['intro'][$key],0,70,'utf-8',false ) );
        }
        $this->dosave('intro',$data);
	   	$data['message'] = '更新完成';
		$data['boolen']  = 1;
		return $data;
    }

	/**
	 +----------------------------------------------------------
	 * 更新联系方式
	 +----------------------------------------------------------
	 * @return	array 更新后的状态及调试信息
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 14:59:50
	 +----------------------------------------------------------
	 */
    function upcontact(){
        $fieldList = $this->data_field( 'contact' );
        foreach ($fieldList as $key=>$value){
            $data[$key] = t( msubstr( $_POST['contact'][$key],0,70,'utf-8',false ) );
        }
        if( $data['mobile'] ){
        	if( $this->isValidMobile($data['mobile']) ){
        		$findFromDB = M('user')->where('mobile='.$data['mobile'].' and uid!='.$this->uid)->find();
        		if( !$findFromDB ){
        			M('user')->where('uid='.$this->uid)->save(array('mobile'=>$data['mobile']));
        			$this->dosave('contact',$data);
        			$data['message'] = L('update_done');
        			$data['boolen']  = 1;
        		}else{
        			$data['message'] = L('手机号已存在');
        		}
        	}else{
        		$data['message'] = L('手机号错误');
        	}
        }else{
        	M('user')->where('uid='.$this->uid)->save(array('mobile'=>$data['mobile']));
        	$this->dosave('contact',$data);
        	$data['message'] = L('update_done');
        	$data['boolen']  = 1;
        }
        
		return $data;
    }
    
    public function isValidMobile($mobile){
    	if(preg_match('/^1[358]{1}[0-9]{9}$/', $mobile)){
    		return true;
    	}else{
    		return false;
    	}
    }
}