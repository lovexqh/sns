<?php
class VersionApi extends Api{
    //版本号
    private $num=2;
    private $name='jijian';
    function check(){
        if($this->num != $_REQUEST['num'] && $this->name==$_REQUEST['name']){
            $info = '新版本提升了整体运行效率';
            $url = SITE_URl.'/download/Android/edusns.apk';
            return array('num'=>$this->num,'info'=>$info,'url'=>$url);
        }elseif($this->num == $_REQUEST['num'] && $this->name==$_REQUEST['name']){
            return 1;
        }else{
            return 0;
        }
    }
}