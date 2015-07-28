<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-10-11
 * Time: 上午10:14
 * To change this template use File | Settings | File Templates.
 */
class FavoriteApi extends Api{

    private $config = array(
        'blog' => array('action'=>'Index','appconfig'=>'blog','appname'=>'blog','method'=>'show'),
        'video' => array('action'=>'Index','appconfig'=>'video','appname'=>'video','method'=>'video'),
        'resource' => array('action'=>'Index','appconfig'=>'resource','appname'=>'resource','method'=>'showresource'),
        'photo' => array('action'=>'Index','appconfig'=>'photo','appname'=>'photo','method'=>'photo'),
    );
    /**
     *+---------------------------------------------------
     *addFavorite—添加收藏
     *+---------------------------------------------------
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-10-11 上午10:15
     *+----------------------------------------------------
     */
    function addFavorite(){
        $data = array();
        switch($_REQUEST['type']){
            case 'blog':
                $data = $this->config['blog'];
                break;
            case 'video':
                $data = $this->config['video'];
                break;
            case 'resource':
                $data = $this->config['resource'];
                break;
            case 'photo':
                $data = $this->config['photo'];
                break;
        }
        $data['fid'] = (int)$_REQUEST['id']?(int)$_REQUEST['id']:exit;
        $id = model('Favorite')->add_favorite($data);
        if($id){
            return $id;
        }else{
            return 0;
        }
    }

    /**
     *+---------------------------------------------------
     *delFavorite——删除收藏
     *+---------------------------------------------------
     * @return int
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-10-11 下午1:20
     *+----------------------------------------------------
     */
    public function delFavorite(){
        $id = (int)$_REQUEST['id']?(int)$_REQUEST['id']:exit;
        $id = model('Favorite')->del_favorite($id);
        if($id){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     *+---------------------------------------------------
     *myFavorite——我收藏过的app
     *+---------------------------------------------------
     * @return mixed
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：13-10-11 下午1:31
     *+----------------------------------------------------
     */
    function myFavorite(){
        $list = model('Favorite')->list_user_favorite_app();
        return $list;
    }


    /**
     *+---------------------------------------------------
     *checkFavorite——检测应用是否被收藏
     *+---------------------------------------------------
     * @return int
     * @author  徐程亮
     *+---------------------------------------------------
     *创建时间：
     *+----------------------------------------------------
     */
    function checkFavorite(){
        $data = array();
        switch($_REQUEST['type']){
            case 'blog':
                $data = $this->config['blog'];
                break;
            case 'video':
                $data = $this->config['video'];
                break;
            case 'resource':
                $data = $this->config['resource'];
                break;
            case 'photo':
                $data = $this->config['photo'];
                break;
        }
        $data['fid'] = (int)$_REQUEST['id']?(int)$_REQUEST['id']:exit;
        if($id = M('favorite')->where($data)->getField('id')){
            return $id;
        }else{
            return 0;
        }
    }

}






















