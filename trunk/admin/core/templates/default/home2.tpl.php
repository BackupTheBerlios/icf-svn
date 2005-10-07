<?php include $this->loadTemplate('header2.tpl.php') ?>
<?php include $this->loadTemplate('menu.tpl.php') ?>
<?php include $this->loadTemplate('toolbar.tpl.php') ?>
<?php include $this->loadTemplate('startmain.tpl.php') ?>

<?php $icfConfig = new IcfConfig() ?>

<script language="javascript">

	function add(classId)
	{
		window.location.href="add.php?classId=" + classId;
	}
	
</script>

<tr>
	<td valign="middle" width="1">
		<img src="<?php echo $this->templatePath?>/images/support.png" border="0">
	</td>
	<td width="33%"><b><?php echo $this->text["publish"]?></b></td>
	<td valign="middle" width="1">
		<img src="<?php echo $this->templatePath?>/images/addedit.png" border="0">
	</td>
	<td width="33%"><b><?php echo $this->text["add"]?></b></td>
	<td valign="middle" width="1">
		<img src="<?php echo $this->templatePath?>/images/categories.png" border="0">
	</td>
	<td width="33%"><b><?php echo $this->text["upload"]?></b></td>
</tr>
<tr>
	<td width="1">&nbsp;</td>
	<td valign="top" width="33%">
		<a href="pending.php"><?php echo $this->controllerData["pending"]?> <?php echo $this->text["pending"]?></a>
	</td>
	<td width="1">&nbsp;</td>
	
	<td valign="top" width="33%">
		<?php foreach($this->classArray as $class) {?>
		<a href="#" onclick="add(<?php echo $class->getId()?>)"><?php echo $class->getTitle()?></a><br>
		<?php }?>
	</td>
	<td width="1">&nbsp;</td>

	<td valign="top" width="33%">
		<a href="#" onclick="add(<?php echo $icfConfig->cfg_image_class_id?>)"><?php echo $this->text["images"]?></a><br>
		<a href="#" onclick="add(<?php echo $icfConfig->cfg_media_class_id?>)"><?php echo $this->text["media"]?></a>
	</td>
</tr>

<?php include $this->loadTemplate('endmain.tpl.php') ?>
<?php include $this->loadTemplate('footer.tpl.php') ?>