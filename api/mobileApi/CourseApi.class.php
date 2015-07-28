<?php
/**
 * Created by PhpStorm.
 * User: 徐程亮
 * Date: 13-11-12
 * Time: 上午9:14
 * To change this template use File | Settings | File Templates.
 */
class CourseApi extends Api{

    function allWeeks(){
        $arr['all'] = D('Course','teaching')->totalweek;
        $arr['now'] = D('Course','teaching')->datetoweek(date('Y-m-d',time()));
        return $arr;
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
        $uc_uid = (int)$_REQUEST['uc_id']?(int)$_REQUEST['uc_id']:exit;
        $week = (int)$_REQUEST['week']?(int)$_REQUEST['week']:exit;
//        $uc_uid = 153;
//        $week = 1;
        $xnd = date("Y").'-'.date("Y",strtotime('+1 year'));
        $kkxq = "春";
        if((int)date("n") > 9){
            $kkxq = "秋";
        }
        $data = D('Course','teaching')->getStudentCourse($uc_uid,$xnd,$kkxq);
        $arr = array();
        $arr1 = array();
        foreach ($data[$week] as $k=>$v) {
            $arr['aid'] = $k;
            $arr['xnd'] = $v['xnd'];
            $arr['kcm'] = $v['kcm'];
            $arr['kkxq'] = $v['kkxq'];
            $arr1[] = $arr;
        }
        unset($data);
        return $arr1;
    }

    function courseDetail(){
        $uc_uid = (int)$_REQUEST['uc_id']?(int)$_REQUEST['uc_id']:exit;
        $aid = (int)$_REQUEST['aid']?(int)$_REQUEST['aid']:exit;
        $week = (int)$_REQUEST['week']?(int)$_REQUEST['week']:exit;
        $xnd = safeSql($_REQUEST['xnd']);
        $kkxq = safeSql($_REQUEST['kkxq']);
//        $aid = 54;
//        $xnd = '2013-2014';
//        $kkxq = '秋';
//        $uc_uid = 153;
//        $week = 1;
        $d = D('Course','teaching')->get_detail($uc_uid,$xnd,$kkxq,$week,$aid);
        $arr['code_kcm'] = $d['code_kcm'];
        $arr['jsm1'] = $d['jsm1'];
        $arr['xnd'] = $d['xnd'];
        $arr['xf'] = $d['xf'];
        $arr['jxlm'] = $d['jxlm'];
        $arr['jsm'] = $d['jsm'];
        $arr['kslx'] = $d['kslx'];
        $arr['kcsx'] = $d['kcsx'];
        return $arr;
    }

}
