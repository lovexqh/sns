<?php

class FileStatisticsModel extends Model {

    public function statistics() {
        $result = array();
        $app_alias = getAppAlias('post');
        $db_prefix = C('DB_PREFIX');
        $fileInfoList = M()->field("f.pid,t.name,count(f.id) AS count,sum(f.read_count) AS read_count,sum(f.down_count) AS down_count")
                ->table("{$db_prefix}file AS f left join {$db_prefix}file_type AS t on f.pid = t.id")
                ->where("f.status = 0")
                ->group("f.pid")
                ->select();
        foreach ($fileInfoList as $value) {
            if(!empty($value['name'])){
                $result[$value['name']] = $value['count'].' ----阅读数总数：'.$value['read_count'].' ----下载总数：'.$value['down_count'];
            }
        }
        $posterDao = M('file');
        $allCount = $posterDao->field('COUNT(*) AS poster')->where('status = 0')->find();
        $result['资料总数'] = $allCount['poster'];
        return $result;
    }

}