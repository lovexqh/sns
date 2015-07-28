<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> <!--Layout-->
<div id="layout1" class="mini-layout" style="width:100%;height:100%;text-align:left;">
	<div title="center" region="center" bodyStyle="overflow:hidden;" style="border:0;">
		<!--Splitter-->
		<div class="mini-splitter" style="width:100%;height:100%;" borderStyle="border:0;">
			<div size="250" maxSize="300" minSize="250" showCollapseButton="true" style="border-width:1px;">
				<!--Tree-->
				<ul id="leftTree" class="mini-tree" url="admin.php?m=college&a=ListTree" style="width:100%;height:100%;"
				showTreeIcon="true" textField="text" idField="id" resultAsTree="false"
				onnodeselect="onNodeSelect" dataField = ""></ul>
			</div>
			<div showCollapseButton="false" style="border:0px;" >
				<!--Tabs-->
				<div id="mainTabs" class="mini-tabs bg-toolbar" activeIndex="0" style="width:100%;height:100%;">
					<div title="院系列表" url="admin.php?m=college&a=collegeList" ></div>
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
    function showTab(node) {
        var tabs = mini.get("mainTabs");
        var id = "tab$" + node.id;
        var tab = tabs.getTab(id);
        if (!tab) {
            tab = {};
            tab.name = id;
            tab.title = node.text;
            tab.showCloseButton = true;
            //这里拼接了url，实际项目，应该从后台直接获得完整的url地址
            tab.url = "admin.php?m=college&a=collegeList&eid=" + node.eid + "&ss=" + node.ss;
            tabs.addTab(tab);
        }
        tabs.activeTab(tab);
    }

    function onNodeSelect(e) {
        var node = e.node;
        var isLeaf = e.isLeaf;
        if(isLeaf){
        	showTab(node);
        }
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
<?php include $this->gettpl('footer');?>

