<?php
class StudyRoomApi extends Api{

    function searchData(){
        $data = array();
        $data['class'] = array(
            'am' => array('1'=>'第一节课','第二节课'),
            'pm' => array('3'=>'第三节课','第四节课'),
            'night' => array('5'=>'第五节课','第六节课'),
        );
        $data['classRoom'] = D('Course','teaching')->getAllClassroom();
        return $data;
    }



    function studyRoom(){
        $time = date('Y-m-d',$_REQUEST['time']);
        $day = date('N',(int)$_REQUEST['time']);
        $jxlh = (int)$_REQUEST['jxlh'];
        $skjc = checkType($_REQUEST['skjc'],'string',array(','))?$_REQUEST['skjc']:'';
        //获取当前是第几个周
        $week = D('Course','teaching')->datetoweek($time);
        //获取教室
        $data = D('Course','teaching')->getClassRoom($week,$day,$jxlh,$skjc);
        return $data;
    }

}