<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
﻿<?php include $this->gettpl('header');?> <!--Layout-->
<div id="layout1" class="mini-layout" style="width:100%;height:100%;text-align:left;">
	<div title="center" region="center" bodyStyle="overflow:hidden;" style="border:0;">
		<!--Splitter-->
		<div class="mini-splitter" style="width:100%;height:100%;" borderStyle="border:0;">
			<div size="250" maxSize="300" minSize="250" showCollapseButton="true" style="border-width:1px;">
				<!--Tree-->
				<ul id="leftTree" class="mini-tree" url="admin.php?m=class&a=ListTree" style="width:100%;height:100%;"
				showTreeIcon="true" textField="name" idField="id" parentField="pid" resultAsTree="false"
				onnodeselect="onNodeSelect" dataField = ""
				></ul>
			</div>
			<div showCollapseButton="false" style="border:0px;" >
				<!--Tabs-->
				<div id="mainTabs" class="mini-tabs bg-toolbar" activeIndex="0" style="width:100%;height:100%;">
					<div title="班级列表" url="admin.php?m=class&a=classList" ></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
        
    var tree = mini.get("leftTree");
    function showTab(treeNode) {
        var tabs = mini.get("mainTabs");
        var id = "tab$" + treeNode.id;
        var tab = tabs.getTab(id);
        if (!tab) {
            tab = {};
            tab.name = id;
            tab.title = treeNode.name;
            tab.showCloseButton = true;
            //这里拼接了url，实际项目，应该从后台直接获得完整的url地址
            tab.url = "admin.php?m=class&a=classList&eid=" + treeNode.eid + "&ss=" + treeNode.ss;
            tabs.addTab(tab);
        }
        tabs.activeTab(tab);
    }

    function onNodeSelect(e) {
        var node = e.node;
        var isLeaf = e.isLeaf;
        showTab(node);
    }

    function onClick(e) {
        var text = this.getText();
        alert(text);
    }

    function onQuickClick(e) {
        tree.expandPath("datagrid");
        tree.selectNode("datagrid");
    }
</script>
<?php include $this->gettpl('foot');?>
