<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-7-15
 * Time: 下午1:59
 * To change this template use File | Settings | File Templates.
 */
class VideoCenterApi extends Api{

    function videoList(){
//        $_REQUEST['category'] =1;
        $conditions = '';
        $who = (int)$_REQUEST['who'];
        $author = (int)$_REQUEST['author_id'];
        $uid = $this->mid;
        $cid = (int)$_REQUEST['cid'];
        $limit = getLimit(false,10);
        $status = '1=1';
        if($who ==1 ){
            $conditions = 'u.uid='.$uid.' and ';
            $url = siteUrl(array('who'=>1));
            //查看所有人
        }elseif($who == 3){
            //基础分类
            if($_REQUEST['category']==1){
                //组合 sql 查询条件
                $_REQUEST['section'] && $conditions = 'section='.(int)$_REQUEST['section'].' and ';
                $_REQUEST['Grade'] && $conditions = 'grade='.$_REQUEST['Grade'].' and ';
                $_REQUEST['Subject'] && $conditions .= 'subject='.$_REQUEST['Subject'].' and ';
                $_REQUEST['Publisher'] && $conditions .= 'publisher='.$_REQUEST['Publisher'].' and ';
                $_REQUEST['Volume'] && $conditions.= 'volume='.$_REQUEST['Volume'].' and ';
                $_REQUEST['Cell'] && $conditions.= 'cell='.$_REQUEST['Cell'].' and ';
                $_REQUEST['Course'] && $conditions.= 'courseid='.$_REQUEST['Course'].' and ';

                $conditions .= $status.' and (v.privacy=0 or v.privacy=2) and type=1 and ';

                $arr = array(
                    'who'       =>  3,
                    'category'  =>  1,
                    'section'    =>  $_REQUEST['section'],
                    'Grade'     =>  $_REQUEST['Grade'],
                    'Subject'   =>  $_REQUEST['Subject'],
                    'Publisher' =>  $_REQUEST['Publisher'],
                    'Volume'    =>  $_REQUEST['Volume'],
                    'Cell'      =>  $_REQUEST['Cell'],
                    'Course'    =>  $_REQUEST['Course'],
                    'resource_type' =>  $_REQUEST['resource_type'],
                );

                $url = siteUrl($arr);
                //其它分类
            }elseif($_REQUEST['category']==2){
                $videos = procExecute("call vcBlogCategory($cid,'{$limit}','v.cTime','2');");
                $url = siteUrl(array('who'=>3,'category'=>2,'cid'=>$cid));

                //专题分类
            }elseif($_REQUEST['category']==3){
                $conditions .= $status.' and v.privacy=1 and (type=3 or attribute=\''.t($_REQUEST['dataCode']).'\') and ';
                $url = siteUrl(array('who'=>3,'category'=>3,'cid'=>$cid,'attribute'=>t($_REQUEST['dataCode'])));

                //微视频
            }elseif($_REQUEST['category'] == 4){
                $conditions .= $status.' and v.privacy=8 and ';
            }
            //查看某个用户
        }elseif($who == 2){
            $conditions = "u.uid=$author and (v.privacy=0 or v.privacy=2) and $status and ";
            $url = siteUrl(array('who'=>2,'cid'=>$cid));
        }

        if($who == 2 || $who ==1 || ($who == 3 && $_REQUEST['category']==1)){

            $limit = getLimit();
            $sql = "select v.id,v.userId author_id,u.uname author_name,v.name title,v.thumb,v.commentCount,v.readCount,v.serverpath,v.savepath,v.outfilename,v.cTime,v.fcode
                    from ts_video v
                    left join ts_user u on u.uid=v.userId
                    where $conditions isDel=0
                    order by v.cTime desc
                    limit $limit
                    ";
            $videos = M('')->query($sql);
        }

        /**
         * 专题分类
         */
        if($who==3 && $_REQUEST['category'] == 4){
            $limit = getLimit();
            $sql = "select v.id,v.userId author_id,u.uname author_name,v.name title,v.thumb,v.commentCount,v.readCount,v.serverpath,v.savepath,v.outfilename,v.cTime,v.fcode
                    from ts_video v
                    left join ts_user u on u.uid=v.userId
                    where $conditions isDel=0
                    order by v.cTime desc
                    limit $limit
                    ";
            $videos = M('')->query($sql);
            return $videos;
        }
        /**
         *  关键字搜索
         */
        if($who==5 && ($keyword=t($_REQUEST['keyword'],true,ENT_QUOTES))){
            $limit = getLimit();
            $url = siteUrl(array('who'=>5,'keyword'=>$keyword));
            $sql = "select v.id,v.userId author_id,u.uname author_name,v.name title,v.thumb,v.commentCount,v.readCount,v.serverpath,v.savepath,v.outfilename,v.cTime,v.fcode,v.info
                    from ts_video v
                    left join ts_user u on u.uid=v.userId
                    where ((u.uname like '%$keyword%') or (v.name like '%$keyword%') or (v.info like '%$keyword%')) and isDel=0
                    order by v.cTime desc
                    limit $limit
                    ";
            $videos = M('')->query($sql);
        }

        //我收藏的视频
        if($who == 4){
            $limit = getLimit();
            $url = siteUrl(array('who'=>4));
            $conditions = 'f.favuid='.$this->mid.' and ';
            $sql = "select v.id,v.userId author_id,u.uname author_name,v.name title,v.thumb,v.commentCount,v.readCount,v.serverpath,v.savepath,v.outfilename,v.cTime,v.status,v.fcode
                    from ts_video v
                    left join ts_user u on u.uid=v.userId
                    inner join ts_favorite f on v.id=f.fid and f.appname='video' and f.status=1
                    where $conditions isDel=0
                    order by v.cTime desc
                    limit $limit
                    ";
            $videos = M('')->query($sql);
        }

        //关注的人的视频
        if($who == 6){
            $now_following_sql = D('Follow', 'weibo')->getNowFollowingSql($uid);
            $r = M('')->query($now_following_sql);
            $uids = '';
            foreach ($r as $v) {
                $uids .= $v['fid'].',';
            }
            $uids = substr($uids,0,-1);

            //没有关注任何人时,不进行查询
            $followCount = D('Follow','weibo')->getUserFollowCount($uid);
            if($followCount){
                $url = siteUrl(array('who'=>5));
                $limit = getLimit();
                $sql = "select v.id,v.userId author_id,u.uname author_name,v.name title,v.thumb,v.commentCount,v.readCount,v.serverpath,v.savepath,v.outfilename,v.cTime,v.fcode.v.info
                    from ts_video v
                    left join ts_user u on u.uid=v.userId
                    where $conditions isDel=0 and u.uid in ($uids)
                    order by v.cTime desc
                    limit $limit
                    ";
                $videos = M('')->query($sql);
            }
        }

        foreach ($videos as $k=>$v) {
            $videos[$k]['video_path'] = $v['serverpath'].$v['savepath'].'/'.$v['outfilename'];
            $path = SITE_URL.'/thumb.php?w=70&h=70&t=f&url='.$v['serverpath'].$v['savepath'].'/img/'.$v['fcode'].'_1.jpg';
            $videos[$k]['author_img'] = $path;
            $videos[$k]['cTime'] = putTime($v['cTime']);
            unset($videos[$k]['serverpath']);
            unset($videos[$k]['savepath']);
            unset($videos[$k]['fcode']);
            unset($videos[$k]['outfilename']);
        }
        if($_POST['pc'] == 1){
            return $videos;
        }
        $readCountUrl = siteUrl(array(),'readCount');
        $this->render(array('data'=>$videos,'url'=>$url,'readCountUrl'=>$readCountUrl),true,'common','common/list-video',array('common-list'));
        exit;
    }

    public function readCount(){
        if($id = (int)$_REQUEST['id']){
            M('video')->setInc('readCount','id='.$id);
            return 1;
        }else{
            return 0;
        }
    }


    //视频上传
    public function saveVideo(){
        //视频信息
        $video = array();
        $video['userId'] = (int)$_REQUEST['uid']?(int)$_REQUEST['uid']:exit('1');

        $video['name'] = $_REQUEST['name']?t($_REQUEST['name'],true,ENT_QUOTES):exit('2');

        $video['cTime'] = time();

        $video['mTime'] = time();

        $video['info'] = $_REQUEST['info']?t($_REQUEST['info'],false,ENT_QUOTES):exit('3');

        $video['fcode'] = checkType($_REQUEST['fcode'],'string')?$_REQUEST['fcode']:exit('4');

        $path = M('video_server')->where('isrun=1')->field('serverurl')->find();

        $video['serverpath'] = $path['serverurl'];

        $video['filename'] = $_REQUEST['oldname']?t($_REQUEST['oldname'],true,ENT_QUOTES):exit('5');

        $video['outfilename'] = checkType($_REQUEST['outfilename'],'string',array('.'))?$_REQUEST['outfilename']:exit('6');

        $video['savepath'] = checkType($_REQUEST['filedir'],'string',array('/','-'))?$_REQUEST['filedir']:exit('7');

//        $video['type'] = 8;
        $video['privacy'] = 8;
        $video['status'] = 0;
        $videoid=M('Video')->add($video);
        //计算积分
        X('Credit')->setUserCredit($this->mid, 'add_video');
        if($videoid){
            return $videoid;
        }else{
            return 0;
        }

    }

    //获取视频上传服务器地址
    public function uploadServer(){
        $path = M('video_server')->where('isrun=1')->field('serverurl')->find();
        $path['serverurl'] = $path['serverurl'].'upload.aspx';
        return $path;
    }

    /**
     *+---------------------------------------------------
     *checkEscape——检查转码
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    public function checkEscape(){
        $_POST['from'] = 'api';
        D('Category','video');
        A('Base','video');
    }

}