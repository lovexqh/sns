<?php
/**
 * Created by PhpStorm.
 * User: 徐程亮
 * Date: 14-1-24
 * Time: 上午11:04
 * To change this template use File | Settings | File Templates.
 */
class WebViewApi extends Api{
    public $apps = array(
        'poster' => array('mod'=>'mod','act'=>'act'),
    );
    function show(){
        var_dump($_POST['p']);
        exit;
        $path = explode('|',$_GET['paht']);
        $mod = $this->apps['adhibition']['mod'];
        $act = $this->apps['adhibition']['act'];
        $url = siteUrl(array(),$act,$mod);
    }
}