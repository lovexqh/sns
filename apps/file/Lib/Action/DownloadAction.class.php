<?php

class DownloadAction extends Action {

    public function index() {
//        $str = 'eee.rar';
//        service('File')->curlFileDownload($str);
        
        $this->display();
//        get_headers($url);
//        print_r(apache_request_headers());
//        exit;
    }

    public function upload(){
	service("File")->fileUpload($_FILES);
    }

}

?>
