<?php
$arr = array(
    'jian' => array(
        'num' => 4,
        'info' => '新版本提升了整体运行效率，修复上一版本bug',
        'url' => SITE_URL.'/download/Android/edusns.apk',
    )
);
if($name = $_REQUEST['name']){
    if($info = $arr["$name"]){
        if($info['num'] != $_REQUEST['num']){
            echo  json_encode(array('num'=>$info['num'],'info'=>$info['info'],'url'=>$info['url']));
        }else{
            echo 1;
        }
    }
}