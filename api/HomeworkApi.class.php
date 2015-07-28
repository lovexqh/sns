<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-6-4
 * Time: 上午10:51
 * To change this template use File | Settings | File Templates.
 */
class HomeworkApi extends Api{
    /**
     +---------------------------------------------------
     *getHomeWork
     *获取某个用户班内作业信息以及该用户完成情况和老师评价信息
     +---------------------------------------------------
     * @param cid 班级id
     * @param uid 用户id
     * @author  徐程亮
     +---------------------------------------------------
     *创建时间：13-6-22 下午4:10
     +----------------------------------------------------
     */
    public function getHomeWork(){
        $cid = $_POST['cid'];
        $uid = $_POST['uid'];
        $arr = array(
            array($cid,'int',1),
            array($uid,'int',1),
        );
        checkRules($arr);
//        $uid = 123;
//        $cid = 68;
        $uid = M('ucenter_user_link')->where('uc_uid='.$uid)->field('uid')->find();
        $uid = $uid['uid'];
        if(!$cid) exit;
        $m = new Model();
        $sql = "select e.id,t.name type,u.uname author,e.coursename course,e.title,FROM_UNIXTIME(e.mtime,'%Y-%m-%e %H:%k:%i') mtime,FROM_UNIXTIME(e.stime,'%Y-%m-%e %H:%k:%i') stime,FROM_UNIXTIME(e.etime,'%Y-%m-%e %H:%k:%i') etime,e.spendtime,e.click,e.status,IFNULL(m.id,'') commentId,IFNULL(f.`status`,-1) status
                from ts_exercise e
                left join ts_exercise_finish f on f.eid=e.id and f.uid=$uid
                left join ts_exercise_class c on e.id=c.eid
                left join ts_exercise_type t on e.typeid=t.id
                left join ts_user u on u.uid=e.fromuid
                left join ts_comment m ON m.appid=e.id and m.appuid=$uid and m.type='exercise'
                where c.classid=$cid  and e.status=1
                ";
        return $m->query($sql);
    }

    /**
     +---------------------------------------------------
     *homeWorkList——获取某班的作业列表
     +---------------------------------------------------
     * POST['cid'] 班级id
     * @author  徐程亮
     +---------------------------------------------------
     *创建时间：13-6-22 下午4:13
     +----------------------------------------------------
     */
    public function homeWorkList(){
        $cid = (int)$_POST['cid'];
        if(!$cid) exit;
        $m = new Model();
        $sql = "select e.id,t.name type,u.uname author,e.coursename course,e.title,FROM_UNIXTIME(e.mtime,'%Y-%m-%e %H:%k:%i') mtime,FROM_UNIXTIME(e.stime,'%Y-%m-%e %H:%k:%i') stime,FROM_UNIXTIME(e.etime,'%Y-%m-%e %H:%k:%i') etime,e.spendtime,e.click
                from ts_exercise e
                left join ts_exercise_class c on e.id=c.eid
                left join ts_exercise_type t on e.typeid=t.id
                left join ts_user u on u.uid=e.fromuid
                where c.classid=$cid and e.status=1
                ";
        return $m->query($sql);
    }

    /**
     +---------------------------------------------------
     *stuHomeWorkDetail(暂不用)
     * 通过班级id和作业id获取作业完成情况列表
     +---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     +---------------------------------------------------
     *创建时间：13-6-22 下午4:25
     *+----------------------------------------------------
     */
    public function stuHomeWorkDetail(){
        $cid = $_POST['cid'];
        $eid = $_POST['eid'];
        $arr = array(
            array($cid,'int',1),
            array($eid,'int',1)
        );
        checkRules($arr);
        $sql = "select *
                from ts_exercise_finish f
                inner join ts_exercise_class c on c.eid=f.eid
                LEFT JOIN ts_comment m	ON 	f.uid=m.appuid
                where f.eid=$eid and c.classid=$cid and m.type='exercise' and m.appid=$eid
                ";
        $m = new Model();
        return $m->query($sql);
    }

    /**
     +---------------------------------------------------
     *stuHomeWorkList
     * 获取某班某次作业信息列表
     +---------------------------------------------------
     * @author  徐程亮
     +---------------------------------------------------
     *创建时间：13-6-22 下午4:19
     +----------------------------------------------------
     */
    public function stuHomeWorkList(){
        $cid = $_POST['cid'];
        $eid = $_POST['eid'];
        $arr = array(
            array($cid,'int',1),
            array($eid,'int',1)
        );
        checkRules($arr);
        //通过班级id获取学生信息
        $students = get_webService('Ustu004',array('classid'=>(string)$cid));
        $sql = "select u.uc_uid uid,f.`status`,IFNULL(m.id,'') commentId
                from ts_exercise_finish f
                left join ts_comment m ON f.uid=m.appuid and m.type='exercise' and m.appid=$eid
                left join ts_ucenter_user_link u on u.uid=f.uid
                inner join ts_exercise_class c on c.eid=f.eid
                where f.eid=$eid and c.classid = $cid
                ";
        if($_POST['action'] == "examine"){
//                $cid = 1;
//                $eid = 8;
            $sql = "select u.uc_uid uid,e.flag status,IFNULL(m.id,'') commentId,r.result
                from ts_examine e
                left join ts_comment m ON e.studentid=m.appuid and m.type='examine' and m.appid=$eid
                left join ts_ucenter_user_link u on u.uid=e.studentid
                inner join ts_examine_classes c on c.examineid=e.id
                left join ts_examine_result r on r.examineID=e.id and r.status=1
                where e.id=$eid and c.classid = $cid
                ";
        }
        $m = new Model();
        $finish = $m->query($sql);
        $f = count($finish);
        $s = count($students);
        for($i = 0; $i < $s ;$i++){

            if($students[$i]['uid']){
                $students[$i]['status'] = -2;
            }else{
                $students[$i]['status'] = -1;
            }

            for($j=0; $j< $f ; $j++){
                //完成作业的
                if($students[$i]['uid'] == $finish[$j]['uid']){
                    $students[$i]['status'] = $finish[$j]['status'];
                    $students[$i]['commentId'] = $finish[$j]['commentId'];
                    $students[$i]['result'] = $finish[$j]['result'];
                //未注册的
                }
            }
        }
        return $students;
    }

    /**
     +---------------------------------------------------
     *getHomeWorkDetail——通过学生id和eid获取学生作业的信息
     *+---------------------------------------------------
     *POST['uid'] ###用户在Ucenter中的uid###
     *POST['eid'] 作业id
     * @author  徐程亮
     +---------------------------------------------------
     *创建时间：13-6-22 下午4:21
     +----------------------------------------------------
     */
    function getHomeWorkDetail(){
        $uid = (int)$_POST['uid'];
        $eid = (int)$_POST['eid'];
        $uid = getUid($uid);
        $sql = "select e.id,t.name type,u.uname author,e.coursename course,e.title,e.content,FROM_UNIXTIME(e.mtime,'%Y-%m-%e %H:%k:%i') mtime,FROM_UNIXTIME(e.stime,'%Y-%m-%e %H:%k:%i') stime,FROM_UNIXTIME(e.etime,'%Y-%m-%e %H:%k:%i') etime,e.spendtime,e.click,f.content sContent,FROM_UNIXTIME(f.mtime,'%Y-%m-%e %H:%k:%i') sTime,f.opinion sOpinion,IFNULL(f.`status`,-1) status
				from ts_exercise e
                left join ts_exercise_type t on e.typeid=t.id
                left join ts_user u on u.uid=e.fromuid
				left join ts_exercise_finish f on f.eid=e.id and f.uid=$uid
                where e.status=1 and e.id=$eid
                ";
        $m = new Model();
        return $m->query($sql);
    }
}