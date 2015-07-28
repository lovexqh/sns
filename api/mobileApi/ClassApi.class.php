<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-7-18
 * Time: 上午8:36
 * To change this template use File | Settings | File Templates.
 */
class ClassApi extends Api{

    //获取精品课堂列表
    public function classList(){
        $data = array();
        $conditions = '';
        $who = (int)$_REQUEST['who'];
        $author = (int)$_REQUEST['author_id'];
        $uid = $this->mid;
        if(service('ForeAdmin')->isAuditApp('airclass')){
            $status = "c.status=1";
        }else{
            $status = "(c.status=0 or c.status=1)";
        }


        if($who ==1 ){
            $conditions = 'u.uid='.$uid.' and ';
            //查看所有人
        }elseif($who == 3){
            //基础分类
            if($_REQUEST['category']==1){

                //获取分类
                $type=$_REQUEST['type'];
                $section=$_REQUEST['section'];
                $option['Grade']=$_REQUEST['Grade'];
                $option['Subject']=$_REQUEST['Subject'];
                $option['Publisher']=$_REQUEST['Publisher'];
                $option['Volume']=$_REQUEST['Volume'];
                $option['Cell']=$_REQUEST['Cell'];

                $data['category']=model('Knowledge')->getTreeNode($type,$section,$option);

                //组合 sql 查询条件
                $_REQUEST['Grade'] && $conditions = 'grade='.$_REQUEST['Grade'].' and ';
                $_REQUEST['Subject'] && $conditions .= 'course='.$_REQUEST['Subject'].' and ';
                $_REQUEST['Publisher'] && $conditions .= 'editon='.$_REQUEST['Publisher'].' and ';
                $_REQUEST['Volume'] && $conditions.= 'fence='.$_REQUEST['Volume'].' and ';
                $_REQUEST['Cell'] && $conditions.= 'cell='.$_REQUEST['Cell'].' and ';

                //以学段分类查看
                if($_GET['stage']){
                    $stage = 'small';
                    if($stage=='small'){
                        $conditions  = "c.xueduan=1 and ";
                    }else if($stage=='middle'){
                        $conditions  = "c.xueduan=2 and ";
                    }else if($stage=='high'){
                        $conditions  = "c.xueduan and ";
                    }
                }

            }elseif($_REQUEST['category'] == 2){

                $data['category'] = M('select_data')->where("`appname`='airclass' and `type`='category' and `status` = 1")->findAll();
                if($_GET['stage']){
                    $stage = 'small';
                    if($stage=='small'){
                        $conditions  = "c.xueduan=1 and ";
                    }else if($stage=='middle'){
                        $conditions  = "c.xueduan=2 and ";
                    }else if($stage=='high'){
                        $conditions  = "c.xueduan and ";
                    }
                }
            }
            //查看某个用户
        }elseif($who == 2){
            $conditions = "u.uid=$author and $status and ";
        }
        $limit = getLimit();
        $sql = "select c.classid,c.uid author_id,u.uname author_name,c.name,count(c.classid) num,c.attendeepw,c.moderatorpw,c.spendtime,c.info,FROM_UNIXTIME(c.stime,'%Y-%m-%e %H:%k:%i') sTime,FROM_UNIXTIME(c.etime,'%Y-%m-%e %H:%k:%i') eTime
                    from ts_airclass c
                    left join ts_user u on u.uid=c.uid
                    left join ts_airclass_member m on m.classid=c.classid
                    where $conditions 1=1
                    group by (c.classid)
                    order by c.stime
                    limit $limit
                    ";
        p($sql);
        $data['resources'] = M('')->query($sql);
        p($data);
        return $data;
    }

}
