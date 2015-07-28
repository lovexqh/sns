<?phpfunction compress($buffer) {//去除文件中的注释	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);	return $buffer;}function getContentUrl($url){	return getShortUrl( $url[1] ).' ';}/**
 +----------------------------------------------------------
 * 根据社团ID返回社团所有数据
 +----------------------------------------------------------
 * @param	int	$id	社团ID
 * @return	array 社团所有数据
 * @author	小波
 +----------------------------------------------------------
 * 创建时间：2013-3-5 15:59:28
 +----------------------------------------------------------
 */
function getGroupInfo($id){
	if(intval($id)){
		$map['id'] = $id;
		$data = D('Group','group')->where($map)->find();
		if($data)
			$data['member'] = D('Member','group')->where('gid = '.$id)->order('cTime desc')->findAll();
		return $data;

	}
}		