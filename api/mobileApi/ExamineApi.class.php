<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-5-23
 * Time: 下午4:27
 * To change this template use File | Settings | File Templates.
 */
class ExamineApi extends Api{
    /**
     *+---------------------------------------------------
     *questionStyle——获取题型
     *+---------------------------------------------------
     * @return id，stylename，auto=1为可自动批题
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-6-23 下午1:09
     *+----------------------------------------------------
     */
    function questionStyle(){
        return M('examine_style')->field('id,stylename,auto')->select();
    }

    /**
     *+---------------------------------------------------
     *getPapers——获取试卷列表
     *+---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-6-23 下午2:10
     *+----------------------------------------------------
     */
    function getPapers(){
        $limit = (int)$_REQUEST['limit'];
        $pageNo = (int)$_REQUEST['pageNo'];
        $start = ($pageNo-1) * $limit;
        if(!$pageNo) $start  = 0;
        if(!$limit) $limit = 20;
        $uid = (int)$_REQUEST['uid'];
        $sid = (int)$_REQUEST['schoolid'];
        $conditions = self::conditions();
        $sql = "select p.id,p.name,p.createtime,p.courseid,p.coursename,p.gradeid,p.gradename
                from ts_examine_paper p
                where $conditions p.schoolid=$sid and p.ownerid=$uid
                order by p.createtime desc
                limit $start , $limit
                ";
        $m = new Model();
        return $m->query($sql);
    }

    /**
     *+---------------------------------------------------
     *getExamineClass——获取某场考试的参加的所有班级列表
     *+---------------------------------------------------
     * @author  徐程亮
     * TODO：一场考试只对应一个级部
     *+---------------------------------------------------
     *创建时间：13-6-25 下午1:11
     *+----------------------------------------------------
     */
    function getExamineClass(){
        $eid = $_REQUEST['eid'];
        $sid = $_REQUEST['sid'];
//        $eid = 8;
//        $sid = 1;
        $arr = array(
            array($eid,'int',1),
            array($sid,'int',1)
        );
        checkRules($arr);
        $in = M('examine_classes')->where('examineid='.$eid.' and schoolid='.$sid)->select();
        return $in;
//        if(count($in) ==1 && $in[0]['all']==1){
//            return get_webService('Usch013',array('jbid'=>(string)$in[0]['jbid']));
//        }else{
//            return $in;
//        }
    }



    /*
     * 试卷删除
     */

    function deletePaper(){
        $paperID = (int)$_REQUEST['paperid'];
        return D('Examine','Examine')->deletePaper($paperID);
    }

    /*
     * 查看试卷详细信息
     */

    function getPaperInfo(){
        $paperID = (int)$_REQUEST['paperid'];
//        $paperID = 2;
        return D('Examine','Examine')->getPaperInfo($paperID);
    }

    /*
     *
     */
    static function conditions(){
        $course = (int)$_REQUEST['course'];
        $grade = (int)$_REQUEST['grade'];
        $version = (int)$_REQUEST['version'];
        $conditions = '';
        if($course){
            $conditions = 'courseid='.$course.' and ';
        }
        if($grade){
            $conditions .= 'gradeid='.$grade.' and ';
        }
        if($version){
            $conditions .= 'versionid='.$grade.' and ';
        }
        return $conditions;
    }

   /*
    *  获取试题列表
    */

    function getQuestions(){
//        $limit = (int)$_REQUEST['limit'];
//        $pageNo = (int)$_REQUEST['pageNo'];
        $style = (int)$_REQUEST['style'];
        $uid = (int)$_REQUEST['uid'];
//        $start = ($pageNo-1) * $limit;
        $conditions = self::conditions();
        $style = (int)$_REQUEST['style'];
        if($style){
            $conditions .= 'styleid='.$style.' and ';
        }
        $limit = self::page();
//        return D('Examine','Examine')->getQuestions($style,$course,$grade,$version,$knowledge,$start,$limit,$mid);
        $sql = "select q.id,q.content,q.gradeid,q.gradename,q.courseid,q.coursename,s.stylename,q.createtime
                from ts_examine_question q
                left join ts_examine_style s on q.styleid=s.id
                where $conditions q.ownerid=$uid
                limit $limit
                ";
        $m = new Model();
        return $m->query($sql);
    }

    /*
     * 获取试题筛选条件数据：课程，年级，题型
     */

    function getQuestionFilter(){
        return D('Examine','Examine')->getQuestionFilter();
    }

    /*
     * 插入试题
     */

    function addQuestion(){
        //TODO 图片上传待更新
        return D('Examine','Examine')->doAddQuestion($_REQUEST['content'],$_REQUEST['answer'],$_REQUEST['options'],$_REQUEST['knowledge'],$imgpath,$_REQUEST['styleid'],$_REQUEST['courseid'],$_REQUEST['coursename'],$_REQUEST['gradeid'],$_REQUEST['gradename'],$_REQUEST['versionid'],$_REQUEST['schoolid'],$_REQUEST['uid']);
    }

    /*
     * 修改试题
     */
    function updateQuestion(){
        return D('Examine','Examine')->doUpdateQ($_REQUEST['id'],$_REQUEST['content'],$_REQUEST['answer'],$_REQUEST['options'],$_REQUEST['knowledge'],$imgpath,$_REQUEST['styleid'],$_REQUEST['courseid'],$_REQUEST['coursename'],$_REQUEST['gradeid'],$_REQUEST['gradename'],$_REQUEST['versionid']);
    }

    /*
     * 试题删除
     */
    function deleteQuestion(){
        $questionID = (int)$_REQUEST['questionid'];
        if(!$questionID) exit;
        return D('Examine','Examine')->deleteQuestion($questionID);
    }

    /*
     * 查看试题
     */
    function getQuestionInfo(){
        $questionID = (int)$_REQUEST['questionid'];
        if(!$questionID) exit;
        return D('Examine','Examine')->getQuestionInfo($questionID);
    }

    /*
     * 获取考试列表
     */
    function getExamines(){
        $course = (int)$_REQUEST['course'];
        $grade = (int)$_REQUEST['grade'];
        $type = (int)$_REQUEST['type'];
        $sid = (int)$_REQUEST['schoolid'];
        $uid = (int)$_REQUEST['uid'];
        $limit = self::page();
        $conditions = '';
        if($course){
            $conditions .= 'i.courseid='.$course.' and ';
        }
        if($grade){
            $conditions .= 'c.classid='.$grade.' and ';
        }
        if($sid){
            $conditions .= 'c.schoolid='.$sid.' and ';
        }
        if($type){
            $conditions .= 't.id='.$type.' and ';
        }
        $sql = "select i.id,i.name,i.starttime,i.peoples,i.corrects,i.duration,i.flag,i.coursename,p.name papername
                from ts_examine_info i
                inner join ts_examine_paper p on p.id=i.paperid
                where $conditions i.publisherid=$uid
                order by i.id desc
                limit $limit
                ";
        $m = new Model();
        $arr = array();
        $m = $m->query($sql);
        foreach ($m as $k) {
            if(time() < $k['starttime']){
               $k['examine_status']='未开考';
            }elseif(time() > $k['starttime'] && time() > $k['starttime']+$k['duration']*60){
                $k['examine_status']='考试中';
            }elseif(time() > $k['starttime'] && time() < $k['starttime']+$k['duration']*60){
                $k['examine_status']='考试完毕';
                if($k['peoples']==$k['corrects']){
                    $k['correct_status']='已经全部批改完';
                }else{
                    $k['correct_status']='还有'.$k['peoples']-$k['corrects'].'份未批改';
                }
            }
            $arr[] = $k;
        }
        return $arr;

    }

    /*
     * 撤销未开始的考试
     */
    function undoExamine(){
        $infoID = (int)$_REQUEST['infoid'];
        if(!$infoID) exit;
        $time = time();
        $m = M('examine_info');
        if($m->where('id='.$infoID.' and starttime>'.$time)->delete()){
            return 1;
        }else{
            return 0;
        }
    }

    /*
     *  获取要批改的考试
     */
    function getRemarkExamines(){
        $mid = $this->mid;
        return D('Examine','Examine')->getRemarkExamines($mid);
    }

    /*
     * 获取要批改的学生列表
     */
    function getNeedRemarkStus(){
        $infoID = (int)$_REQUEST['infoid'];
        if(!$infoID) exit;
        return D('Examine','Examine')->getNeedRemarkStus($infoID);
    }

    function submitExamine(){
        $data['studentid'] = (int)$_REQUEST['uid'];
        $data['flag'] = 1;
        $data['infoid'] = (int)$_REQUEST['eid'];
        M('examine_info')->setInc('peoples','id='.$data['infoid']);
        M('examine')->add($data);
    }

    /*
     * 获取某个学生某场考试的试题列表
     */
    function getExamineQuestions(){
        $paperID = (int)$_REQUEST['parperid'];
        $examineID = (int)$_REQUEST['examineid'];
        return D('Examine','Examine')->getExamineQuestions($paperID,$examineID);
    }

    /*
     * 获取已结束的考试列表(成绩统计时的考试列表)
     */
    function getFinishedExamine(){
        $limit = (int)$_REQUEST['limit'];
        $pageNo = (int)$_REQUEST['pageNo'];
        $course = (int)$_REQUEST['course'];
        $grade = (int)$_REQUEST['grade'];
        $type = (int)$_REQUEST['type'];
        $start = ($pageNo-1) * $limit;
        $mid = $this->mid;
        if(!$pageNo) $start  = 0;
        if(!$limit) $limit = 20;
        return D('Examine','Examine')->getFinishedExamine($course,$grade,$type,$start,$limit,$mid);

    }

    /*
     * 获取某场考试的成绩排名列表
     */
    function getExamineResult(){
        $infoID = (int)$_REQUEST['infoid'];
        return D('Examine','Examine')->getExamineResult($infoID);
    }

    /**
     *+---------------------------------------------------
     *studentsExamineList——获取学生的考试列表
     *+---------------------------------------------------
     * @param   cid     班级id
     * @param   uid     学生id
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function studentsExamineList(){
        return D('Examine','Examine')->studentsExamineList();
    }

    /**
     * 学生答题
     */
    public function studentAnswer(){
        $data = array();
        $data['uid'] = (int)$_REQUEST['uid'];
        $data['questionid'] = (int)$_REQUEST['qid'];
        $data['content'] = t($_REQUEST['content']);
        $data['examineid'] = (int)$_REQUEST['eid'];
        $data['id'] = (int)$_REQUEST['aid'];
        $auto = (int)$_REQUEST['auto'];
        if($auto == 1){
            $question = M('examine_question')->where('id='.$data['questionid'])->field('answer')->find();
            if(trim($data['content']) == trim($question['answer'])){
                $data['result'] = 1;
                $data['score'] = (int)$_REQUEST['score'];
            }else{
                $data['result'] = 0;
                $data['score'] = 0;
            }
        }
        if($data['id']){
            if($id = M('examine_answer_result')->where('id='.$data['id'])->save($data)){
               return $id;
            }
        }else{
            if($id = M('examine_answer_result')->add($data)){
                return $id;
            }
        }
    }

    /*
     * 创建试卷
     */
   function addPaper(){
       $data = array();
       $data['name'] = t($_REQUEST['name']);
//       $data['questionsum'] = t($_REQUEST['questions']);
       $data['totalscore'] = (int)$_REQUEST['totalScore'];
       $data['ownerid'] = (int)$_REQUEST['uid'];
       $data['gradeid'] = (int)$_REQUEST['gid'];
       $data['gradename'] = t($_REQUEST['gradename']);
       $data['gradeid'] = (int)$_REQUEST['gradeid'];
       $data['schoolid'] = (int)$_REQUEST['schoolid'];
       $data['courseid'] = (int)$_REQUEST['cid'];
       $data['coursename'] = t($_REQUEST['cname']);
       $data['createtime'] = time();
       $id = M('examine_paper')->add($data);
       return $id;
   }

   /*
    *导入试题
    */
    function importQuestions(){
        $pid = (int)$_REQUEST['pid'];
        $q = t($_REQUEST['q']);
        $q = substr($q,0,-1);
        $q = explode('^',$q);
        $m = new Model();
        $sql = 'insert into ts_examine_paper_to_question(questionid,paperid,score)values';
        foreach($q as $v){
            $a = explode(',',$v);
            $sql.='('.$a['0'].','.$pid.','.$a[1].'),';
        }
        $sql = substr($sql,0,-1);
        if($r = $m->execute($sql)){
            return $r;
        }
    }

    /**
     *从试卷中删除试题
     */
    function dQuestionFromPaper(){
        $pid = (int)$_REQUEST['pid'];
        $qid = (int)$_REQUEST['qid'];
        if($pid && $qid)
            return M('examine_paper_to_question')->where('questionid='.$qid.' and '.'paperid='.$pid)->delete();
        else
            exit;
    }

    /**
     *老师发布考试
     */
    public function createExamine(){
        $data = array();
        //开始时间
        $data['starttime'] = strtotime($_REQUEST['sTime']);
        //考试时长
        $data['duration'] = (int)($_REQUEST['duration']);
        //考试名字
        $data['name'] = t($_REQUEST['name']);
        //科目id
        $data['courseid'] = (int)$_REQUEST['courseid'];
        //科目名字
        $data['coursename'] = t($_REQUEST['coursename']);
        //考试类型id
        $data['examine_typeid'] = (int)$_REQUEST['typeid'];
        $data['publisherid'] = (int)$_REQUEST['uid'];
        //试卷id
        $data['paperid'] = (int)$_REQUEST['paperid'];
        if($id = M('examine_info')->add($data)){
            return $id;
        }else{
            return -1;
        }
    }


    /**
     *老师修改考试
     */
    public function updateExamine(){
        $data = array();
        //开始时间
        $data['starttime'] = strtotime($_REQUEST['sTime']);
        //考试时长
        $data['duration'] = (int)($_REQUEST['duration']);
        //考试名字
        $data['name'] = t($_REQUEST['name']);
        //科目id
        $data['courseid'] = (int)$_REQUEST['courseid'];
        //科目名字
        $data['coursename'] = t($_REQUEST['coursename']);
        //考试类型id
        $data['examine_typeid'] = (int)$_REQUEST['typeid'];
        $data['publisherid'] = (int)$_REQUEST['uid'];
        $id = (int)$_REQUEST['id'];
        if($id = M('examine_info')->where('id='.$id)->save($data)){
            return $id;
        }else{
            return -1;
        }
    }




    /**
     * 选择参考班级
     */
    public function selectClass(){
        //是否选择整个级部 all = 1 选择整个级部， all = 0 时不选择全部
        $all = (int)$_REQUEST['all'];
        //考试id
        $eid = (int)$_REQUEST['eid'];
        //学校
        $sid = (int)$_REQUEST['sid'];
        //级部id
        $jbid = (int)$_REQUEST['jbid'];
        $name = t($_REQUEST['name']);
        //班级 array()
        $classes = $_REQUEST['classes'];
        $classes = str_replace('\'','"',$classes);
        $classes = json_decode($classes,true);
        //
        $data = array();
        $data['examineid'] = $eid;
        $data['schoolid'] = $sid;
        $data['jbid'] = $jbid;
        $m = M('examine_classes');
        if($all == 1){
            $data['all'] = 1;
            $data['name'] = $name;
            if($m->add($data)){
                return 1;
            }else{
                return 0;
            }
        }else{
            $data['all'] = 0;
            $m->startTrans();
            $a = true;
            foreach($classes as $k=>$v){
                $data['classid'] = $k;
                $data['name'] = $v;
                if(!$m->add($data)){
                    $a = false;
                    break;
                }
            }
            if($a){
                $m->commit();
                return 1;
            }else{
                $m->rollback();
                return 0;
            }
        }
    }

    /**
     * @return 考试列表
     */
    public function examineList(){
        $cid = (int)$_REQUEST['cid'];
        $sid = (int)$_REQUEST['sid'];
//        $jbid = (int)$_REQUEST['jbid'];
        $jb = jbInfo($cid);
        $jbid = $jb['id'];
        $sql = "select i.id eid,i.starttime,i.duration,i.name,i.coursename,u.uname,t.typename
                from ts_examine_info i
                left join ts_user u on u.uid=i.publisherid
                left join ts_examine_type t on t.id=i.examine_typeid
                left join ts_examine_classes c on c.examineid=i.id
                where c.classid=$cid  or c.jbid=$jbid and schoolid=$sid
                ";
        $m = new Model();
        return $m->query($sql);
    }

    public function answer(){
        $data = array();
        //考试id
        $data['examineid'] = (int)$_REQUEST['eid'];
        $data['questionid'] = (int)$_REQUEST['qid'];
        $data['content'] = t($_REQUEST['content']);
        $data['examineid'] = 2;
        $time = M('examine_info')->where('id='.$data['examineid'])->field('starttime,duration')->find();
        $time = $time['starttime']+$time['duration']*60+60;
        if(time() < $time){
            if($a = M('ts_examine_answer_result')->add($data)){
                return $a;
            }
        }else{
            return 0;
        }
    }

    /**
     * 老师批卷
     * @return mixed
     */
    public function correct(){
        //学生id
        $sid = (int)$_REQUEST['sid'];
        //考试id
        $eid = (int)$_REQUEST['eid'];
        //试卷id
        $pid = (int)$_REQUEST['pid'];
//        $sid = 1;
//        $pid = 5;
        $limit = self::page();
        $sql = "select q.id,q.content,q.answer rightAnswer,q.options,q.flag,a.content studentAnswer,a.result,a.score,pq.score qScore,a.criticism,stylename,s.auto
                from ts_examine_question q
                inner join ts_examine_paper_to_question pq on pq.questionid=q.id
                left join ts_examine_answer_result a on a.questionid=q.id and a.examineid=$eid and a.uid=$this->mid
                left join ts_examine_style s on s.id=q.styleid
                where pq.paperid=$pid and a.uid=$sid
                order by s.order,q.id
                limit $limit
                ";
        if($_REQUEST['action'] == 'play'){
            $qid = (int)$_REQUEST['qid'];
            $conditions = '';
            if($qid){
                $conditions = 'q.id='.$qid.' and';
            }
            $sql = "select q.id,q.content,q.options,q.flag,a.id aid,a.content studentAnswer,pq.score qScore,s.stylename,s.auto
                    from ts_examine_question q
                    inner join ts_examine_paper_to_question pq on pq.questionid=q.id
                    left join ts_examine_answer_result a on a.questionid=q.id and a.uid=$this->mid and a.examineid=$eid
                    left join ts_examine_style s on s.id=q.styleid
                    where $conditions pq.paperid=$pid
                    order by s.order,q.id
                    limit $limit
                    ";
        }
        $m = new Model();
        return $m->query($sql);
    }

    /**
     * 老师提交批卷信息
     * @return mixed
     */
    public function doCorrect(){
        $action = t($_REQUEST['action']);
        $data = array();
        //学生id
        $data['uid'] = (int)$_REQUEST['uid'];
        if($action == 'submit'){
            //老师id
            $data['tid'] = (int)$_REQUEST['tid'];
            //题id
            $data['questionid'] = (int)$_REQUEST['qid'];
            //对错
            $data['result'] = (int)$_REQUEST['result'];
            //得分
            $data['score'] = (int)$_REQUEST['score'];
            //老师评论
            $data['criticism'] = t($_REQUEST['criticism']);
            if($id = M('examine_answer_result')->where('uid='.$data['uid'].' and questionid='.$data['questionid'])->save($data)){
                return $id;
            }
        }elseif($action = 'halfSubmit'){
            return self::e(3);
        }elseif($action='allSubmit'){
            return self::e(4);
        }
    }

    /**
     * 试题列表，带有错题率
     */
    public function questionsWithWrong(){
        $eid = (int)$_POST['eid'];
        $pid = M('examine')->where('id='.$eid)->field('paperid')->find();
        $pid = $pid['paperid'];
        $limit = self::page();
        $sql = "SELECT
			      q.id,q.content,q.options,q.imgpath,q.answer,q.flag,p.score,SUM(CASE  WHEN  result = 0 THEN  1  ELSE  0 END ) wrong,count(*) total,(SUM(CASE  WHEN  result = 0 THEN  1  ELSE  0 END ) / count(*)) q
	            FROM ts_examine_question q
	            LEFT JOIN ts_examine_answer_result a ON a.questionid=q.id
	            LEFT JOIN ts_examine_paper_to_question p ON p.questionid=q.id
	            WHERE p.paperid=$pid
	            GROUP BY a.questionid
	            ORDER BY p.order
	            LIMIT $limit";
        $m = new Model();
        $res = $m->query($sql);
        $arr = array();
        foreach($res as $v){
            $v['q'] = (round($v['q'],2) * 100).'%';
            $arr[] = $v;
        }
        return $arr;
    }

    public function wrong(){
        $qid = (int)$_REQUEST['qid'];
       $sql = "SELECT a.questionid id,a.wrong,a.total,a.q FROM(
	              SELECT
			        a.questionid,SUM(CASE  WHEN  result = 0 THEN  1  ELSE  0 END ) wrong,count(*) total,(SUM(CASE  WHEN  result = 0 THEN  1  ELSE  0 END ) / count(*)) q
	              FROM ts_examine_answer_result a
	              GROUP BY a.questionid) a
	           WHERE a.questionid=$qid;
               ";
        $m = new Model();
        $res = $m->query($sql);
        $arr = array();
        foreach($res as $v){
            $v['q'] = (round($v['q'],2) * 100).'%';
            $arr[] = $v;
        }
        return $arr;
    }

    /**
     * 老师提交批改完考卷信息
     * @param $status
     * @return int
     */
    static public function e($status){
        $data = array();
        //学生id
        $data['uid'] = (int)$_REQUEST['uid'];
        //考试id
        $data['infoid'] = (int)$_REQUEST['eid'];
        //班级
        $data['classid'] = (int)$_REQUEST['cid'];
        //学校
        $data['schoolid'] = (int)$_REQUEST['sid'];
        //考试成绩
        $data['score'] = (int)$_REQUEST['allScore'];
        $data['flag'] = $status;
        $m = M('examine');
        if(! $m->where('uid='.$data['uid'].' and info='.$data['infoid'].' and classid='.$data['classid'].' and schoolid='.$data['schoolid'])->find()){
            if($m->add($data)){
                $a = true;
            }
        }else{
            if($m->where('uid='.$data['uid'].' and info='.$data['infoid'].' and classid='.$data['classid'].' and schoolid='.$data['schoolid'])->save($data)){
               $a = true;
               M('examine_info')->setInc('corrects','id='.$data['infoid']);
            }
        }
        if($a){
            return 1;
        }else{
            return 0;
        }
    }

    /*
     * 返回limit default:0,20
     */
    static public function page(){
        $limit = (int)$_REQUEST['limit'];
        $pageNo = (int)$_REQUEST['pageNo'];
        $start = ($pageNo-1) * $limit;
        if(!$pageNo) $start  = 0;
        if(!$limit) $limit = 20;
        return $start.','.$limit;
    }


    /**
     * 通过班级id获取班级考试列表
     */
    public function getExaminesList(){
        $cid = (int)$_REQUEST['cid'];
        $sql = "select i.id,i.name,i.coursename course,FROM_UNIXTIME(i.starttime,'%Y-%m-%e %H:%k:%i') startTime
                from ts_examine_info i
                inner join ts_examine_classes c on c.examineid=i.id
                left join ts_examine_type t on i.examine_typeid=t.id
                where c.classid=$cid
                ";
        $m = new Model();
//        echo $sql;
        return $m->query($sql);
    }

}













