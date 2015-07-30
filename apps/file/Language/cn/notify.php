<?php 
return  array(
        'file_warn' => array(
		'title' => '警告',
                'body'=> "您发的'{$body}'文档因含有敏感词语被警告。",
                'other' => ' <a href="'.$url.'" target="_blank">' . $url . '</a></title>',
	),
        'file_warn_cancel' => array(
		'title' => '警告',
                'body'=> "您发的'{$body}'文档已被取消警告。",
	),
        'file_delete' => array(
		'title' => '通知',
                'body'=> "您发的'{$body}'文档因含敏感词已被删除。",
	),
                        
);
?>