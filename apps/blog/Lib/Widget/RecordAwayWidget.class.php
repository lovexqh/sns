<?php
    /**
     * Pub_RecordAwayWidget
     * 归档widget
     *
     * @uses BaseWiget
     * @package Widget
     * @version $id$
     * @copyright 2013-05-24 孙晓波
     * @author 孙晓波 <xiaobosun@gridinfo.com.cn>
     */
    class RecordAwayWidget extends Widget{

        private $data;
        /**
         * render
         *
         * @param mixed $data
         * @access public
         * @return void
         */
        public function render( $data ){
        	global $ts;
            $this->data = $data;

            $date = date( 'Ym',time() );
            if( !isset($data['limit']) ){
                $date = self::paramData( $date,6,$data['tableName']);
            }else{
                $date = self::paramData( $date,$data['limit'],$data['tableName']);
            }
            $data['date'] = $date;
			$data['alldate'] = '全部时间';
			$data['title'] = $ts['app']['app_alias'].'存档';
            return $this->renderFile( 'RecordAwayWidget',$data );
        }


        /**
         * paramData
         * 解析日期
         * @param mixed $date 当前时间（200905格式）
         * @param mixed $object 需要查询数据的object名.
         * @static
         * @access private
         * @return void
         */
        private  function paramData( $date,$limit = 6,$tableName){
			//获取该用户第一次发布信息的日期
			$uid = $this->data['condition']['uid'];
			$sql = "SELECT cTime FROM {$tableName} WHERE uid = {$uid} ORDER BY cTime ASC LIMIT 1";	
			$dao = D( 'Smile' );
			$result = $dao->query( $sql );
            $fcTime = $result[0]['cTime'];
            //start year
            $year=date( "y",$fcTime);
            
            $endyear = '2013';
            
            $allresult=M("blog")->field("id,DATE_FORMAT(FROM_UNIXTIME(cTime),'%Y-%m') as cTime")->where("uid='$uid'")->group("DATE_FORMAT(FROM_UNIXTIME(cTime),'%Y-%m')")->findAll();
            $da = array();
            $unique="";
            foreach($allresult as $res){
                $_month = array();
                $ym = explode('-',$res['cTime']);
                $da[$ym[0]][$ym[1]] = array(1);
            }
            //echo $month=date("m",$fcTime);
            
            /*$unique = join(',',array_unique(explode(',',$unique)));
            $unique=explode(',',$unique);
            var_dump($unique);
            $judge=array();
            for($n=1;$n<=12;$n++){
                $currect = str_pad($n,2,"0",STR_PAD_LEFT);
                $judge[$n]=0;
                for ($m=0;$m<count($unique);$m++){
                    if($unique[$m]==$currect){
                        $judge[$n]=1;
                    }
                }
            }
            
			
			
            $year     = $date[0].$date[1].$date[2].$date[3];
            $month    = $date[4].$date[5];
            $timestmp = mktime( 0,0,0,$month,1,$year );
            $object = $this->data['instance'];
          
            $condition = $this->data['condition'];

            foreach ( $condition as $key=>$value ){
                if( !is_numeric( $value ) ){
                    $where[] = " `{$key}` = `{$value}` ";
                }else{
                    $where[] = " `$key` = {$value}";
                }
            }


            if( !empty( $where ) ){
                $where = implode( ' AND ',$where )." AND ";
            }
            
            //循环得到年月列表
            for( $i = 0; $i<$limit;$i++ ){
                $timestmp_temp    = $timestmp-( $i*28*24*60*60 );
                $key              = date( 'Y',$timestmp_temp );
                $date             = date( 'Y年',$timestmp_temp );
                $time  = $this->getData( $key );
                $sql = "select '{$key}' as `time`,count(1) as count from  {$tableName} where {$where} cTime BETWEEN {$time[0]} AND {$time[1]}";
                $limit_time[$key]['content'] = $date;

//                获得记录数
//                if( $result = $object->fileAwayCount($key,$condition) ){
//                    $limit_time[$key]['count'] = $result[0]['count(*)'];
//                }else{
//                    $limit_time[$key]['count'] = 0;
//                }
            }
            $sql = implode( ' union all ',$sql );
            $result = $dao->query( $sql );
						//dump($sql);exit;
            foreach ( $result as $value ){
                $limit_time[$value['time']]['count'] = $value['count'];
            }
            return $limit_time;*/
            return $da;
        }
        /**
         * getData
         * 处理归档查询的时间格式
         * @param string $findTime 200903这样格式的参数
         * @static
         * @access protected
         * @return void
         */
        private function getData( $findTime ){
            //处理年份
            $year = $findTime[0].$findTime[1].$findTime[2].$findTime[3];
            //处理月份
            $month_temp = explode( $year,$findTime);
            $month = $month_temp[1];
            //归档查询
            if ( !empty( $month ) ){

                //判断时间.处理结束日期
                switch (true) {
                    case ( in_array( $month,array( 1,3,5,7,8,10,12 ) ) ):
                        $day = 31;
                        break;
                    case ( 2 == $month ):
                        if( 0 != $year % 4 ){
                            $day = 28;
                        }else{
                            $day = 29;
                        }
                        break;
                    default:
                        $day = 30;
                        break;
                }
                //被查询区段开始时期的时间戳
                $start = mktime( 0, 0, 0 ,$month,1,$year  );

                //被查询区段的结束时期时间戳
                $end   = mktime( 24, 0, 0 ,$month,$day,$year  );

                //反之,某一年的归档
            }elseif( isset( $year[4] ) ){
                $start = mktime( 0, 0, 0, 1, 1, $year );
                $end = mktime( 24, 0, 0, 12,31, $year  );
            }else{
                //其它操作
            }

            //fd( array( friendlyDate($start),friendlyDate($end) ) );
            return array( $start,$end );

        }

    }
