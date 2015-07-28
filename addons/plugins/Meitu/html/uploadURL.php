<?php

define('SITE_PATH', dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/');
$savePath = SITE_PATH . '/data/uploads/temp';
$filename = $_GET['filename'] . '.jpg';
@move_uploaded_file($_FILES['pic1']['tmp_name'], $savePath . '/' . $filename);

