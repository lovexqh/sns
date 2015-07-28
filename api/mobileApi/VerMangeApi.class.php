<?php
/**
 * Class VerMangeApi 社区版本管理接口
 */
class VerMangeApi extends Api{

    /**
     *---------------------------------------------------
     *_initialize—类初始化时调用限制ip
     *---------------------------------------------------
     * @author  徐程亮
     *
     *---------------------------------------------------
     *创建时间：
     *----------------------------------------------------
     */
    function _initialize(){
        if($_POST['from']!='mobile-group' || $_GET['c']!=9){
            header("Location:".SITE_URL);
            exit('Bad Way!');
        }
    }

    /**
     *+---------------------------------------------------
     *apkUpload—apk上传
     *+---------------------------------------------------
     * @author  徐程亮
     * 成功后自动生成下载地址
     * 上传失败是get方式传回错误信息 //message=error
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function apkUpload(){
        $back = $_GET['back'];
        require_cache(SITE_PATH."/addons/libs/UploadFile.class.php");
        $name = explode('.',$_FILES['apk']['name']);
        $upload = new UploadFile(); // 实例化上传类
        $upload->maxSize  = 20 * 1024 * 1024; // 设置附件上传大小20M
        $upload->allowExts  = array('apk'); // 设置附件上传类型
        $upload->savePath =  SITE_PATH.'/download/'; // 设置附件上传目录
        $upload->saveRule =  date('Y-m-d-H-i-s',time()).'_'.$name[0]; // 设置附件上传目录
        if(!$upload->upload()) { // 上传错误提示错误信息
            $message=$upload->getErrorMsg();
            header('Location:http://openapi.educomm.cn/index.php?r=VerMange/Show&message='.$message.'&back='.$back);
//             $upload->getErrorMsg();
        }else{ // 上传成功获取上传文件信息
            $info =  $upload->getUploadFileInfo();
            $name = M('android_ver_manage')->where(array('id'=>(int)$_POST['id']))->getField('name');
            $save['downloadurl'] = SITE_URL.'/download/'.$name.'.apk';
            $save['apkname'] = $info[0]['savename'];
            $save['savepath'] = $info[0]['savepath'].$info[0]['savename'];
            $down = $info[0]['savepath'].$name.'.apk';
            if(file_exists($down)){
                unlink($down);
                copy($save['savepath'],$down);
            }else{
                copy($save['savepath'],$down);
            }
            if(M('android_ver_manage')->where(array('id'=>(int)$_POST['id']))->save($save)){
//                var_dump($info);
                header('Location:http://openapi.educomm.cn/index.php?r=VerMange/Show&back='.$back);
//                echo "新增成功！";
            }
        }
    }

    /**
     *+---------------------------------------------------
     *getList—获取版本列表
     *+---------------------------------------------------
     * @author  徐程亮
     * limit 以字符串形式返回 //'0,10'
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */

    function getList(){
        $limit = $_POST['limit'];
        $data = M('android_ver_manage')->limit($limit)->findAll();
        $count = M('android_ver_manage')->count();
//        $data = D('Mobile')->getList();
        if(!$data)$data=array();
        if(!$count)$count=0;
        $data = array('total'=>$count,'rows'=>$data);
        if($data){
            echo 'message^^:'.json_encode($data);
        }else{
            echo 'message^^:'.json_encode(array('total'=>0,'rows'=>array()));
        }
        exit;
    }

    /**
     *+---------------------------------------------------
     *update—更新和新增版本信息接口
     *+---------------------------------------------------
     * @author  徐程亮
     * 返回修改成功的条数
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function update(){
        $i = 0;
        if($_POST['update']){
            $data = $_POST['update'];
            foreach ($data as $v) {
                if(M('android_ver_manage')->where(array('id'=>$v['id']))->save($v)){
                    $i++;
                }
            }

        }elseif($_POST['insert']){
            $data = $_POST['insert'];
            foreach ($data as $v) {
                if(M('android_ver_manage')->add($v)){
                    $i++;
                }
            }

        }
        echo 'message^^:'.$i;
        exit;
    }

    /**
     *+---------------------------------------------------
     *deleteInfo—删除版本信息
     *+---------------------------------------------------
     * @author  徐程亮
     * @id 多个id字符串形式 //'1,2,3'
     * 返回删除信息条数
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function deleteInfo(){
        $id = t($_POST['id']);
        $data = M('android_ver_manage')->where('id in('.$id.')')->field('savepath')->findAll();
        foreach ($data as $v) {
            if(is_file($v['savepath'])){
                @unlink($v['savepath']);
            }
        }
        if($id = M('android_ver_manage')->delete($id)){
            echo 'message^^:'.$id;
        }
        exit;
    }
}