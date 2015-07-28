<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
    <div class="mini-fit" style ="text-align:left;">

        <ul id="tree1" class="mini-tree" style="width:100%;height:100%;"
            showTreeIcon="true" textField="text" idField="id" parentField="pid" resultAsTree="false"
            expandOnLoad="false" onnodedblclick="onNodeDblClick" expandOnDblClick="false"
            >
        </ul>

    </div>
    <div class="mini-toolbar" style="text-align:center;padding-top:8px;padding-bottom:8px;"
        borderStyle="border-left:0;border-bottom:0;border-right:0;">
        <a class="mini-button" style="width:60px;" onclick="onOk()">确定</a>
        <span style="display:inline-block;width:25px;"></span>
        <a class="mini-button" style="width:60px;" onclick="onCancel()">取消</a>
    </div>

</body>
</html>
<script type="text/javascript">
    mini.parse();

    var tree = mini.get("tree1");


    tree.load("admin.php?m=student&a=getClassSelecttree&schoolid=<?php echo $schoolid;?>");

    function GetData() {
        var node = tree.getSelectedNode();
        return node;
    }

    function onKeyEnter(e) {
        search();
    }
    function onNodeDblClick(e) {
        onOk();
    }
    //////////////////////////////////
    function CloseWindow(action) {
        if (window.CloseOwnerWindow) return window.CloseOwnerWindow(action);
        else window.close();
    }

    function onOk() {
        var node = tree.getSelectedNode();
        if (node && tree.isLeaf(node) == false) {
            alert("不能选中父节点");
            return;
        }

        CloseWindow("ok");
    }
    function onCancel() {
        CloseWindow("cancel");
    }


</script>