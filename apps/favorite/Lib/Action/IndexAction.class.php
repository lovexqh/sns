<?php
class IndexAction extends Action
{
    // 发现社团
    public function index()
    {
    	$appname = isset($_GET['appname']) ? $_GET['appname'] : '';
    	$favoritelist = model('Favorite')->list_favorite_all($appname);
    	$applist = model('favorite')->list_user_favorite_app();
        $this->setTitle("我的收藏");
        $this->assign('appname',$appname);
        $this->assign('html',$favoritelist['html']);
        $this->assign('favoritelist',$favoritelist['data']);
        $this->assign('applist',$applist);
        $this->display();
    }

}