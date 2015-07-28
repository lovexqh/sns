<?php
$callback = $_REQUEST['callback'];

// Create the output object.
$output1 = array('title' => 'Apple1', 'createTime' => 'Banana1');
$output2 = array('title' => 'Apple2', 'createTime' => 'Banana2');
$output3 = array('title' => 'Apple3', 'createTime' => 'Banana3');
$output4 = array('title' => 'Apple4', 'createTime' => 'Banana4');
$output5 = array('title' => 'Apple5', 'createTime' => 'Banana5');
$output = array($output1, $output2, $output3, $output4, $output5);

//start output
if ($callback) {
    header('Content-Type: text/javascript');
    echo $callback . '(' . json_encode($output) . ');';
} else {
    header('Content-Type: application/x-json');
    echo json_encode($output);
}
?>