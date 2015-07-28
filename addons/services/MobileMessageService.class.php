<?php
/**
 * Created by PhpStorm.
 * User: 徐程亮
 * Date: 14-1-20
 * Time: 上午9:25
 * To change this template use File | Settings | File Templates.
 */
class MobileMessageService {
    //ios证书密码
    public $passphrase = 111111;

    /**
     *---------------------------------------------------
     *pushMessage— 推送消息(建议：多条消息一次性调用)
     *---------------------------------------------------
     * @param array $m
     * $m = array(
     *   'public' => array(            //公共消息公共
     *      'uid' =>'1,2,3'         // uid == 0 发送给所有人
     *      'message' =>'大家好'
     *   ),
     *   'private' => array(           //单独给某个用户的消息
     *         // uid => 'message'
     *          4 => '小明你好',
     *          5 => '小明你好',
     *          ……
     *    )
     * )
     * @param $obj
     * @author  徐程亮
     *---------------------------------------------------
     *创建时间：14-1-20 下午1:25
     *----------------------------------------------------
     */
    function pushMessage($m){
        //tokens
        $tokens = array('ios'=>array(),'android'=>array());
        //公共消息
        $public = $m['public'];
        //定制消息
        $private = $m['private'];
        unset($m);
        //群发消息并且支持个别用户定制发送
        if($public){
            //发送给所有人
            if($public['uid'] == 0){
                $res = M('mobile_push_tokens')->field('uid,token,type')->where('status=\'1\'')->findAll();
                //发送给指定人
            }elseif($public['uid']){
                $res = M('mobile_push_tokens')->field('uid,token,type')->where('status=\'1\' and uid in ('.$public['uid'].')')->findAll();
            }
        }
        //只有定制消息，没有公共消息
        if($private){
            $uid = '';
            foreach ($private as $k=>$v){
                //判断是否重复，防止重复查询
                if(strpos($public['uid'],(string)$k) !== false){
                    continue;
                }
                $uid .= $k.',';
            }
            if($uid){
                $uid = substr($uid,0,-1);
                //判断是否有有公共消息
                if($res){
                    $p = M('mobile_push_tokens')->field('uid,token,type')->where('status=\'1\' and uid in ('.$uid.')')->findAll();
                    $res = array_merge($p,$res);
                }else{
                    $res = M('mobile_push_tokens')->field('uid,token,type')->where('status=\'1\' and uid in ('.$uid.')')->findAll();

                }
            }
        }


        //token数组
        foreach ($res as $v) {
            //判断是否有定制消息
            if($private[$v['uid']]){
                //ios
                if($v['type'] == 1){
                    $tokens['ios'][] = array('token'=>$v['token'],'message'=>$private[$v['uid']]);
                    //android
                }elseif($v['type'] == 2){
                    $tokens['android'][] = array('token'=>$v['token'],'message'=>$private[$v['uid']]);
                }
            }else{
                if($v['type'] == 1){
                    $tokens['ios'][] = $v['token'];
                    $tokens['message'] = $public['message'];
                }elseif($v['type'] == 2){
                    $tokens['android'][] = $v['token'];
                    $tokens['message'] = $public['message'];
                }
            }
        }
        if($tokens['ios'])
            self::iosPush($tokens['ios'],$tokens['message']);

        if($tokens['android'])
            self::__androidPush($tokens['android'],$tokens['message']);

    }

    static function __androidPush($tokens,$message=''){

    }

    static function iosPush($tokens,$message=''){
        $ctx = stream_context_create();
        $pem = SITE_PATH . '/' . 'ck.pem';
        stream_context_set_option($ctx, 'ssl', 'local_cert',$pem);
        stream_context_set_option($ctx, 'ssl', 'passphrase', 111111);

// Open a connection to the APNS server
//这个为正是的发布地址
//$fp = stream_socket_client(“ssl://gateway.push.apple.com:2195“, $err, $errstr, 60, //STREAM_CLIENT_CONNECT, $ctx);
//这个是沙盒测试地址，发布到appstore后记得修改哦
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);

        echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default'
        );

// Encode the payload as JSON
        $payload = json_encode($body);

// Build the binary notification
//        $arr = array('3dad4f7c37354e26441a84f24fb7eb7701250d6c5bc979fdbcd44b2fc0a33e19','50bb2cbbe07ae771e10570109da8fc63ee9989823bf7e722ad78404c475d4421');
        foreach($tokens as $v){
            if(is_array($v)){
                $body['aps']['alert'] = $v['message'];
                $push = json_encode($body);
                $msg = chr(0) . pack('n', 32) . pack('H*', $v['token']) . pack('n', strlen($push)) . $push;
            }else{
                $msg = chr(0) . pack('n', 32) . pack('H*', $v) . pack('n', strlen($payload)) . $payload;
            }
            $result = fwrite($fp, $msg, strlen($msg));
            echo $result.'<br>';
            if (!$result)
                echo 'Message not delivered<br>';
            else
                echo 'Message successfully delivered<br>';
        }
        fclose($fp);
    }

}