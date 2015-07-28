<?php
/**
 * 选择好友Widget
 */
class SelectClassWidget extends Widget{

	/**
	 * 选择好友Widget
	 * 
	 * $data的参数:
	 * array(
	 * 	'name'(可选)	=> '表单的name', // 默认为"fri_ids"
	 * )
	 * 
	 * @see Widget::render()
	 */
	public function render($data){
		$data['name'] || $data['name']= 'fri_ids';
        $content = $this->renderFile(ADDON_PATH . '/widgets/SelectClass.html',$data);

        return $content;

    }

}
?>