<?php
class PhoneBookApi extends Api{

    function getList(){
        $param['namecn'] = null;
        $param['empstate'] = null;
        $data = getEmployeeAndDept($param);
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }

}
