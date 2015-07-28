<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo ($ts['site']['site_name']); ?>管理后台</title>
<link href="__PUBLIC__/admin/style.css" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/js/tbox/box.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	var _UID_ = "<?php echo ($uid); ?>";
	var _PUBLIC_ = "__PUBLIC__";	
</script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/tbox/box.js"></script>
</head>
<body>
<div class="so_main">
  <div class="page_tit">站点配置</div>
  
  <div class="form2">
  	<form method="post" action="<?php echo U('admin/Global/doSetSiteOpt');?>" enctype="multipart/form-data">
    <dl class="lineD">
      <dt>站点名称：</dt>
      <dd>
        <input name="site_name" type="text" value="<?php echo ($site_name); ?>" size="60">
        <span>整个网站的名称</span>
      </dd>  
    </dl>
    <dl class="lineD">
      <dt>默认标题：</dt>
      <dd>
        <input name="site_header_title" type="text" value="<?php echo ($site_header_title); ?>" size="60">
        <span>整个站点默认的标题(title)，搜索引擎优化使用</span>
      </dd>
    </dl>
    <dl class="lineD">
      <dt>默认关键字：</dt>
      <dd>
        <textarea cols="50" rows="3" name="site_header_keywords"><?php echo ($site_header_keywords); ?></textarea><br />
        <span>页面meta标签里的关键字信息(keywords)，搜索引擎优化使用</span>
      </dd>
    </dl>
    <dl class="lineD">
      <dt>默认描述信息：</dt>
      <dd>
        <textarea cols="50" rows="3" name="site_header_description"><?php echo ($site_header_description); ?></textarea><br />
        <span>页面meta标签对关键字内容的描述(description)，搜索引擎优化使用</span>
      </dd>
    </dl>

    <dl class="lineD">
      <dt>公司名/ICP/IP/域名备案：</dt>
      <dd>
        <input name="site_icp" type="text" value="<?php echo ($site_icp); ?>" size="60">
        <p>例如：青岛理工大学 </p>
      </dd>
    </dl>
    
    <dl class="lineD">
      <dt>站点状态：</dt>
      <dd>
        <label class="check-line">
        	<input class="s-ck" name="site_closed" type="radio" value="1" <?php if(($site_closed)  ==  "1"): ?>checked<?php endif; ?>>关闭
        </label>
        <br>
        <label>
        	<input class="s-ck" name="site_closed" type="radio" value="0" <?php if(($site_closed)  ==  "0"): ?>checked<?php endif; ?>>开启
        </label>
      </dd>
    </dl>
    <dl class="lineD">
      <dt>站点关闭理由：</dt>
      <dd>
        <textarea cols="50" rows="3" name="site_closed_reason"><?php echo ($site_closed_reason); ?></textarea><br />网站关闭时，页面显示的内容</span>
      </dd>
    </dl>
    <dl class="lineD">
      <dt>是否允许匿名访问个人空间：</dt>
      <dd>
        <label class="check-line">
            <input class="s-ck" name="site_anonymous" type="radio" value="1" <?php if(($site_anonymous)  ==  "1"): ?>checked<?php endif; ?>>允许
        </label>
        <br>
        <label>
            <input class="s-ck" name="site_anonymous" type="radio" value="0" <?php if(($site_anonymous)  ==  "0"): ?>checked<?php endif; ?>>禁止
        </label>
        <p>对于没有登录或注册的用户是否允许访问个人空间</p>
      </dd>
    </dl>
    <dl class="lineD">
      <dt>是否允许匿名访问微广播广场：</dt>
      <dd>
        <label class="check-line">
            <input class="s-ck" name="site_anonymous_square" type="radio" value="1" <?php if(($site_anonymous_square)  ==  "1"): ?>checked<?php endif; ?>>允许
        </label>
        <br>
        <label>
            <input class="s-ck" name="site_anonymous_square" type="radio" value="0" <?php if(($site_anonymous_square)  ==  "0"): ?>checked<?php endif; ?>>禁止
        </label>
        <p>对于没有登录或注册的用户是否允许访问微广播广场</p>
      </dd>
    </dl>
    <dl class="lineD">
      <dt>是否允许匿名访问搜索：</dt>
      <dd>
        <label class="check-line">
            <input class="s-ck" name="site_anonymous_search" type="radio" value="1" <?php if(($site_anonymous_search)  ==  "1"): ?>checked<?php endif; ?>>允许
        </label>
        <br>
        <label>
            <input class="s-ck" name="site_anonymous_search" type="radio" value="0" <?php if(($site_anonymous_search)  ==  "0"): ?>checked<?php endif; ?>>禁止
        </label>
        <p>对于没有登录或注册的用户是否允许访问搜索</p>
      </dd>
    </dl>
    <dl class="lineD">
      <dt>是否启用个性化域名：</dt>
      <dd>
        <label class="check-line">
            <input class="s-ck" name="site_user_domain_on" type="radio" value="1" <?php if(($site_user_domain_on)  ==  "1"): ?>checked<?php endif; ?>>启用
        </label>
        <br>
        <label>
            <input class="s-ck" name="site_user_domain_on" type="radio" value="0" <?php if(($site_user_domain_on)  !=  "1"): ?>checked<?php endif; ?>>禁用
        </label>
        <p>帐号，设置里面显示</p>
      </dd>
    </dl>
    <dl class="lineD">
      <dt>是否启用Rewrite伪静态：</dt>
      <dd>
        <label class="check-line">
            <input class="s-ck" name="site_rewrite_on" type="radio" value="1" <?php if(($site_rewrite_on)  ==  "1"): ?>checked<?php endif; ?>>启用
        </label>
        <br>
        <label>
            <input class="s-ck" name="site_rewrite_on" type="radio" value="0" <?php if(($site_rewrite_on)  !=  "1"): ?>checked<?php endif; ?>>禁用
        </label>
		    <p>启用伪静态，需要服务器环境支持Rewrite功能。请和你的系统管理员或主机厂商咨询确认，从URLRewrite目录下拷贝伪静态规则文件到根目录下。</p>
      </dd>
    </dl>
    <!--<dl class="lineD">
      <dt>左侧的应用名超过4个字时：</dt>
      <dd>
        <label>
            <input name="site_appalias_wordwrap" type="radio" value="1" <?php if(($site_appalias_wordwrap)  ==  "1"): ?>checked<?php endif; ?>>换行
        </label>
        <br>
        <label>
            <input name="site_appalias_wordwrap" type="radio" value="0" <?php if(($site_appalias_wordwrap)  ==  "0"): ?>checked<?php endif; ?>>隐藏多余字符
        </label>
      </dd>
    </dl>-->

    <dl class="lineD">
      <dt>风格包：</dt>
      <dd>
          <select name="site_theme">
              <?php if(is_array($theme_list)): ?><?php $i = 0;?><?php $__LIST__ = $theme_list?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$theme): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($theme["filename"]); ?>" <?php if($site_theme == $theme['filename']){ ?>selected<?php } ?>><?php echo ($theme["filename"]); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
          </select>
          <p>重要: 修改风格包后需要手动清理缓存!</p>
      </dd>
    </dl>
      <dl style="*zoom:1">
      <dt>logo：</dt>
      <dd>
          <img src="<?php echo ($site_logo); ?>" /><br />
		  <input type="file" name="site_logo" />
          <p>重要: 在线上传LOGO，最好是8位透明PNG图标!</p>
      </dd>
    </dl>
      <dl style="*zoom:1">
      <dt>banner：</dt>
      <dd>
          <img src="<?php echo ($banner_logo); ?>" style="width:600px" /><br />
          <input type="file" name="banner_logo" />
          <p>重要: 因为各种模板的样式和比例不同，请注意图片大小，程序并不进行自动剪切!</p>
          <p>Newstyle模板，为960*305尺寸</p>
          <p>weibo模板，为525*430尺寸</p>
          <p>classic2模板，为960*305尺寸</p>
      </dd>
    </dl>
    <dl style="*zoom:1">
    <dt>表情包：</dt>
      <dd>
          <select name="expression">
              <?php if(is_array($expression_list)): ?><?php $i = 0;?><?php $__LIST__ = $expression_list?><?php if( count($__LIST__)==0 ) : echo "" ; ?><?php else: ?><?php foreach($__LIST__ as $key=>$theme): ?><?php ++$i;?><?php $mod = ($i % 2 )?><option value="<?php echo ($theme["filename"]); ?>" <?php if($expression == $theme['filename']){ ?>selected<?php } ?>><?php echo ($theme["filename"]); ?></option><?php endforeach; ?><?php endif; ?><?php else: echo "" ;?><?php endif; ?>
          </select>
          <p>重要: 修改表情包后需要手动清理缓存!</p>
      </dd>
    </dl>
    <dl style="*zoom:1">
      <dt>开启验证码：</dt>
      <dd>
        <label style="padding:0; margin:0 30px 0 0;_margin:0 30px 0 -3px" class="check-line">
        	<input class="s-ck" name="site_verify[]" type="checkbox" value="register" <?php if(in_array('register',$site_verify)) { ?>checked<?php } ?>>注册页面
        </label>
        <label style="padding:0">
        	<input class="s-ck" name="site_verify[]" type="checkbox" value="login" <?php if(in_array('login',$site_verify)) { ?>checked<?php } ?>>登录页面
        </label>
        <p>登录或注册是否显示验证码</p>
      </dd>
    </dl>
    <dl style="*zoom:1">
      <dt>全站微广播、评论字数限制：</dt>
      <dd>
        <input name="length" type="text" value="<?php echo ($length); ?>"> 字
        <p>填写正整数（大于0），默认是140字。</p>
      </dd>
    </dl>
    <dl style="*zoom:1">
      <dt>全站微广播、评论发布频率限制：</dt>
      <dd>
        <input name="max_post_time" type="text" value="<?php echo ($max_post_time); ?>"> 秒
        <p>填写正整数（大于0），默认是5秒。执行发布操作后，在5秒内不允再发布内容。</p>
      </dd>
    </dl>
    <dl style="*zoom:1">
      <dt>用户关注人数限制：</dt>
      <dd>
        <input name="max_following" type="text" value="<?php echo ($max_following); ?>"> 人
        <p>填写正整数（大于0），默认是1000人。只能关注1000个用户（管理员除外），修改此数字后，只在加关注时按新数字计算。</p>
      </dd>
    </dl>
    <dl style="*zoom:1">
      <dt>全站搜索频率限制：</dt>
      <dd>
        <input name="max_search_time" type="text" value="<?php echo ($max_search_time); ?>"> 秒
        <p>填写正整数（大于0），默认是5秒。执行搜索操作后，在5秒内不允再搜索。</p>
      </dd>
    </dl>
    <!--<dl style="*zoom:1">
      <dt>页面刷新频率限制：</dt>
      <dd>
        <input name="max_refresh_time" type="text" value="<?php echo ($max_refresh_time); ?>"> 秒
        <p>填写正整数（大于0），默认是1秒。同一个地址，在1秒内不允再刷新。</p>
      </dd>
    </dl>
    -->
    <div class="page_btm">
      <input type="submit" class="btn_b" value="确定" />
    </div>
    </form>
  </div>
</div>
<!-- 底部状态栏 start -->
<div region="south" id="bottomBar" class="bottomBar" style="text-align:center; clear:both; line-height: 30px; background:none; text-align: center;">
    <?php echo ($ts['site']['site_icp']); ?>
    <div id="site_analytics_code" style="display:none;">
        <?php echo (base64_decode($site["site_analytics_code"])); ?>
    </div>
</div>
<!-- 底部状态栏 end -->
<!-- 底部状态栏 end -->
</body>
</html>