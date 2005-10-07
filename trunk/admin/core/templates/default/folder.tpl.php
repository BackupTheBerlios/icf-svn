<?php include $this->loadTemplate('header.tpl.php') ?>
<?php include $this->loadTemplate('menu.tpl.php') ?>
<?php include $this->loadTemplate('toolbar.tpl.php') ?>
<?php include $this->loadTemplate('startmain.tpl.php') ?>

<?php $isoDateFormat = new IsoDateFormat(); ?>

<form method="POST" name="form" id="form">
	<input type="hidden" name="method" id="method" value="showView" />
	<input type="hidden" name="folderId" id="folderId" value="<?php echo $this->folder->getId()?>" />
	<input type="hidden" name="childFolderIdHidden" id="childFolderIdHidden" value="" />
	<input type="hidden" name="childObjectFolderIdHidden" id="childObjectFolderIdHidden" value="" />
	<input type="hidden" name="selectedFoldersHidden" id="selectedFoldersHidden" value="" />
	

	<table class="adminform" border="0" width="100%">
		<tr>
			<td valign="top" width="100%">
				<table width="100%">
					<tr>
						<td><?php echo $this->pathView?></td>
						<td align="right">
							<div align="right">
							
								<select name="folderSelect" id="folderSelect">
									<?php foreach(array_keys($this->specialArray) as $key) {?>
										<option value="<?php echo $key?>"><?php echo $this->specialArray[$key]?></option>
									<?}?>
									<?php foreach(array_keys($this->classesArray) as $key) {?>
										<option value="<?php echo $key?>"><?php echo $this->classesArray[$key]?></option>
									<?}?>
								</select>
								
								<input type="button" onclick="addButton_onClick()" value="<?php echo $this->text["add"]?>">
							</div>
						</td>
					</tr>
				</table>			
					
				<table valign="top" align="left" width="100%" class="adminform">
					<tr>
						<th colspan="3"><?php echo $this->text["object"]?></th>
						<th colspan="2" align="center"><?php echo $this->text["order"]?></th>
						<th><?php echo $this->text["class"]?></th>
						<th><?php echo $this->text["user"]?></th>
						<th><?php echo $this->text["updated"]?></th>
					</tr>
					
					<?php $rowClass = "row0"; ?>
					<?php $rowNumber = 0; ?>
					
					<!-- Folders -->
					<tr class="<?php echo $rowClass ?>">
						<td width="1%"></td>
						<td width="1%"><img src="<?php echo $this->templatePath?>/images/folderClosed.gif"/></td>
						<td width="45%"><a href="#" onclick="showFolder('<?php echo $this->folder->getParentID()?>')">..</a></td>
						<td width="2%"></td>
						<td width="2%"></td>
						<td width="25"><?php echo $this->text["folder"]?></td>
						<td width="10%">-</td>
						<td width="14%">-</td>
					</tr>
					
					<?php 
					// TODO: ACTIONS if ($this->folder->canDoAction(null, Action::LIST_FOLDERS_ACTION()) == true)
					// {
						?>
					
					<?php foreach($this->folder->getChildren() as $child) {?>
					<?php if (($rowNumber % 2) == 0) $rowClass = "row0"; else $rowClass="row1"; ?>
					<tr class="<?php echo $rowClass ?>">
						<td><input type="checkbox" name="folderCheckbox" id="folderCheckbox" value="<?php echo $child->getId()?>"></td>
						<td><img src="<?php echo $this->templatePath?>/images/folderClosed.gif"/></td>
						<td><a href="#" onclick="showFolder(<?php echo $child->getId()?>)"><?php echo $child->getTitle()?></a></td>
						<td><?php if ($this->folder->isFirstChild($child) == false) {?><img src="<?php echo $this->templatePath?>/images/uparrow.png" onclick="moveFolderUp(<?php echo $child->getId()?>)" /><?php }?></td>
						<td><?php if ($this->folder->isLastChild($child) == false) {?><img onclick="moveFolderDown(<?php echo $child->getId()?>)" src="<?php echo $this->templatePath?>/images/downarrow.png"/><?php }?></td>
						<td><?php echo $this->text["folder"]?></td>
						<td>-</td>
						<td>-</td>
					</tr>
					<?php $rowNumber++; ?>
					<?php }?>
					
					<?php // }?>
					
					<!-- Objects -->
					<?php if ($this->folder->canDoAction(null, Action::LIST_OBJECTS_ACTION()) == true)
					{?>					
					<?php foreach($this->folder->getObjectFolders() as $objectFolder) {?>
					<?php $object = $objectFolder->getObject(); /* @var $object Object */?>
					<?php $class = $object->getClass(); ?>
					<?php if (($rowNumber % 2) == 0) $rowClass = "row0"; else $rowClass="row1"; ?>
					<tr class="<?php echo $rowClass ?>">
						<td>&nbsp;</td>
						<td><img src="<?php echo $this->templatePath?>/images/leaf.gif"/></td>
						<td><a href="#" onclick="editObject(<?php echo $object->getId()?>)"><?php echo $object->getTitle()?></a></td>
						<td><?php if ($this->folder->isFirstObjectFolder($objectFolder) == false) {?><img onclick="moveObjectFolderUp(<?php echo $objectFolder->getId()?>)" src="<?php echo $this->templatePath?>/images/uparrow.png"/><?php }?></td>
						<td><?php if ($this->folder->isLastObjectFolder($objectFolder) == false) {?><img onclick="moveObjectFolderDown(<?php echo $objectFolder->getId()?>)" src="<?php echo $this->templatePath?>/images/downarrow.png"/><?php }?></td>
						<td><?php echo $class->getTitle()?></td>
						<td><?php $user = $object->getUpdatedByUser(); if ($user == null) echo "-"; else echo $user->getName();?></td>
						<td><?php $date = $isoDateFormat->parseDatetime($object->getUpdated()); if ($date == null) echo "-"; else echo $this->dateFormat->toDatetimeString($date);?></td>
					</tr>
					<?php $rowNumber++; ?>
					<?php }?>
					<?php }?>
					
				</table>
	
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript">
	
	var form = document.getElementById("form");
	var methodHidden = document.getElementById("method");
	var folderIdHidden = document.getElementById("folderId");
	var selectedFoldersHidden = document.getElementById("selectedFoldersHidden");
	var folderSelect = document.getElementById("folderSelect");
	var folderCheckboxArray = document.getElementsByName("folderCheckbox");
	var childFolderIdHidden = document.getElementById("childFolderIdHidden");
	var childObjectFolderIdHidden = document.getElementById("childObjectFolderIdHidden");
	
	function addButton_onClick()
	{
		var value = folderSelect.value;
		if (value == "")
		{
			alert("<?php echo $this->text["noactionselected"]?>");
			return;
		}
		
		if (value == "folder")
		{
			// User requires to add a new folder
			window.location.href = "addFolder.php?parentIdHidden=" + folderIdHidden.value + "&refererHidden=folder.php_folderId-" + folderIdHidden.value;
		}
		else
		{
			// User requires to add a new object
			window.location.href = "add.php?classId=" + value + "&refererHidden=folder.php_folderId-" + folderIdHidden.value;
		}
	}
	
	function editButton_onClick()
	{	
		var checked = 0;
		var folderId;
		for (var i = 0; i < folderCheckboxArray.length; i++)
		{
			if (folderCheckboxArray[i].checked)
			{
				checked++;
				folderId = folderCheckboxArray[i].value;
			}
		}
		
		if (checked == 0)
		{
			alert("<?php echo $this->text["mustcheckonefirst"]?>");
			return;
		}
		
		if (checked > 1)
		{
			alert("<?php echo $this->text["mustcheckonlyone"]?>");
			return;
		}
		
		window.location.href = "addFolder.php?method=showUpdateFolderView&folderIdHidden=" + folderId + "&refererHidden=folder.php_folderId-" + folderIdHidden.value;
	}
	
	function deleteButton_onClick()
	{
		var checked = false;
		
		var selectedFolders = "";
		for (var i = 0; i < folderCheckboxArray.length; i++)
		{
			if (folderCheckboxArray[i].checked) 
			{
				if (selectedFolders != "") selectedFolders += ",";
				selectedFolders += folderCheckboxArray[i].value
			}
		}
		
		if (selectedFolders == "")
		{
			alert("<?php echo $this->text["mustcheckonefirst"]?>");
			return;
		}
		
		if (confirm("<?php echo $this->text["confirmdelete"]?>") == false)
			return;

		methodHidden.value = "deleteFolders";
		selectedFoldersHidden.value = selectedFolders;
		form.submit();
	}
	
	function showFolder(folderId)
	{
		if (folderId == "" || folderId == "null")
		{
			alert("<?php echo $this->text["rootfolderhasnoparent"]?>");
			return;
		}
		
		methodHidden.value = "showFolder";
		childFolderIdHidden.value = folderId;
		form.submit();
	}
	
	function moveFolderUp(folderId)
	{
		methodHidden.value = "moveFolderUp";
		childFolderIdHidden.value = folderId;
		form.submit();
	}
	
	function moveFolderDown(folderId)
	{
		methodHidden.value = "moveFolderDown";
		childFolderIdHidden.value = folderId;
		form.submit();
	}
	
	function moveObjectFolderUp(objectFolderId)
	{
		methodHidden.value = "moveObjectFolderUp";
		childObjectFolderIdHidden.value = objectFolderId;
		form.submit();
	}
	
	function moveObjectFolderDown(objectFolderId)
	{
		methodHidden.value = "moveObjectFolderDown";
		childObjectFolderIdHidden.value = objectFolderId;
		form.submit();
	}
	
	function editObject(objectId)
	{
		window.location.href = "add.php?method=showUpdateView&objectId=" + objectId + "&refererHidden=folder.php_folderId-" + folderIdHidden.value;
	}
	
</script>

<?php include $this->loadTemplate('endmain.tpl.php') ?>
<?php include $this->loadTemplate('footer.tpl.php') ?>