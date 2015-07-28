<?php
/**
 * 字典模型
 *
 * @author 小波 <xiaobo.sun@ruijie-grid.com.cn>
 */
define('DICTIONARY_ISNULL',0);	//数据不能为空
define('DICTIONARY_NOARRAY',-2);//数据不是array
define('DICTIONARY_NOEMPTY',-3);//数据不完整
define('DICTIONARY_SUCCESS',1);	//执行操作成功
class DictionaryModel extends Model{
	protected $tableName = 'category_dictionary';

	/***
	 * 获取某一组类型数据
	 */
	public function findAll($map){
		$map = $this->__formatData($map);
		return $this->where($map)->select();
	}

	/***
	 * 添加字典数据
	 * @item 添加的字段数据
	 * @return int 状态代码
	 */
	public function insert($item=NULL){
		if(empty($item)){
			return DICTIONARY_ISNULL;
		}
		if(!is_array($item)){
			return DICTIONARY_NOARRAY;
		}
		if (!$this->__isValidParam('DataDescribe,DataType,DataName,DataCode',$item)) {
			return DICTIONARY_NOEMPTY;
		}

		return $this->add($this->__formatData($item));
	}

	/**
	 * 更新字典数据
	 */
	public function update($map,$item){
		if(empty($item)||empty($map)){
			return DICTIONARY_ISNULL;
		}
		if(!is_array($item)||!is_array($map)){
			return DICTIONARY_NOARRAY;
		}
		if (!$this->__isValidParam('DataName,DataCode',$item)) {
			return DICTIONARY_NOEMPTY;
		}
		return $this->where($this->__formatData($map))->save($this->__formatData($item));
	}

	/***
	 * 格式化数据库数据
	 */
	private function __formatData($item){
		$params = array(
			'DataDescribe','DataType','DataName','DataCode','DataOrder','Remark'
		);

		if(is_array($item)){
			$obj = array();
			foreach ($item as $k => $v){
				foreach($params as $s){
					if(strtolower(trim($k))==strtolower(trim($s))){
						$i = trim($s);
					}
				}
				$key = empty($i)?$k:$i;
				$obj[$key]=$v;
			}
			return $obj;
		}else{
			foreach($params as $s){
				if(strtolower(trim($item))==strtolower(trim($s))){
					$i = trim($s);
				}
			}
			return empty($i)?$item:$i;
		}

	}

	/***
	 * 验证数据的完整性
	 */
	private function __isValidParam($field, $array = array()) {
		$field = is_array($field) ? $field : explode(',', $field);
		foreach ($field as $v){
			$v = strtolower(trim($v));
			if ( !isset($array[$v]) || $array[$v] == '' ) return false;
		}
		return true;
	}
}

?>