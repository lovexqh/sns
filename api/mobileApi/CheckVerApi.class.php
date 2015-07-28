<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-5-22
 * Time: 上午10:36
 * To change this template use File | Settings | File Templates.
 */
class CheckVerApi extends Api{
    function androidVer(){
        if(!$_REQUEST['ver'] || !$_REQUEST['nickname'])exit;
        $name = t($_REQUEST['nickname']);
        $map['status'] = '1';
        $map['nickname'] = $name;
        $list = M('android_ver_manage')->where($map)->field('ver,nickname,downloadurl,updateinfo,status')->order('id desc')->find();
        if($_REQUEST['ver'] != $list['ver'] && $list){
            return ($list);
        }else{
            return 1;
        }
    }
}