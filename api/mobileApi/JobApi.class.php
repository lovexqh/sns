<?php
/**
 * Created by PhpStorm.
 * User: 徐程亮
 * Date: 13-10-23
 * Time: 下午4:03
 * To change this template use File | Settings | File Templates.
 */
class JobApi extends Api{
    /**
     *+---------------------------------------------------
     *jobType—工作的类型
     *+---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-10-29 下午4:29
     *+----------------------------------------------------
     */
    function jobType(){
        if($data = M('job_type')->findAll()){
            return $data;
        }else{
            return array();
        }
    }

    /**
     *+---------------------------------------------------
     *jobDetail—信息详情
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-10-29 下午1:20
     *+----------------------------------------------------
     */
    function jobDetail(){
        //信息id
        $id = (int)$_REQUEST['id']?(int)$_REQUEST['id']:exit;
        //获取数据
        $m = M('job');
        $data = $m
        ->field("{$this->db_prefix}job.id,{$this->db_prefix}job.title,{$this->db_prefix}job.meettime,{$this->db_prefix}job.meetplace,{$this->db_prefix}job.deadline,{$this->db_prefix}job.contact,{$this->db_prefix}job.tel,{$this->db_prefix}job_apply.status applyed,{$this->db_prefix}job.num,{$this->db_prefix}job.apply,{$this->db_prefix}job.content,{$this->db_prefix}job.publishdept,{$this->db_prefix}job_type.name")
            ->join("{$this->db_prefix}job_type on {$this->db_prefix}job_type.id={$this->db_prefix}job.type")
            ->join("{$this->db_prefix}job_apply on {$this->db_prefix}job_apply.jobid={$this->db_prefix}job.id and {$this->db_prefix}job_apply.uid={$this->mid}")
            ->where("{$this->db_prefix}job.isDel=0 and {$this->db_prefix}job.id=$id")
            ->findAll();
        //整理数据方便显示
        if($data[0]['meettime']){
            $data[0]['after'] = putTime($data[0]['meettime']);
            $data[0]['meettime'] = date("Y-m-d H:i",$data[0]['meettime']);
        }
        if($data[0]['deadline'] && $data[0]['deadline'] < time()){
            $data[0]['past'] = 1;
        }
        $data[0]['deadline'] && $data[0]['deadline'] = date("Y-m-d H:i",$data[0]['deadline']);
        $arr = array(
            'data' => $data,
            'applyUrl' => siteUrl(array('uid'=>$this->mid,'id'=>$id),'joinJob'),
        );
        //渲染模版
        $this->render($arr,true,'zepto','common/job-detail',array('common-show','job-detail'));
    }

    /**
     *+---------------------------------------------------
     *jobList—就业列表
     *+---------------------------------------------------
     * @return array|int
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-10-29 上午9:30
     *+----------------------------------------------------
     */
    function jobList(){
        //分类id 不传表示获取全部
        $id = $_REQUEST['id'];
        //组装查询条件
        $condition = '';
        if($id){
            $id = (int)$id;
            $condition = ' and '.$this->db_prefix.'job.type='.$id;
        }
        $limit = getLimit(false,10);
        //获取数据
        $data = M('job')
            ->field("{$this->db_prefix}job.id,{$this->db_prefix}job.title,{$this->db_prefix}job.meettime,{$this->db_prefix}job.meetplace,{$this->db_prefix}job.deadline,{$this->db_prefix}job.contact,{$this->db_prefix}job.tel,{$this->db_prefix}job_apply.id applyed,{$this->db_prefix}job.num,{$this->db_prefix}job.apply,{$this->db_prefix}job.content,{$this->db_prefix}job.publishdept,{$this->db_prefix}job.publishtime,{$this->db_prefix}job_type.name")
            ->join("{$this->db_prefix}job_type on {$this->db_prefix}job_type.id={$this->db_prefix}job.type")
            ->join("{$this->db_prefix}job_apply on {$this->db_prefix}job_apply.jobid={$this->db_prefix}job.id and {$this->db_prefix}job_apply.uid={$this->mid}")
            ->where("{$this->db_prefix}job.isDel=0".$condition)
            ->order("{$this->db_prefix}job.publishtime desc")
            ->limit($limit)
            ->findAll();

//        处理数据
        foreach ($data as $k=>$v) {
            if($v['deadline'] && ($v['deadline'] < time())){
               $data[$k]['past'] = 1;
            }
            $v['meettime'] && $data[$k]['meettime'] = date('Y-m-d H:i',$v['meettime']);

            $v['deadline'] && $data[$k]['deadline'] = date('Y-m-d H:i',$v['deadline']);
        }

//        foreach($data as $k=>$v){
//            $time = $v['meettime'];
//            $v['meettime'] = $data[$k]['meettime'] = date("m.d H:i",$v['meettime']);
//            $v['after'] = $data[$k]['after'] = putTime($time);
//            if($v['deadline'] < time()){
//                $v['past'] = 1;
//                $arr[] = $v;
//                unset($data[$k]);
//            }
//        }
//        $new = array_reverse($data);
//        unset($data);
//        $data = array_merge($new,$arr);
//        unset($new);
//        unset($arr);

        if($_POST['pc'] == 1){
            if($data){
                return $data;
            }else{
                return 0;
            }
        }
        $arr = array(
            'data' => $data,
            'url'  => siteUrl(array('id'=>$id)),
            'applyUrl' => siteUrl(array('uid'=>$this->mid),'joinJob')
        );
        //渲染
        $this->render($arr,true,'zepto','common/list-job',array('job-list'));
    }

    /**
     *+---------------------------------------------------
     *joinJob—申请加入
     *+---------------------------------------------------
     * @return int
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-10-29 下午10:33
     *+----------------------------------------------------
     */
    function joinJob(){
        //用户id
        $uid = (int)$_REQUEST['uid']?(int)$_REQUEST['uid']:exit;
        //信息id
        $id = (int)$_REQUEST['id']?(int)$_REQUEST['id']:exit;
        $map = array();
        $map['uid'] = $uid;
        $map['jobid'] = $id;
        $map['addtime'] = time();
        $map['statu'] = 0;
        if(M('job_apply')->add($map)){
            //成功
            return 1;
        }else{
            //失败
            return 0;
        }
    }
}