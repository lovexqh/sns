<?php
/*
功能：生成SeletTree
属性:$result 结果集
	$id_field 自身id字段
	$parent_field 父类id字段
	$option_text 选项显示名称
	$select_name 下拉菜单的名称
	$elected 默认选中
	$no_top 是否需要顶层选项
	$level 层深度
	$parent_id 同层中的id
示例：$result 获取所有结果集
	$tree = new SelectTree($result, 'id', 'bid', 'name', 'tree');
	echo $tree->getSelectTree();
*/
class SelectTree {
	public $result;
	public $select_name;
	public $option_text;
	public $elected;
	public $id_field;
	public $parent_field;
	public $no_top;
	public $level;
	public $parent_id;
	public $getarray;

	function __construct($result, $id_field, $parent_field, $option_text, $select_name = '', $elected = 0, $no_top = 0, $level = 0, $parent_id = 0) {
		$this->result = $result;
		$this->id_field = $id_field;
		$this->parent_field = $parent_field;
		$this->option_text = $option_text;
		$this->select_name = $select_name;
		$this->elected = $elected;
		$this->no_top = $no_top;
		$this->level = $level;
		$this->parent_id = $parent_id;
		$this->getarray = self::getArray();
	}

	/*
	功能：返回Tree二维数组
	*/
	function getArray() {
		$arrays = array ();
		foreach ($this->result as $row) {
			$arrays[$row[$this->parent_field]][$row[$this->id_field]] = $row;
		}
		return $arrays;
	}

	/*
	功能：获取SelectTree
	*/
	function getSelectTree() {
		$tree = '<select name="' . $this->select_name . '">';
		if ($this->no_top) {
			$tree .= '<option value="0">根分类</option>';
		}
		self::buildTree($this->getarray, &$tree, $this->id_field, $this->option_text, $this->selected, $this->level, $this->parent_id); //生成树状结构
		$tree .= '</select>';
		return $tree;
	}

	/*
	功能：递归构建树状结构
	*/
	function buildTree($array, &$tree, $option_value, $option_text, $selected, $level = 0, $parent_id = 0) {
		if (is_array($array[$parent_id])) {
			for ($i = 0; $i < $level; $i++)
				$space .= ' '; //选项缩进深度
			foreach ($array[$parent_id] as $key => $value) {
				if ($value[$option_value] == $selected) {
					$tree .= '<option value="' . $value[$option_value] . '" selected="selected">' . $space . $value[$option_text] . "</option>";
				} else {
					$tree .= '<option value="' . $value[$option_value] . '">' . $space . $value[$option_text] . "</option>";
				}
				$tree .= self :: buildTree($array, & $tree, $option_value, $option_text, $selected, $level +1, $key);
			}
		} else {
			$tree .= '';
		}
	}
}

?>