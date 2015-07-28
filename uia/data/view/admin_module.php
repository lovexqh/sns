<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?> <!--Layout-->
<div id="layout1" class="mini-layout" style="width:100%;height:100%;text-align:left;">
	<div title="center" region="center" bodyStyle="overflow:hidden;" style="border:0;">
		<!--Splitter-->
		<div class="mini-splitter" style="width:100%;height:100%;" borderStyle="border:0;">
			<div size="250" maxSize="300" minSize="250" showCollapseButton="true" style="border-width:1px;" name="tree" id="tree">
				<!--Tree-->
				<ul id="leftTree" class="mini-tree" url="admin.php?m=module&a=listTree" style="width:100%;height:100%;"
				showTreeIcon="true" textField="text" idField="id" parentField="pid" resultAsTree="false"
				onnodeselect="onNodeSelect" dataField = ""></ul>
			</div>
			<div showCollapseButton="false" style="border:0px;" >
				<!--Tabs-->
				<div id="editPage" class="mini-tabs bg-toolbar" activeIndex="0" style="width:100%;height:100%;">
					<div title="首页" url="admin.php?m=module&a=moduleEdit" ></div>
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
        var tabs = mini.get("editPage");
        var id = "tab$" + node.id;
        var tab = tabs.getTab(id);
        if (!tab) {
            tab = {};
            tab.name = id;
            tab.title = node.text;
            tab.showCloseButton = true;
            tab.url = "admin.php?m=module&a=moduleEdit&id="+node.id;
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
<?php include $this->gettpl('footer');?>

