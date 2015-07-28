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

