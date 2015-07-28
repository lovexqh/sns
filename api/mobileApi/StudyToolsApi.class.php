<?php
/**
 * Created by PhpStorm.
 * User: 徐程亮
 * Date: 13-11-20
 * Time: 上午10:17
 * To change this template use File | Settings | File Templates.
 */
class StudyToolsApi extends Api{

    //获取搜索条件
    function searchParam(){
        return array(
            'courseName'=>array(
                'name'=>'按科目名',
                'type'=>2
            ),
            'teacherName'=>array(
                'name'=>'按老师名',
                'type'=>1
            )
        );
    }


    /**
     *+---------------------------------------------------
     *myCourseList—获取我的选课
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     */
    function myCourseList(){
        $uid = (int)$_REQUEST['uc_id']?(int)$_REQUEST['uc_id']:exit;
//        $uid = 108;
        $data['data'] = D('Course','teaching')->getSelectCourse($uid);
        if($_POST['pc'] == 1){
            if($data){
                return $data;
            }else{
                return 0;
            }
        }
        $arr = array(
            'data' => $data,
            'url' => siteUrl(array('uc_id'=>$uid)),
        );
        $this->render($arr,true,'zepto','common/list-course-info',array('course-info-list','list'));
    }


    /**
     *+---------------------------------------------------
     *searchCourse—搜索
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     */
    function searchList(){
//        type = 1按教师名字 type = 2 按科目名字查
        $type = (int)$_REQUEST['type']?(int)$_REQUEST['type']:exit;
        $keyword = safeSql($_REQUEST['keyword']);
//        $type = 2;
//        $keyword = '美术';
        $data = D('Course','teaching')->searchCourse($keyword,$type);
        $arr = array(
            'data' => $data,
            'type' =>$type,
        );
        $this->render($arr,true,'zepto','common/study-tools-search',array('study-tools-search','common-show'));
    }

    /**
     *+---------------------------------------------------
     *courseList—课程列表
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     */
    function courseInfoList(){
        $type = (int)$_REQUEST['type']?(int)$_REQUEST['type']:exit;
        $id = (int)$_REQUEST['id']?(int)$_REQUEST['id']:exit;
        $page = (int)$_REQUEST['page']?(int)$_REQUEST['page']:1;
//        $id = '010109';
//        $type = 1;
//        $page = 1;
        $data = D('Course','teaching')->listCourse($id,$type,$page);
        if($_POST['pc'] == 1){
            if($data){
                return $data;
            }else{
                return 0;
            }
        }
        $arr = array(
            'data' => $data,
            'url' => siteUrl(array('type'=>$type,'id'=>$id)),
        );
//        var_dump($data);
        $this->render($arr,true,'zepto','common/list-course-info',array('course-info-list','list'));
//        $this->render($arr,true,'zepto','common/list-job',array('course-info-list'));
    }

    /**
    +---------------------------------------------------
     *courseList—课程表list
    +---------------------------------------------------
     * @author  徐程亮
    +---------------------------------------------------
     *创建时间：13-11-12 上午10:36
    +----------------------------------------------------
     */
    function courseList(){
        //用户uid
        $uc_uid = (int)$_REQUEST['uc_id']?(int)$_REQUEST['uc_id']:exit;
//        第几个周
        $week = (int)$_REQUEST['week']?(int)$_REQUEST['week']:exit;
        //周几
        $day = (int)$_REQUEST['day']?(int)$_REQUEST['day']:-1;
        //获取详情
        $aid = (int)$_REQUEST['aid']?(int)$_REQUEST['aid']:-1;
//        $day=5;
//        $uc_uid = 153;
//        $week = 13;
//        $aid=52;
        $r = D('Course','teaching')->getThisXnd();
        $xnd = $r['xnd'];
        $kkxq = $r['kkxq'];
        $data = D('Course','teaching')->getStudentCourse($uc_uid,$xnd,$kkxq);
//        var_dump($data);
        $arr = array();
        $arr1 = array();
        if($aid > 0){
            //老师名
            $arr['jsm1'] = $data[$week][$aid]['jsm1'];
            //课程名
            $arr['kcm'] = $data[$week][$aid]['kcm'];
            //课程类型
            $arr['kclb'] = $data[$week][$aid]['kclb'];
            //(选修or必修)
            $arr['kcsx'] = $data[$week][$aid]['kcsx'];
            //教学搂名
            $arr['jxlm'] = $data[$week][$aid]['jxlm'];
            //教室名
            $arr['jsm'] = $data[$week][$aid]['jsm'];
            //学分
            $arr['xf'] = $data[$week][$aid]['xf'];
            return $arr;
        }
        foreach ($data[$week] as $k=>$v) {
            $k = (string)$k;
            if($k !=00 ){
                if($day > 0){
                    if($k[0] == $day){
                        $arr['aid'] = $k;
                        $arr['xnd'] = $v['xnd'];
                        $arr['kcm'] = $v['kcm'];
                        $arr['kkxq'] = $v['kkxq'];
                        $arr['jxlm'] = $v['jxlm'];
                        $arr['jsm'] = $v['jsm'];
                        $arr['xf'] = $v['xf'];
                        $arr1[] = $arr;
                    }
                }else{
                    $arr['aid'] = $k;
                    $arr['xnd'] = $v['xnd'];
                    $arr['kcm'] = $v['kcm'];
                    $arr['kkxq'] = $v['kkxq'];
                    $arr1[] = $arr;
                }
            }

        }
//        var_dump($arr1);
        return $arr1;
    }

}
