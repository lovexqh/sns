<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<style>
td {
	text-align: left;
	padding-left: 10px;
	line_height:10px;
}
</style>
	<form action="admin.php?m=teacher&a=uploadExcel" method="post" enctype="multipart/form-data">
		<table style="width: 100%">
			<tr>
				<td width="100px">当前学校：</td>
				<td width="150px"><?php echo $xxmc;?></td>
				<td></td>
			</tr>
			<tr>
				<td>选择Excel文件：</td>
				<td>
					<input type="file" name="files[]"/>
   				</td>
				<td>
					<input type="hidden" name="schoolid" value="<?php echo $xxid;?>"/>
					<input type="submit" name="submit" value="导入文件"/>
					<input type="button" onclick="window.open('./data/tmp/hrUploadModel.xls');" value="下载模版"/>
				</td>
			</tr>
		</table>
	</form>
<?php include $this->gettpl('footer');?>