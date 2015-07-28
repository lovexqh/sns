<?php
/**
 * Created by PhpStorm.
 * User: 徐程亮
 * Date: 13-11-14
 * Time: 上午9:06
 * To change this template use File | Settings | File Templates.
 */
class MessagePushApi extends Api{

    function message(){
        $m = array(
            'public' => array(
                'uid' => '1,2,3',
                'message' => 'test1',
            ),
            'private' => array(
                '2' => 'test0'
            ),
        );
        require_cache(SITE_PATH . '/addons/services/MobileMessageService.class.php');
        MobileMessageService::pushMessage($m);
    }

}