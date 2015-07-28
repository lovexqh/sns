<?php
/**
 * Created by PhpStorm.
 * User: 徐程亮
 * Date: 13-11-14
 * Time: 上午9:06
 * To change this template use File | Settings | File Templates.
 */
class PushApi extends Api{

    function message(){
        return array('name'=>'Tom','message'=>'How are you');
    }

}