<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-7-10
 * Time: 下午4:07
 * To change this template use File | Settings | File Templates.
 */

/**
 *+---------------------------------------------------
 *clearText——剔除所有html标签
 *+---------------------------------------------------
 * @param $str
 * @return mixed
 * @author  徐程亮
 *+---------------------------------------------------
 *创建时间：
 *+----------------------------------------------------
 */
function clearText($str){
    return str_replace('&nbsp;',' ',strip_tags(htmlspecialchars_decode($str)));
}

function safeSql($value){
    // 去除斜杠
    if (get_magic_quotes_gpc())
    {
        $value = stripslashes($value);
    }
    $value = str_replace(' ','',strip_tags(htmlspecialchars_decode($value)));

// 如果不是数字则加引号
    if (!is_numeric($value))
    {
        $value =  mysql_real_escape_string($value);
    }
    return $value;
}

/**
 *+---------------------------------------------------
 *getLimit——获取limit
 *+---------------------------------------------------
 * @return string
 * @author  徐程亮
 *+---------------------------------------------------
 *创建时间：13-7-11 上午9:53
 *+----------------------------------------------------
 */
function getLimit($js=false,$count=20){
    $limit = (int)$_REQUEST['limit'];
    $pageNo = (int)$_REQUEST['pageNo'];
    $start = ($pageNo-1) * $limit;
    if($limit && $js){
        $start +=10;
    }
    if(!$pageNo) $start  = 0;
    if(!$limit) $limit = $count;
    return $start.','.$limit;
}

/**
 *+---------------------------------------------------
 *p——格式化输出数组
 *+---------------------------------------------------
 * @param $arr
 * @author  徐程亮
 *+---------------------------------------------------
 *创建时间：
 *+----------------------------------------------------
 */
function p($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

function getUserImg($arr=array(),$k=null){
    //获取用户头像
    $data = array();
    if($k){
        $uid = $k;
    }else{
        $uid = 'author_id';
    }
    foreach($arr as $v){
        $img_path = SITE_PATH.'/data/uploads/avatar/'.$v[$uid].'/small.jpg';
        //http://esn.ruijie-grid.com/data/uploads/avatar/122/middle.jpg
        if(file_exists($img_path))
            $v['author_img'] = SITE_URL.'/data/uploads/avatar/'.$v[$uid].'/big.jpg';
        else
            $v['author_img'] = SITE_URL.'/public/themes/edustyle/images/user_pic_middle.gif';
        $data[] = $v;
    }
    return $data;
}

function siteUrl($arr=array(),$act=ACTION_NAME,$mod=MODULE_NAME){
    $url = SITE_URL.'/index.php?app=api&mod='.$mod.'&act='.$act.'&oauth_token='.$_REQUEST['oauth_token'].'&oauth_token_secret='.$_REQUEST['oauth_token_secret'];
    foreach($arr as $k=>$v){
        $url.='&'.$k.'='.$v;
    }
    return $url;
}

/**
 *+---------------------------------------------------
 *checkRules——强制类型检查，不通过直接结束脚本
 *+---------------------------------------------------
 * @param array $arr array(array('被检查项','类型','是否为必填','安全字符'))
 * #############################注意：被检查项为必填时，被检查项为假时也被视为不合法例如0#################################
 * @author  徐程亮
 *+---------------------------------------------------
 *创建时间：13-6-26 下午2:51
 *+----------------------------------------------------
 */
function checkRules($arr=array()){
    foreach($arr as $v){
        //array('被检查项','类型','是否为必填','安全字符')
        //若为必填项
        if($v['2']){
            //检查是否为空
            if($v[0]){
                if(! checkType($v[0],$v[1],$v[3]))
                    die('0');
            }else{
                die('0');
            }
            //若为选填项
        }else{
            if($v[0]){
                if(! checkType($v[0],$v['1'],$v[3]))
                    die('0');
            }
        }
    }
}

/**
 *+---------------------------------------------------
 *checkType——检查变量类型
 *+---------------------------------------------------
 * @param $v
 * @param $type
 * @param string $r
 * @return bool
 * @author  徐程亮
 *+---------------------------------------------------
 *创建时间：13-6-26 下午2:54
 *+----------------------------------------------------
 */
function checkType($v,$type,$r=array()){
    $a = true;
    switch($type){
        //检查是是否为正整数
        case 'int':
            if(!ctype_digit($v))
                $a = false;
            break;
        //检查是否为只包含a-z,0-9的字符，$r不为空时表示可允许字符
        case 'string':
            if($r){
                //剔除安全字符
                foreach($r as $str){
                    $v = str_replace($str,'',$v);
                }
            }
            if(!ctype_alnum($v))
                $a = false;
            break;
    }
    return $a;
}

function putTime($t,$limit = 'y'){
    $time = time()-$t;
    if($time < 0){
        $time = $t-time();
        $after = true;
    }
//    $time = $time / 60 /60 /21 /;
    if($time >= 60){
        if(($time/60) >=60){

            if(($time/60/60) >=24){

                $days = date("t",strtotime($t));
                if(($time/60/60/24) >= $days){

                    if(($time/60/60/24/30) >= 12){
                        return $time = date("Y-m-d H:i:s",$t);
                    }else{
                        if($limit == 'm'){
                            return $time = date("y-m-d H:i",$t);
                        }else{
                            $time = $time /60 /60 /24 /30;
                            $str = '个月';
                        }
                    }
                }else{
                    if($limit == 'd'){
                        return $time = date("y-m-d H:i",$t);
                    }else{
                        $time = $time /60 /60 /24;
                        $str = '天';
                    }
                }
            }else{
                $time = $time /60 /60;
                $str = '小时';
            }

        }else{
            $time = $time / 60;
            $str = '分钟';
        }
    }else{
        $str = '秒钟';
    }
    if($after){
        return round($time).$str.'后';
    }else{
        return round($time).$str.'前';
    }
}

function replaceFace($content){
    $content = preg_replace_callback("/(?:#[^#]*[^#^\s][^#]*#|(\[.+?\]))/is",replaceEmot,$content);
    return $content;
}

//微博处理图片
function pic($data,$lazy=''){
    if(count($data) > 4){
        foreach ($data as $v) {
            if(is_array($v)){
                if($lazy){
                    ?>
                    <img class="pic lazy checkpic" src="<?php echo $lazy;?>" data-url-p="<?php echo $data['picurl'].$v['picurl'];?>" data-url="<?php echo $data['thumburl'].$v['thumburl'];?>">
                <?php
                }else{
                    ?>
                    <img class="pic lazy checkpic"  data-url-p="<?php echo $data['picurl'].$v['picurl'];?>" src="<?php echo $data['thumburl'].$v['thumburl'];?>">
                <?php

                }
            }
        }
    }else{
        if($lazy){
            ?>
            <img class="pic lazy checkpic" src="<?php echo $lazy;?>" data-url-p="<?php echo $data['picurl'];?>" data-url="<?php echo $data['thumburl'];?>" >
        <?php
        }else{
            ?>
            <img class="pic lazy checkpic"  data-url-p="<?php echo $data['picurl'];?>" src="<?php echo $data['thumburl'];?>" >
        <?php
        }
    }
}

function ob_gzip($content){
    if(!headers_sent() && extension_loaded("zlib") && strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip")){
        $content = gzencode($content,5);
        header("Content-Encoding: gzip");
        header("Vary: Accept-Encoding");
        header("Content-Length: ".strlen($content));
    }
    return $content;
}


function outputCache($module,$action,$filename){
    ob_start();
    call_user_func(array($module,$action));
    $html = ob_get_contents();
    ob_end_clean();
    outputByGzip($html);
    newCacheFile($filename,$html);
}

function cacheFileDeadline($file,$config){
    $time = time()-filemtime($file);
    $config = $config === 0 ? 0 : 8;
    if($time > $config){
        return false;
    }else{
        return true;
    }
}

//
function cacheHtml($module,$action){
    ob_start();
    call_user_func(array($module,$action));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

//压缩输出
function outputByGzip($source,$type=0){
    ob_start('ob_gzip');
    if($type == 1){
        include $source;
    }else{
        echo $source;
    }
    ob_end_flush();
}

function newCacheFile($filename,$html){
    //判断缓存目录是否存在不存在创建
    if(!file_exists (MOBILE_CACHE_DIR)){
        if(!mkdir(MOBILE_CACHE_DIR,777)){
            mkdir(RUNTIME_DIR,777);
            mkdir(MOBILE_CACHE_DIR,777);
        }
    }
    $file = MOBILE_CACHE_DIR.'/'.$filename.'.html';
    if($handle = fopen($file,'w+')){
        if(fwrite($handle,$html)){
            return $file;
        }else{
            return false;
        }
        fclose ($handle);
    }else{
        return false;
    }

}
//检测缓存文件是否存在
//function cacheFileExists($filename){
//    $file = MOBILE_CACHE_DIR.'/'.$filename.'.html';
//    if(file_exists($file)){
//        return $file;
//    }else{
//        return false;
//    }
//}

/**
 *+---------------------------------------------------
 *clearHtml—清除内部样式
 *+---------------------------------------------------
 * @param $content
 * @return mixed
 * @author  徐程亮
 *+---------------------------------------------------
 *创建时间：
 *+----------------------------------------------------
 */
function clearHtml($content){
    $content = strip_tags($content,"<div><p><img><br><span><em>");
    return preg_replace('/(style=\".*?\")/',' ',$content);
}

/**
 * @title  将获取的数据库键值赋给移动API指定的键值
 * @description  $data为获取的数据. $arrkey为键值对应关系,格式为$arrkey = array('apikey1' => 'dbkey1','apikey2' => 'dbkey2');
 * @param
 * @return
 * @author	xiawei 2014-2-8
 */
function getApiArray($data, $arrkey){
	foreach ($arrkey as $k => $v){
		$newarr[$k] = $data[$v];
	}
	return $newarr;
}