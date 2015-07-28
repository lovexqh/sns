<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <!--对可视区进行矫正，通知浏览器使用移动设备的宽度作为可视宽度-->
    <meta name="viewport" content="width=device-width,initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta content="telephone=no" name="format-detection" />
    <link rel="stylesheet" href="<?php echo API_PUBLIC_PATH;?>css/<?php echo strtolower(MODULE_NAME.'-'.ACTION_NAME);?>.css" rel="stylesheet" />
    <script src="<?php echo API_LIB_PATH;?>jquery.mobile-1.3.1/jquery.js"></script>
</head>
<body>
<?php include_once $view_path;?>
<script src="<?php echo API_LIB_PATH;?>hammer/jquery.hammer.min.js"></script>
</body>
</html>
