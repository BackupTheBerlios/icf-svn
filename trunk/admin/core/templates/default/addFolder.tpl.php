<?php include $this->loadTemplate('header.tpl.php') ?>
<?php include $this->loadTemplate('menu.tpl.php') ?>
<?php include $this->loadTemplate('toolbar.tpl.php') ?>
<?php include $this->loadTemplate('startmain.tpl.php') ?>

<form name="folderForm" id="folderForm" method="POST" action="addFolder.php">
	<input type="hidden" name="method" id="method" value="addFolder" />
	<input type="hidden" name="folderIdHidden" id="folderIdHidden" value="<?php echo $this->controllerData["folderIdHidden"]?>" />
	<input type="hidden" name="refererHidden" id="refererHidden" value="<?php echo $this->controllerData["refererHidden"]?>" />
	
	<table class="adminform" border="0" width="100%">
		<tr>
			<td width="100%" valign="top">
				<table width="100%">
					<tr>
						<th colspan="2"><?php echo $this->text["folder"]?></th>
					</tr>
					<tr>			
						<td width="10%"><?php echo $this->text["title"]?>:</td>
						<td width="90%"><input type="text" name="titleText" id="titleText" size="10" maxlength="80" value="<?php echo $this->controllerData["titleText"]?>" /></td>
					</tr>
					<tr>			
						<td><?php echo $this->text["shortdescription"]?>:</td>
						<td><input type="text" name="shortDescriptionText" id="shortDescriptionText" size="30" maxlength="255" value="<?php echo $this->controllerData["shortDescriptionText"]?>" /></td>
					</tr>
					<tr>			
						<td><?php echo $this->text["longdescription"]?>:</td>
						<td><textarea name="longDescriptionTextarea" id="longDescriptionTextarea" rows="4" cols="30"><?php echo $this->controllerData["longDescriptionTextarea"]?></textarea></td>
					</tr>
					<tr>			
						<td><?php echo $this->text["parent"]?>:</td>
						<td>
							<input type="text" name="parentText" id="parentText" size="10" readonly="true" value="<?php echo $this->controllerData["parentText"]?>" />
							<input type="hidden" name="parentIdHidden" id="parentIdHidden" value="<?php echo $this->controllerData["parentIdHidden"]?>"/>
						</td>
					</tr>
					<tr>			
						<td><?php echo $this->text["position"]?>:</td>
						<td>
							<input type="text" name="positionText" id="positionText" size="4" readonly="true" value="<?php echo $this->controllerData["positionText"]?>" />
						</td>
					</tr>
					<tr>			
						<td><?php echo $this->text["classes"]?>:</td>
						<td>
							<select name="classesIdSelect[]" id="classesIdSelect[]" multiple="multiple" size="5">
							<?php
							foreach($this->controllerData["classes"] as $class)
							{
								?>
								<option value="<?php echo $class->getId()?>"
								<?php
								$found = false;
								
								foreach($this->controllerData["classesIdSelect"] as $classId)
								{
									if ($class->getId() == $classId) 
									{
										$found = true;
										break;
									}
								}
								
								if ($found)
								{ 
									?>
									selected="selected"
									<?php
								}
								?>
								>
								<?php echo $class->getTitle()?>
								</option>
							<?}?>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

<script language="javascript">	
	
	var folderForm = document.getElementById("folderForm");
	var methodHidden = document.getElementById("method");
	
	function saveButton_onClick()
	{
		methodHidden.value = "addFolder";
		folderForm.submit();		
	}
	
	function updateButton_onClick()
	{
		methodHidden.value = "updateFolder";
		folderForm.submit();
	}
	
	function cancelButton_onClick()
	{
		methodHidden.value = "addCancel";
		folderForm.submit();
	}
		
</script>

<?php include $this->loadTemplate('endmain.tpl.php') ?>
<?php include $this->loadTemplate('footer.tpl.php') ?>