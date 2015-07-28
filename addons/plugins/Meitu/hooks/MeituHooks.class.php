<?php
class MeituHooks extends Hooks
{
	public function weibo_js_plugin()
	{
		echo '<script type="text/javascript" src="' . __ROOT__ . '/addons/plugins/Meitu/html/image.js"></script>';

		//载入Swfobj
		echo '<script type="text/javascript" src="' . __ROOT__ . '/addons/plugins/Meitu/html/swfobject.js"></script>';
		echo '<script>var Plugin_path_meitu="' . $this->htmlPath . '";upload="'.U('home/widget/addonsRequest',array('addon'=>'Meitu','hook'=>'upload')).'";</script>';
	}

	public function home_index_middle_publish_type()
	{
		$html = sprintf("<style>sup{position:absolute;margin-top:1px;margin-left:-10px;background:url(".$this->htmlPath."/html/ico_new.jpg);width:23px;height:11px}.icon_add_meitu_d{background:url('".$this->htmlPath."/html/icon.png') no-repeat;height:16px; width:16px;  margin:0 2px -4px 0;}</style><a href='javascript:void(0)' onclick='weibo.plugin.meitu.click(this)' class='a52' id='meitu'><img class='icon_add_meitu_d' src='%s' />美图</a><sup></sup>", ''.$this->htmlPath.'/html/zw_img.gif');
		echo $html;
	}

	public function weibo_type($param)
	{
		$res = &$param['result'];

		$typeData = $param['typeData'];
		if ($param['typeId'] == '1')
		{
			$savePath = SITE_PATH . '/data/uploads/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
			$path = '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
			$filename = md5(time() . $this->mid);
			if (! file_exists ($savePath))
				mk_dir ($savePath);
				
				
			$size['small']['x'] = 120;
			$size['small']['y'] = 120;
			$size['middle']['x'] = 465;
			$size['middle']['y'] = -1; //不限制
			include_once SITE_PATH . '/addons/libs/Image.class.php';
			@copy(SITE_PATH . '/data/uploads/'.$typeData[0], $savePath . '/' . $filename . '.jpg');
			Image::thumb($savePath . '/' . $filename . '.jpg' , $savePath . '/small_' . $filename . '.jpg' , '' , $size['small']['x'] , $size['small']['y']);
			Image::thumb($savePath . '/' . $filename . '.jpg' , $savePath . '/middle_' . $filename . '.jpg', '' , $size['middle']['x'] , ($size['middle']['y'] == -1)?'auto':$size['middle']['y']);
			$typedata['thumburl'] = $path . 'small_' . $filename . '.jpg';
			$typedata['thumbmiddleurl'] = $path . 'middle_' . $filename . '.jpg';
			$typedata['picurl'] = $path  . $filename . '.jpg';
			$res['type'] = 1;
			$res['type_data'] = serialize($typedata);
		}
		else
		{
			return false;
		}
	}
	public function cancelPublish()
	{
		if (file_exists( SITE_PATH .'/data/uploads/temp/' . $this->mid . '.jpg'))
		{
			@unlink(SITE_PATH . '/data/uploads/temp/' . $this->mid . '.jpg');
		}
		if (file_exists( SITE_PATH .'/data/uploads/temp/' . $this->mid . '.editor.jpg'))
		{
			@unlink(SITE_PATH . '/data/uploads/temp/' . $this->mid . '.editor.jpg');
		}
	}
	public function isok()
	{
		if (file_exists( SITE_PATH .'/data/uploads/temp/' . $_POST['filename'] . '.jpg'))
		{
			$data['ok'] = '1';
			$data['url'] = __UPLOAD__.'/temp/' . $_POST['filename'] . '.jpg';
			$data['urlpath'] = '/temp/' . $_POST['filename'] . '.jpg';
			$data['imgpath'] = $_POST['filename'];
		}
		else
		{
			$data['ok'] = '0';
		}

		exit(json_encode($data));
	}
	public function isitor()
	{
		if (file_exists(SITE_PATH .'/data/uploads/temp/' . $_POST['filename'] . '.editor.jpg'))
		{
			$data['ok'] = '1';
			$data['url'] = __UPLOAD__.'/temp/' . $_POST['filename'] . '.editor.jpg';
			$data['urlpath'] = '/temp/' . $_POST['filename'] . '.editor.jpg';
			$data['old'] = 'data/uploads/temp/' . $_POST['filename'] . '.jpg';
		}
		else
		{
			$data['ok'] = '0';
		}

		exit(json_encode($data));
	}
	public function weibo_type_parse_tpl($param)
	{ 
		// print_r($param);
		$type = $param['typeId'];
		$typeData = $param['typeData'];
		$rand['rand'] = $param['rand'];
		$res = &$param['result'];

		if ($type == '1')
		{
			$this->assign($typeData);
			$this->assign($rand);
			$res = $this->fetch('image');
		}
		else
		{
			return false;
		}
	}
}
