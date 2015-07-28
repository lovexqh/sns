<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-7-22
 * Time: 上午10:32
 * To change this template use File | Settings | File Templates.
 */
class ClassSpaceApi extends Api{

    function spaceIndex(){
        $cid = (int)$_REQUEST['cid']?(int)$_REQUEST['cid']:exit;
        //班级logo,口号，寄语，简介
        $info = M('class')->where('classid='.$cid)->field("IFNULL(thumb,'') logo,logan,message,info")->find();
        if($info['logo']){
            $info['logo'] = SITE_URL.$info['logo'];
        }else{
            $info['logo'] = SITE_URL.'apps/space/Tpl/desktop/Public/images/class_avatar.gif';
        }
        //最新访客
        $visitor = M('class_visit')->where('classid='.$cid)->join('ts_user on ts_user.uid=ts_class_visit.visituid')->field('ts_user.uname visitor')->order('vTime desc')->limit("0,1")->find();
        //当月过生日人数
        $count = count(get_webService('Ustu017',array('classid'=>$cid,'month'=>date('m'))));
        //最新发布的作业
        $exercise = M('exercise')->where('ts_exercise_class.classid='.$cid)->join('ts_exercise_class on ts_exercise_class.eid=ts_exercise.id')->field('coursename,stime')->order('ts_exercise.mtime desc')->limit(1)->find();

        $info = $info ? $info : array();
        $visitor = $visitor ? $visitor : array();
        $exercise = $exercise ? $exercise : array();
        $info = array_merge($info,$visitor);
        $info['birthdayCount'] = $count;
        $info['exercise'] = $exercise;
        return $info;
    }


    /**
    +---------------------------------------------------
     *myClass——获取与用户相关的班级
    +---------------------------------------------------
     * @author  徐程亮
    +---------------------------------------------------
     *创建时间：13-7-22 上午11:30
    +----------------------------------------------------
     */
    function myClass(){
        $role = (int)$_REQUEST['role'];
        $uc_id = (int)$_REQUEST['uc_id'];
        if(!$role || !$uc_id)exit;
        $data = array();
        switch ($role){

            case 2:
                $data['myClass'] = get_class_by_teacher_uid((string)$uc_id);
                break;

            case 3:
                $data['myClass'] = get_class_by_student_uid((string)$uc_id);
                break;

            case 4:
                $data['myClass'] = get_class_by_parent_uid((string)$uc_id);
                break;

        }
        $data['stage'] = array(
            array('name'=>'小学','id'=>'21'),
            array('name'=>'初中','id'=>'31'),
            array('name'=>'高中','id'=>'34')
        );
        return $data;
    }

    /**
    +---------------------------------------------------
     *getClass——获取班级列表
    +---------------------------------------------------
     * @return array
     * @author  徐程亮
    +---------------------------------------------------
     *创建时间：13-7-22 上午11:31
    +----------------------------------------------------
     */
    function getClass(){
        $type = (string)$_REQUEST['type'];
        $data = array();
        switch($type){

            case 'school':
                //通过学段获取学校列表
                $xd = (int)$_REQUEST['xd']?(string)$_REQUEST['xd']:exit;
                $data = get_webService('Usch009',array('xd'=>$xd));
                foreach($data as $v){
                    $arr[] = array('id'=>$v['id'],'name'=>$v['xxmc']);
                }
                $data = $arr;
                break;

            case 'jb':
                $xd = (int)$_REQUEST['xd']?(string)$_REQUEST['xd']:exit;
                $sid = (int)$_REQUEST['sid']?(string)$_REQUEST['sid']:exit;
                //根据学校id获取级部列表
                $data = get_webService('Usch012',array('xd'=>$xd,'schoolid'=>$sid));
                break;

            case 'class':
                $jb = (int)$_REQUEST['jb']?(string)$_REQUEST['jb']:exit;
                //根据级部id获取班级列表
                $data = get_webService('Usch013',array('jbid'=>$jb));
                foreach($data as $v){
                    $arr[] = array('classid'=>$v['id'],'name'=>$v['bj']);
                }
                $data = $arr;
                break;
        }
        return $data;
    }


    /**
     *+---------------------------------------------------
     *classMembers——班内成员
     *+---------------------------------------------------
     * @return array
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function classMembers(){
        $role = (int)$_REQUEST['role']?$_REQUEST['role']:exit;
        $cid = (int)$_REQUEST['classid']?$_REQUEST['classid']:exit;
//        $role = 3;
//        $cid = 19;
        $data = array();
        $arr = array();
        switch($role){
            //班内所有老师
            case 1:
                $data = get_webService("Utch018",array('classid'=>$cid));
                foreach($data as $v){
                    if($v['uid']){
                        $v['uid'] = getUid($v['uid']);
                        $v['uid'] = $v['uid']?$v['uid']:'';
                    }else{
                        $v['uid'] = '';
                    }
                    if(!$v['xkmc']){
                        $v['xkmc'] = '';
                    }
                    unset($v['identityid']);
                    unset($v['xbm']);
                    unset($v['csrq']);
                    unset($v['py']);
                    $arr[] = $v;
                }
                $data = $arr;
                break;
            //班内所有学生
            case 2:
                $data = get_webService("Ustu004",array('classid'=>$cid));
                foreach($data as $k=>$v){
                    if($v['uid']){
                        $data[$k]['uid'] = getUid($v['uid']);
                        $data[$k]['uid'] = $v['uid']?$v['uid']:'';
                    }else{
                        $data[$k]['uid'] = '';
                    }
                    unset($data[$k]['identityid']);
                }
                break;
            //班内所有班干部
            case 3:
                $stu = get_webService("Ustu004",array('classid'=>$cid));
                $data = M('class_leader')->join($this->db_prefix.'class_position as p on p.id='.$this->db_prefix.'class_leader.positionid')->where('classid='.$cid)->field('name xm,identityid,position')->findAll();
                foreach ($stu as $v) {
                    if($v['uid']){
                        foreach ($data as $kk=>$vv) {
                            if($vv['identityid'] == $v['identityid']){
                                $u = M('ucenter_user_link')->where('uc_uid='.$v['uid'])->field('uid')->find();
                                $data[$kk]['uid'] = $u['uid'];
                            }
                            unset($data[$kk]['identityid']);
                        }

                    }
                }

                break;
        }
        unset($stu);
        $data = getUserImg($data,'uid');
        return $data;
    }

    /**
     *+---------------------------------------------------
     *notice——班级公告
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-7-22 下午4:19
     *+----------------------------------------------------
     */
    function noticeList(){
        $cid = (int)$_REQUEST['classid']?$_REQUEST['classid']:exit;
        $limit = getLimit(false,10);
        $data=M('')
            ->table($this->db_prefix.'user u left join '.$this->db_prefix.'class_announce a on u.uid=a.uid')
            ->where('classid='.$cid)
            ->field("a.id,a.uid,a.title,a.content,a.atime ctime,u.uname")
            ->order('aTime desc')
            ->limit($limit)->findAll();
        if($_POST['pc']==1){
            if($data)
                return $data;
            else
                return 0;
        }
        foreach ($data as $k=>$v) {
            $data[$k]['ctime'] = putTime($v['ctime'],'m');
            $data[$k]['content'] = clearHtml($v['content']);
        }

        $url = siteUrl(array('classid'=>$cid));
        $this->render(array('data'=>$data,'url'=>$url),true,'common','common/list-class',array('class-list'));
    }

    /**
     *+---------------------------------------------------
     *courseList——课程表
     *+---------------------------------------------------
     * @return array
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-7-22 下午6:01
     *+----------------------------------------------------
     */
    function courseList(){
        $cid = (int)$_REQUEST['classid']?$_REQUEST['classid']:exit;
//        $course=D('Course','space')->_course($cid);			//课程表显示
//        var_dump($course);
        //TODO 按年份与学期
        $sql = "select
                c.*,d.DataName name
                from {$this->db_prefix}class_course c
                left join {$this->db_prefix}category_dictionary d on d.DataCode=c.subjectid and DataType='Subject'
                where c.classid=$cid
                order by c.quantum,c.weekday,c.festival
                ";
        $data = M('')->query($sql);
        $arr = array();
        $a = array(1=>'morn','am','pm','night');
        foreach ($data as $v) {
            $arr[$a[$v['quantum']]]['week'.$v['weekday']][$v['festival']]=array($v['subjectid'],$v['name']);
        }
        unset($data);
        if($arr){
            return $arr;
        }else{
            return '{}';
        }
    }

    function courseView(){
        $cid = (int)$_REQUEST['classid']?$_REQUEST['classid']:exit;
//        $course=D('Course','space')->_course($cid);			//课程表显示
//        var_dump($course);
        //TODO 按年份与学期
        $sql = "select
                c.*,d.DataName name
                from {$this->db_prefix}class_course c
                left join {$this->db_prefix}category_dictionary d on d.DataCode=c.subjectid and DataType='Subject'
                where c.classid=$cid
                order by c.quantum,c.weekday,c.festival
                ";
        $data = M('')->query($sql);
        $arr = array();
        $a = array(1=>'morn','am','pm','night');
        foreach ($data as $v) {
            $arr[$a[$v['quantum']]]['week'.$v['weekday']][$v['festival']]=array($v['subjectid'],$v['name']);
        }
        $url = siteUrl(array('classid'=>$cid));
        $this->render(array('data'=>$arr,'url'=>$url),true,'v4','common/course',array('course'));
    }

    /**
     *+---------------------------------------------------
     *exerciseList——作业列表
     *+---------------------------------------------------
     * @return int
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-7-20 上午9:25
     *+----------------------------------------------------
     */

    function exerciseList(){
        $cid = (int)$_REQUEST['classid']?$_REQUEST['classid']:exit;
        $limit = getLimit(false,10);
        $sql = 'select e.title,e.content,e.coursename,e.stime,e.etime,u.uname
                from '.$this->db_prefix.'exercise e
                inner join '.$this->db_prefix.'exercise_class c on c.eid=e.id
                left join '.$this->db_prefix.'user u on u.uid=e.fromuid
                where c.classid='.$cid.' and e.status=1
                order by e.stime desc
                limit '.$limit;
        $data = M('')->query($sql);
        foreach ($data as $k=>$v) {
            $data[$k]['content'] = clearHtml($v['content']);
            $data[$k]['stime'] = date('n.j H:i',$v['stime']);
            $data[$k]['etime'] = date('n.j H:i',$v['etime']);
        }

        if($_POST['pc']==1){
            if($data)
                return $data;
            else
                return 0;
        }
        $url = siteUrl(array('classid'=>$cid));
        $this->render(array('data'=>$data,'url'=>$url,'type'=>'exercise'),true,'common','common/list-class',array('class-list'));
    }

    /**
     *+---------------------------------------------------
     *honorList——荣誉榜
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function honorList(){
        $cid = (int)$_REQUEST['classid']?$_REQUEST['classid']:exit;
//        $cid = 599;
        $limit = getLimit(false,10);
//        $data = M('class_honor')->where("classid=".$cid)->limit($limit)->field('title,content,hTime ctime')->findAll();
        $data=M('')
            ->table($this->db_prefix.'user u left join '.$this->db_prefix.'class_honor a on u.uid=a.uid')
            ->where('classid='.$cid)
            ->field("a.title,a.content,a.hTime ctime,u.uname")
            ->order('a.hTime desc')
            ->limit($limit)->findAll();
        if($_POST['pc']==1){
            if($data)
                return $data;
            else
                return 0;
        }
        foreach ($data as $k=>$v) {
            $data[$k]['ctime'] = putTime($v['ctime'],'m');
            $data[$k]['content'] = clearHtml($v['content']);
        }
        $url = siteUrl(array('classid'=>$cid));
        $this->render(array('data'=>$data,'url'=>$url),true,'common','common/list-class',array('class-list'));
    }

    /**
     *+---------------------------------------------------
     *albumList——相册列表
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-7-30 上午9:04
     *+----------------------------------------------------
     */
    function albumList(){
        $cid = (int)$_REQUEST['classid']?$_REQUEST['classid']:exit;
        $data = M("class_photo_album")->where('classid='.$cid)->field('id,name,coverImagePath,photoCount,readCount')->order('mTime Desc')->findAll();
        return $data;
    }

    /**
     *+---------------------------------------------------
     *photos——图片
     *+---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function photos(){
        $cid = (int)$_REQUEST['classid']?(int)$_REQUEST['classid']:exit;
        $aid = (int)$_REQUEST['aid']?(int)$_REQUEST['aid']:exit;
        $order	=	'`order` DESC,id DESC';
        $map = array();
        $map['albumId']	=	$aid;
        $map['classid']	=	$cid;
        $map['isDel']	=	0;
        $data = M("class_photo")->where($map)->field('name,mTime,commentCount,readCount,savepath')->order($order)->findAll();
        foreach($data as $k=>$v){
            list($data[$k]['width'],$data[$k]['height']) = getimagesize(SITE_DATA_PATH.'/uploads/'.$v['savepath']);
        }
        return $data;
    }

    /**
     *+---------------------------------------------------
     *birthday——获取当月过生日的同学
     *+---------------------------------------------------
     * @return Ambigous
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function birthday(){
        $cid = (int)$_REQUEST['classid']?$_REQUEST['classid']:exit;
        $m = (int)$_REQUEST['m']?(int)$_REQUEST['m']:date('m');
        $list = get_webService('Ustu017',array('classid'=>$cid,'month'=>$m));
//        var_dump($list);
        $list = getUserImg($list,'uid');
        return $list;
    }

    /**
     *+---------------------------------------------------
     *visitors——访客列表
     *+---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+--------------------------------------------------
     */
    function visitors(){
        $cid = (int)$_REQUEST['classid']?(int)$_REQUEST['classid']:exit;
        $visitor = M('class_visit')->where('classid='.$cid)->join('ts_user on ts_user.uid=ts_class_visit.visituid')->field('ts_class_visit.visituid uid,ts_user.uname,ts_class_visit.vTime')->order('vTime desc')->limit("0,20")->findAll();
        return $visitor;
    }

    /**
     *+---------------------------------------------------
     *seat——座位表
     *+---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function seat(){
        $cid = (int)$_REQUEST['classid']?(int)$_REQUEST['classid']:exit;
//        $cid = 19;
        $seat = D('class_seat')->field('row,col,identityid')->where('classid='.$cid)->order("row,col")->findAll();
        $stu = get_webService("Ustu004",array('classid'=>$cid));
        foreach ($stu as $v) {
            foreach ($seat as $kk=>$vv) {
                if($vv['identityid'] == $v['identityid']){
                    $seat[$kk]['name'] = $v['xm'];
                    if($v['uid']){
                        $u = M('ucenter_user_link')->where('uc_uid='.$v['uid'])->field('uid')->find();
                        $seat[$kk]['uid'] = $u['uid'];
                    }
                    unset($seat[$kk]['identityid']);
                }
            }

        }
        return $seat;
    }

    function seatView(){
        $cid = (int)$_REQUEST['classid']?(int)$_REQUEST['classid']:exit;
//        $cid = 19;
        $seat = M('class_seat')->field('row,col,identityid')->where('classid='.$cid)->order("row,col")->findAll();
        $count = M('class_seat')->field('MAX(row) rowCount,MAX(col) colCount')->where('classid='.$cid)->find();
        $stu = get_webService("Ustu004",array('classid'=>$cid));
        foreach ($stu as $v) {
            foreach ($seat as $kk=>$vv) {
                if($vv['identityid'] == $v['identityid']){
                    $seat[$kk]['name'] = $v['xm'];
                    if($v['uid']){
                        $u = M('ucenter_user_link')->where('uc_uid='.$v['uid'])->field('uid')->find();
                        $seat[$kk]['uid'] = $u['uid'];
                    }
                    unset($seat[$kk]['identityid']);
                }
            }

        }
        $arr = array();
        foreach ($seat as $v) {
            $arr[$v['row']][$v['col']]=$v['name'];
        }
//        p($arr);
//      p($count);
//        exit;
        unset($seat);
        $this->render(array('data'=>$arr,'count'=>$count),true,'common','common/seat',array('seat'));
    }


}