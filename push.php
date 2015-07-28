<?php
// 这里是我们上面得到的deviceToken，直接复制过来（记得去掉空格）
$deviceToken = '50bb2cbbe07ae771e10570109da8fc63ee9989823bf7e722ad78404c475d4421';

// Put your private key's passphrase here:
$passphrase = '111111';

// Put your alert message here:
$message = '我们更新了啦';

////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
$pem = dirname(__FILE__) . '/' . 'ck.pem';
stream_context_set_option($ctx, 'ssl', 'local_cert',$pem);
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

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
$arr = array('3dad4f7c37354e26441a84f24fb7eb7701250d6c5bc979fdbcd44b2fc0a33e19','50bb2cbbe07ae771e10570109da8fc63ee9989823bf7e722ad78404c475d4421');
foreach($arr as $v){
    $msg = chr(0) . pack('n', 32) . pack('H*', $v) . pack('n', strlen($payload)) . $payload;
    $result = fwrite($fp, $msg, strlen($msg));
}


if (!$result)
    echo 'Message not delivered' . PHP_EOL;
else
    echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server

fclose($fp);