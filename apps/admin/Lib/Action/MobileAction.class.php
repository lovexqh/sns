<?php
/**
 * Created by PhpStorm.
 * User: 徐程亮
 * Date: 13-12-9
 * Time: 下午3:42
 * To change this template use File | Settings | File Templates.
 */
class MobileAction extends AdministratorAction {

    function mobileMange(){
        $this->display();
    }

    function apkUpload(){
        require_cache(SITE_PATH."/addons/libs/UploadFile.class.php");
        $name = explode('.',$_FILES['apk']['name']);
        $upload = new UploadFile(); // 实例化上传类
        $upload->maxSize  = 20 * 1024 * 1024; // 设置附件上传大小20M
        $upload->allowExts  = array('apk'); // 设置附件上传类型
        $upload->savePath =  SITE_PATH.'/download/'; // 设置附件上传目录
        $upload->saveRule =  date('Y-m-d-H-i-s',time()).'_'.$name[0]; // 设置附件上传目录
        if(!$upload->upload()) { // 上传错误提示错误信息
            echo $this->error($upload->getErrorMsg());
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
                $this->success("新增成功！");
            }
        }
    }

    function getList(){
        $limit = (int)$_GET['start'].','.(int)$_GET['limit'];
        $data = M('android_ver_manage')->limit($limit)->findAll();
        $count = M('android_ver_manage')->count();
//        $data = D('Mobile')->getList();
        if(!$data)$data=array();
        $data = array('total'=>$count,'rows'=>$data);
        echo json_encode($data);
        unset($data);
    }

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
        echo $i;
    }

    function deleteInfo(){
        $id = t($_GET['id']);
        $data = M('android_ver_manage')->where('id in('.$id.')')->field('savepath')->findAll();
        foreach ($data as $v) {
           if(is_file($v['savepath'])){
               @unlink($v['savepath']);
           }
        }
        if($id = M('android_ver_manage')->delete($id)){
            echo $id;
        }
    }

}