<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> <!--Layout-->
<div id="layout1" class="mini-layout" style="width:100%;height:100%;text-align:left;">
	<div title="center" region="center" bodyStyle="overflow:hidden;" style="border:0;">
		<!--Splitter-->
		<div class="mini-splitter" style="width:200%;height:100%;" borderStyle="border:0;">
			<div showCollapseButton="false" style="border:0px;">
				<!--Tabs-->
				<div id="mainTabs" class="mini-tabs bg-toolbar" activeIndex="0" style="width:100%;height:100%;">
					<div title="应用管理" url="admin.php?m=application&a=appList" ></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    
    grid.load();
        
    function showTab() {
        var tabs = mini.get("mainTabs");
        var tab = tabs.getTab(id);
        if (!tab) {
            tab = {};
            tab.name = id;
            tab.showCloseButton = true;
            //这里拼接了url，实际项目，应该从后台直接获得完整的url地址
            tab.url = "admin.php?m=application&a=appList;
            tabs.addTab(tab);
        }
        tabs.activeTab(tab);
    }
    
</script>
<?php include $this->gettpl('foot');?>

