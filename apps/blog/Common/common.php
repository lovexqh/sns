<?php
function this_tags_html($content){
	$content=preg_replace("/<(\/?style.*?)>/si","",$content); //过滤style标签
	$content=preg_replace("/<(\/?class.*?)>/si","",$content); //过滤style标签
	return $content;
}
