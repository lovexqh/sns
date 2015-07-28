<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-6-24
 * Time: 上午9:09
 * To change this template use File | Settings | File Templates.
 */
class PhotoApi extends Api{
    function __construct(){
        parent::__construct();
        A('Base','photo');
        include_once SITE_PATH.'/apps/photo/Common/common.php';
        $_POST['from'] = 'api';
    }

    /**
     *+---------------------------------------------------
     *allPhotos——获取图片列表
     *+---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-6-24 下午2:51
     *+----------------------------------------------------
     */
    public function allPhotos(){
        $order  = NULL;
        switch( $_POST['order'] ) {
            case 'hot':    //热门排行
                $order = 'readCount DESC';
                break;
            case 'following':    //关注的人的图片
                $in_arr = M('weibo_follow')->field('fid')->where("uid={$this->mid} AND type=0")->findAll();
                $in_arr = $this->_getInArr($in_arr);
                $map['userId'] = array('in',$in_arr);
                break;
            default:      //默认最新
                $order = 'cTime DESC';
        }
        $map['privacy']	=	1; //所有人公开的图片
        if($_POST['limit'] == 'all'){
            $limit = M('photo_album')->where($map)->count();
        }else{
            $limit = $_POST['limit']?(int)$_POST['limit']:10;
        }
        $a = M('photo')->where($map)->order($order)->field("id,userId,name,FROM_UNIXTIME(cTime,'%Y-%m-%e %H:%k:%i') cTime,FROM_UNIXTIME(mTime,'%Y-%m-%e %H:%k:%i') mTime,IFNUll(info,' ') info,commentCount,readCount,savepath")->findPage($limit);
        unset($a['html']);
        return $a;
    }

    function _getInArr($in_arr) {
        $in_str = array();
        foreach($in_arr as $key=>$v) {
            $in_str[] = $v['fid'];
        }
        return $in_str;
    }

    /**
     *+---------------------------------------------------
     *allAlbums——获取所有公开相册列表
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-6-24 下午5:07
     *+----------------------------------------------------
     */
    function allAlbums(){
        $order = NULL;
        $map   = '';
        switch($_POST['order']) {
            case 'hot':    //最热排行
                $order = 'readCount DESC';
                $map  .=' photoCount>0 AND privacy=1 AND isDel=0 AND status=1 ';
                break;
            case 'following':    //关注的人的相册
                $in_arr = M('weibo_follow')->field('fid')->where("uid={$this->mid} AND type=0")->findAll();
                $in_arr = $this->_getInArr($in_arr);
                $map['userId'] = array('in',$in_arr);
                break;
            default:      //默认最新排行
                $order = 'mTime DESC';
                $map  .=' photoCount>0 AND privacy=1 AND isDel=0 AND status=1 ';
        }
        if($_POST['limit'] == 'all'){
            $limit = M('photo_album')->where($map)->count();
        }else{
            $limit = $_POST['limit']?(int)$_POST['limit']:10;
        }
        //获取相册数据
        if($uid = (int)$_REQUEST['uid']){
            $map = array();
            $map['userId']	=	(int)$_REQUEST['uid'];
            $map['isDel']	=	0;
            $map['status']	=	1;
        }
        $p = 'ts_photo_album';
        $u = 'ts_user';
        $data	=	M('photo_album')->join('ts_user on ts_photo_album.userId=ts_user.uid')->field("$p.id,userId,$u.uname,$p.name,IFNULL($p.info,'') info,FROM_UNIXTIME($p.cTime,'%Y-%m-%e %H:%k:%i') cTime,FROM_UNIXTIME($p.mTime,'%Y-%m-%e %H:%k:%i') mTime,IFNULL($p.coverImageId,'') coverImageId,IFNULL($p.coverImagePath,'') coverImagePath,photoCount,readCount")->order($order)->where($map)->findPage($limit);
        unset($data['html']);
        if(!$data['data']){
            $data['data']=array();
        }else{
            $arr = array();
            foreach($data['data'] as $v){
                if(!$v['coverImageId']){
                   $a = M('photo')->where('userId='.$v['userId'].' and albumId='.$v['id'])->field('id,savepath')->order('mTime DESC')->limit("1")->find();
                    $v['coverImageId'] = $a['id'];
                    $v['coverImagePath'] = $a['savepath'];
                }
                $arr[]=$v;
            }
            $data['data'] = $arr;
        }
        return $data;
    }

    /**
     *+---------------------------------------------------
     *albums——获取某个用户相册列表
     *+---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-6-24 下午5:02
     *+----------------------------------------------------
     */
    function albums(){
        echo $this->uid;
        $map['userId']	=	(int)$_REQUEST['uid'];
        $map['userId'] = 3;
        $map['isDel']	=	0;
        $limit = $_POST['limit']?(int)$_POST['limit']:10;
        $data	=	M('photo_album')->order("mTime DESC")->field("id,userId,name,IFNULL(info,'') info,FROM_UNIXTIME(cTime,'%Y-%m-%e %H:%k:%i') cTime,FROM_UNIXTIME(mTime,'%Y-%m-%e %H:%k:%i') mTime,IFNULL(coverImageId,'') coverImageId,IFNULL(coverImagePath,'') coverImagePath,photoCount,readCount")->where($map)->findPage($limit);
        //获取微广播相册
        $weibo  =  D('WeiboAttach','weibo')->getWeiboAlbum($map['userId'],true);
        $arr = array();
        $arr['albums'] = $data;
        $arr['weibo'] = $weibo;
        return $arr;
    }

    /**
     *+---------------------------------------------------
     *album——获取某个用户某个相册照片列表
     *+---------------------------------------------------
     * id   相册id
     * uid  用户id
     * @return int
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-6-24 下午5:06
     *+----------------------------------------------------
     */
    function album(){
        $id		=	intval($_POST['id']);
        $uid = intval($_POST['uid']);
//        $uid = 141;
//        $id = 56;
        //获取相册信息
        $albumDao = M('photo_album');
        $album	  =	$albumDao->where("id={$id}")->find();
        if(!$album){
            //该相册被删除
            return 0;
        }
        //隐私控制
        if($this->mid!=$album['userId']){
            $relationship	=	getFollowState($this->mid,$uid);
            if($album['privacy']==3){
                //相册只有主任可见
                return 3;
            }elseif($album['privacy']==2 && $relationship=='unfollow'){
                //相册只限关注人可看
                return 2;
            }elseif($album['privacy']==4){
                //需要输入密码
                return 4;
//                $cookie_password	=	cookie('album_password_'.$album['id']);
                //如果密码不正确，则需要输入密码
//                if($cookie_password != md5($album['privacy_data'].'_'.$album['id'].'_'.$album['userId'].'_'.$this->mid)){
//                }
            }
        }

        $order	=	'`order` DESC,id DESC';
        $map['albumId']	=	$id;
        $map['userId']	=	$uid;
        $map['isDel']	=	0;
        $limit = $_POST['limit']?(int)$_POST['limit']:10;
//        var_dump($map);
        $photos	= M('photo')->order($order)->field("id,userId,name,FROM_UNIXTIME(cTime,'%Y-%m-%e %H:%k:%i') cTime,FROM_UNIXTIME(mTime,'%Y-%m-%e %H:%k:%i') mTime,IFNUll(info,' ') info,commentCount,readCount,savepath")->where($map)->findPage($limit);

        //点击率加1
        $albumDao->execute('UPDATE '.C('DB_PREFIX').$albumDao->tableName." SET readCount=readCount+1 WHERE id={$id} AND userId={$uid} LIMIT 1");
        unset($photos['html']);
        return $photos;
    }

    /**
     *+---------------------------------------------------
     *deletePhoto——删除照片
     *+---------------------------------------------------
     * @param   pid 照片id
     * @return 1:成功 0:失败
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-6-27 下午3:17
     *+----------------------------------------------------
     */
    function deletePhoto(){
        $pid = (int)$_POST['pid'];
        if(!$pid)exit;
        if(D('Album','photo')->deletePhoto($pid,$this->mid))
            return 1;
        else
            return 0;
    }

    /**
     *+---------------------------------------------------
     *uploadPic——发表图片
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-6-28 下午12:37
     *+----------------------------------------------------
     */
    function uploadPic(){
        if(!$_REQUEST['albumId']){
            $album = $this->createAlbum();
            $_REQUEST['albumId'] = $album['albumId'];
        }
        A('Upload','photo')->upload_single_pic();
        exit;
    }

    /**
     *+---------------------------------------------------
     *createAlbum——创建相册
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-6-28 上午10:41
     *+----------------------------------------------------
     */
    function createAlbum(){
        $_POST['name'] = rand(1,100);
        if(!$_POST['name'])exit;
        //BaseAction
        return A('Manage','photo')->do_create_album();
    }
}
