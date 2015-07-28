<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-7-15
 * Time: 上午9:23
 * To change this template use File | Settings | File Templates.
 */
class ResourceApi extends Api{

    /**
     *+---------------------------------------------------
     *resourceList——返回资源列表和分类
     *+---------------------------------------------------
     * @return array
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function resourceList(){
//        $_REQUEST['category'] =1;
        $data = array();
        $conditions = '';
        $who = (int)$_REQUEST['who'];
        $author = (int)$_REQUEST['author_id'];
        $uid = $this->mid;
        $limit = getLimit(false,10);
        if(service('ForeAdmin')->isAuditApp('resource')){
            $status = "r.state=1";
        }else{
            $status = "(r.state=0 or r.state=1)";
        }
        if($who ==1 ){
            $conditions = 'u.uid='.$uid.' and ';
            $url = siteUrl(array('who'=>1));
            //查看所有人
        }elseif($who == 3){
            //基础分类
            if($_REQUEST['category']==1){
//
                //组合 sql 查询条件
                $_REQUEST['section'] && $conditions = 'section='.(int)($_REQUEST['section']).' and ';
                $_REQUEST['Grade'] && $conditions .= 'grade='.t($_REQUEST['Grade']).' and ';
                $_REQUEST['Subject'] && $conditions .= 'subject='.t($_REQUEST['Subject']).' and ';
                $_REQUEST['Publisher'] && $conditions .= 'publisher='.t($_REQUEST['Publisher']).' and ';
                $_REQUEST['Volume'] && $conditions.= 'volume='.t($_REQUEST['Volume']).' and ';
                $_REQUEST['Cell'] && $conditions.= 'cell='.t($_REQUEST['Cell']).' and ';
                $_REQUEST['Course'] && $conditions.= 'courseid='.t($_REQUEST['Course']).' and ';

                $c = $conditions;
                $conditions .= $status.' and class=\'ElementaryEDU\' and ';
                $_REQUEST['resource_type'] && $conditions.= 'r.type='.$_REQUEST['resource_type'].' and ';

                $arr = array(
                    'who'       =>  3,
                    'category'  =>  1,
                    'section'     =>  $_REQUEST['section'],
                    'Grade'     =>  $_REQUEST['Grade'],
                    'Subject'   =>  $_REQUEST['Subject'],
                    'Publisher' =>  $_REQUEST['Publisher'],
                    'Volume'    =>  $_REQUEST['Volume'],
                    'Cell'      =>  $_REQUEST['Cell'],
                    'Course'    =>  $_REQUEST['Course'],
                    'resource_type' =>  $_REQUEST['resource_type'],
                );
                $url = siteUrl($arr);
                $data['type'] = M('category_dictionary')->where("DataType=RecourseType")->field("DataCode,DataName")->order("DataOrder")->findAll();
                //其它分类
            }elseif($_REQUEST['category']==2){

                $cid = (int)$_REQUEST['cid']?(int)$_REQUEST['cid']:exit;
                $data = procExecute("call vcBlogCategory($cid,'{$limit}','r.time','3');");
                $url = siteUrl(array('who'=>3,'category'=>2,'cid'=>$cid));

            }elseif($_REQUEST['category']==3){
                //装条件
                $conditions = 'r.attribute='.$_REQUEST['attribute'].' and '.$status.' and. ';
                $_REQUEST['resource_type'] && $conditions.= 'r.type='.$_REQUEST['resource_type'].' and ';

                $arr = array(
                    'who'       =>  3,
                    'category'  =>  3,
                    'attribute' =>  $_REQUEST['attribute'],
                    'resource_type' =>  $_REQUEST['resource_type']
                );
                $url = siteUrl($arr);
            }
            //查看某个用户
        }elseif($who == 2){
            $conditions = "u.uid=$author and $status and ";
            $url = siteUrl(array('who'=>2,'uid'=>$author));
        }

        if($who == 1 || $who ==2 || ($who==3 && $_REQUEST['category'] != 2)){

            $sql = "select r.id,r.uid author_id,u.uname author_name,r.title,r.info,r.readCount,r.downCount,f.saveaddress,r.downCount,f.savepath,f.savename,f.savetype,r.time cTime
                    from ts_resource r
                    left join ts_user u on u.uid=r.uid
                    inner join ts_resource_file f on f.id=r.id
                    where $conditions 1=1
                    order by r.time desc
                    limit $limit
                    ";
            $data = M('')->query($sql);
            if($who == 3 && $_REQUEST['category'] == 1){

                if(service('ForeAdmin')->isAuditApp('prepare')){
                    $status = "p.state=1";
                }else{
                    $status = '1=1';
                }

                $sql = "select p.id,p.uid author_id,u.uname author_name,p.title,p.readCount,p.saveaddress,p.savepath,p.savename,p.mtime cTime
                        from ts_prepare p
                        inner join ts_user u on u.uid=p.uid
                        where $c p.status=1 and $status
                        ";
                $prepare = M('')->query($sql);

                $sql = "select p.id,p.uid author_id,u.uname author_name,p.title,p.readCount,p.saveaddress,p.savepath,p.savename,p.mtime cTime
                        from ts_prepare_chapter p
                        inner join ts_user u on u.uid=p.uid
                        where $c p.status=1 and $status
                        ";
                $chapter = M('')->query($sql);
                $data = $data?$data:array();
                $prepare = $prepare?$prepare:array();
                $chapter = $chapter?$chapter:array();
                $data  = array_merge($data,$chapter,$prepare);
                unset($chapter);
                unset($prepare);
            }
        }

        //资源搜索
        if($who==5 && ($keyword=t($_REQUEST['keyword'],true,ENT_QUOTES))){
            $url = siteUrl(array('who'=>5,'keyword'=>$keyword));
            $sql = "select r.id,r.uid author_id,u.uname author_name,r.title,r.info,r.readCount,r.downCount,f.saveaddress,r.downCount,f.savepath,f.savename,f.savetype,r.time cTime
                    from ts_resource r
                    left join ts_user u on u.uid=r.uid
                    inner join ts_resource_file f on f.id=r.id
                    where (r.title like '%$keyword%') or (r.info like '%$keyword%') or (u.uname like '%$keyword%')
                    order by r.time desc
                    limit $limit
                    ";
            $data = M('')->query($sql);
        }


        //我收藏的资源
        if($who == 4){
            $sql = "select r.id,r.uid author_id,u.uname author_name,r.title,r.info,r.readCount,f.saveaddress,f.savepath,f.savename,f.savetype,r.time cTime
                    from ts_resource r
                    left join ts_user u on u.uid=r.uid
                    inner join ts_resource_file f on f.id=r.id
                    inner join ts_favorite a on r.id=a.fid and a.appname='resource' and a.status=1
                    where $conditions a.favuid=$this->mid
                    order by cTime desc
                    limit $limit
                    ";
            $data = M('')->query($sql);
        }

        $path = SITE_URL.'/api/mobileViews/public/image/';
        foreach ($data as $k=>$v) {
            if(!$v['savetype']){
                $v['savetype'] = $data[$k]['sayetype'] = '.'.array_pop(explode('.',$v['savename']));
            }

            if(!@fopen($v['saveaddress'].$v['savepath'].$v['savename'],'r')){
                $data[$k]['saveaddress']=$data[$k]['savepath']=$data[$k]['savename']='';
            }

            $data[$k]['cTime'] = putTime($v['cTime']);
            switch($v['savetype']){

                case '.doc':
                case '.docx':
                    $data[$k]['img'] = $path.'r-word.png';
                    break;

                case '.xls':
                case '.xlsx':
                    $data[$k]['img'] = $path.'r-xls.png';
                    break;

                case '.ppt':
                case '.pptx':
                    $data[$k]['img'] = $path.'r-ppt.png';
                    break;

                case '.jpg':
                case '.jpeg':
                case '.bmp':
                case 'png':
                    $data[$k]['img'] = $path.'r-pic.png';
                    break;

                case '.pdf':
                    $data[$k]['img'] = $path.'r-pdf.png';
                    break;

                case '.chm':
                    $data[$k]['img'] = $path.'r-chm.png';
                    break;

                default :
                    $data[$k]['img'] = $path.'r-exe.png';
            }
        }
        if($_POST['pc']==1){
            return $data;
        }
        $this->render(array('data'=>$data,'url'=>$url),true,'common','common/list-resource',array('common-list'));
    }

}