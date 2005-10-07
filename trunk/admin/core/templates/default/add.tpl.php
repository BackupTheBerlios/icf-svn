<?php include $this->loadTemplate('header.tpl.php') ?>
<?php include $this->loadTemplate('menu.tpl.php') ?>
<?php include $this->loadTemplate('toolbar.tpl.php') ?>
<?php include $this->loadTemplate('startmain.tpl.php') ?>
<?php require_once "./frontAttributes/frontAttributeFactory.php" ?>

<script language="javascript">

	var tree;

	function displayTree()
	{
		tree = new dhtmlXTreeObject("treeBox", "100%", "100%", 0); 
	
		tree.setImagePath("<?php echo $this->templatePath?>/images/");
		tree.enableCheckBoxes(true);
		tree.enableThreeStateCheckboxes(false);

		<?php foreach ($this->allowedFolderArray as $allowedFolder) {?>
			tree.insertNewItem(<?php echo $allowedFolder["parentId"]?>, <?php echo $allowedFolder["id"] ?>, "<?php echo $allowedFolder["title"]?>", "<?php echo $allowedFolder["closed"]?>", "<?php echo $allowedFolder["closed"]?>", "<?php echo $allowedFolder["open"]?>", 0, "<?php echo $allowedFolder["mode"] ?>");
			<?php if ($allowedFolder["checked"]) {?>
				tree.setCheck(<?php echo $allowedFolder["id"]?>, true);
			<?}?>
		<?}?>
	}
	
	/**
	 * Transfers selected items from one combo to another
	 * @param sourceSelectId String - source combo id string
	 * @param targetSelectId String - target combo id string
	 */
	function transferSelectItems(sourceSelectId, targetSelectId)
	{
		sourceSelect = document.getElementById(sourceSelectId);
		targetSelect = document.getElementById(targetSelectId);
		
		for(var i = 0; i < sourceSelect.options.length; i++)
		{
			if (sourceSelect.options[i].selected)
			{
				var option = sourceSelect.options[i];
				sourceSelect.options[i] = null;

				var newOption = new Option(option.text, option.value, false, false);
				targetSelect.options.add(newOption);
				i--;
			}
		}
	}
	
	/**
	 * Validates data before sending
	 */
	function validateData()
	{
		var allChecked = tree.getAllChecked().toString();
		
		if (allChecked == "") 
		{
			alert("<?php echo $this->text["mustselectleaf"]?>");
			return false;
		}
		
		allCheckedArray = allChecked.split(",");
		
		for (var i = 0; i < allCheckedArray.length; i++)
		{
			if (tree.hasChildren(allCheckedArray[i]) > 0)
			{
				alert("<?php echo $this->text["mustnotselectparents"]?>");
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Selects items from classRelation combos so they are submitted
	 */
	function selectClassRelationItems()
	{
		// Select all items from multiselection combos
		var elementsArray = document.getElementsByTagName("select");
		
		for (var i = 0; i < elementsArray.length; i++)
		{
			if (elementsArray[i].name.indexOf("classRelation") >= 0 && elementsArray[i].type == "select-multiple")
			{
				var select = elementsArray[i];				
				// Select all items
				for (var j = 0; j < select.options.length; j++)
					select.options[j].selected = true;
			}
		}		
	}
	
	function save_onClick()
	{		
		if (validateData() == false) return;
		selectClassRelationItems();
		
		document.getElementById("folders").value = tree.getAllChecked();
		document.getElementById("method").value = "save";
		document.getElementById("objectForm").submit();
	}
	
	function update_onClick()
	{
		if (validateData() == false) return;
		selectClassRelationItems();
		
		document.getElementById("folders").value = tree.getAllChecked();		
		document.getElementById("method").value = "update";
		document.getElementById("objectForm").submit();
	}
	
	function cancel_onClick()
	{
		window.location.href="./home.php";
	}
	
	function reset_onClick()
	{
		document.getElementById("hitsText").value = "0";
	}
		
</script>

<form enctype="multipart/form-data" name="objectForm" id="objectForm" method="POST" action="add.php">
	<input type="hidden" name="method" id="method" />
	<input type="hidden" name="classId" id="classId" value="<?php echo $this->class->getId()?>" />
	<input type="hidden" name="objectId" id="objectId" value="<?php echo $this->objectId?>" />
	<input type="hidden" name="folders" id="folders" value="<?php echo $this->objectFolders?>" />
	<input type="hidden" name="refererHidden" id="refererHidden" value="<?php echo $this->controllerData["refererHidden"]?>" />
	
	<table class="adminform" border="0" width="100%">
		<tr>
			<td width="65%" valign="top">
				<table width="100%">
					<tr>
						<th colspan="3"><?php echo $this->text["details"]?></th>
					</tr>
					
					<? foreach ($this->frontLanguageArray as $language) { ?>
					<tr>
						<td><img src="<?php echo $this->templatePath?>/images/flags/<?php echo strtolower($language[0]->getTitle())?>.gif"></td>
						<td colspan="2" width="100%"><b><?php echo $this->text["language"]?> <?php echo $language[0]->getTitle()?></b></td>
					</tr>
					
						<?php foreach ($language[1] as $frontAttribute) { ?>
					<tr>
						<td width="1%">
							<?php $attribute = $frontAttribute->getAttribute() ?>
							<a href="#" onmouseover="return escape('<?php echo $attribute->getHelpText() ?>')">
								<img src="<?php echo $this->templatePath?>/images/tooltip.png" border="0" />
							</a>
						</td>
			
						<td width="10%"><?php echo $attribute->getTitle()?>: </td>
						<td width="89%">
							<?php echo $frontAttribute->drawWidget()?>
						</td>
					</tr>
						<? } ?>
						
					<!-- Relationships between classes -->					
					<?php if ($language[0]->getIsMain()) { ?>
						<?php foreach($this->class->getClassRelations() as $classRelation)
						{
							/* @var $classRelation ClassRelation */						
							?>
					<tr>
						<td>
							<a href="#" onmouseover="return escape('<?php echo $classRelation->getHelpText()?>')">
								<img src="<?php echo $this->templatePath?>/images/tooltip.png" border="0" />
							</a>
						</td>
						<td>
							<?php echo $classRelation->getTitle()?>
						</td>
						<td>
							<?php if ($classRelation->getCardinality() <= 1)
							{
							?>
							<select name="classRelation<?php echo $classRelation->getId()?>Select[]" id="classRelation<?php echo $classRelation->getId()?>Select[]" style="width:150px">
								<option value=""></option>
									<?php $child = $classRelation->getChild(); /* @var $child BaseClass */ ?>
									<?php foreach ($child->getObjects() as $childObject)
									{
										/* @var $childObject Object */
										?>
									<option value="<?php echo $childObject->getId()?>" <?php if (is_null($this->object) == false && $this->object->hasObjectRelation($childObject->getId())) echo "selected"?>><?php echo $childObject->getTitle()?></option>
								<?php
								  }
								?>
								</select>
							<?php
							}
							?>
								
							<?php if ($classRelation->getCardinality() > 1)
							{
								?>
								<table width="100%">
									<tr>
										<td width="40%">
											<center><?php echo $this->text["available"]?></center>
										</td>
										<td width="20%">
										</td>
										<td width="40%">
											<center><?php echo $this->text["selected"]?></center>
										</td>
									</tr>								
									<tr>
										<td>
											<center>
											<select name="classes<?php echo $classRelation->getId()?>Select" id="classes<?php echo $classRelation->getId()?>Select" style="width:150px" multiple="multiple" size="5">
												<?php $child = $classRelation->getChild(); /* @var $child BaseClass */ ?>
												<?php foreach ($child->getObjects() as $childObject)
												{
													/* @var $childObject Object */
													if ($this->object != null && $this->object->hasObjectRelation($childObject->getId()) == true) continue;
													?>
												<option value="<?php echo $childObject->getId()?>"><?php echo $childObject->getTitle()?></option>
											<?php
											  }
											?>
											</select>
											</center>
										</td>
										<td>
											<center>
												<input type="button" onclick="transferSelectItems('classes<?php echo $classRelation->getId()?>Select', 'classRelation<?php echo $classRelation->getId()?>Select[]')" value=">>" />
												<input type="button" onclick="transferSelectItems('classRelation<?php echo $classRelation->getId()?>Select[]', 'classes<?php echo $classRelation->getId()?>Select')" value="<<" />
											</center>
										</td>
										<td>
											<center>
											<select name="classRelation<?php echo $classRelation->getId()?>Select[]" id="classRelation<?php echo $classRelation->getId()?>Select[]" style="width:150px" multiple="multiple" size="5">
												<?php $child = $classRelation->getChild(); /* @var $child BaseClass */ ?>
												<?php foreach ($child->getObjects() as $childObject)
												{
													/* @var $childObject Object */
													if ($this->object == null) continue; 
													if ($this->object->hasObjectRelation($childObject->getId()) == false) continue;
													?>
												<option value="<?php echo $childObject->getId()?>"><?php echo $childObject->getTitle()?></option>
											<?php
											  }
											?>
											</select>
											</center>
										</td>
										<td width="50">&nbsp;</td>
									</tr>
								</table>
									<?php
									}
									?>
							</td>
						</tr>
					<?php
						}
						?>
							
					<?php 
					}?>
					
					<!-- separator between languages -->
					<tr><td colspan="3"><hr></td></tr>
					<? } ?>

				</table>
			</td>
			<td width="35%"  valign="top">
				<table width="100%">
					<tr>
						<th><?php echo $this->text["publishinginfo"]?></th>
					</tr>
					<tr>
						<td>
							<table width="100%" class="adminform">
								<tr>
									<td><?php echo $this->text["publish"]?></td>
									<td><input type="checkbox" name="publishCheckbox" id="publishCheckbox" <?php if ($this->publishCheckbox) echo "checked=\"checked\""?> value="-1"></td>
								</tr>
								<tr>
									<td><?php echo $this->text["from"]?></td>
									<td>
										<input class="text_area" id="publishFromText" name="publishFromText" type="text" size="12" value="<?php echo $this->publishFromText ?>">&nbsp;
										<input type="button" value="..." onClick="return showCalendar('publishFromText', '<?php echo $this->dateFormat->getCalendarDateFormatString()?>');">
									</td>
								</tr>
								<tr>
									<td><?php echo $this->text["to"]?></td>
									<td>
										<input class="text_area" id="publishToText" name="publishToText" type="text" size="12" value="<?php echo $this->publishToText ?>">&nbsp;
										<input type="button" value="..." onClick="return showCalendar('publishToText', '<?php echo $this->dateFormat->getCalendarDateFormatString()?>');">
									</td>
								</tr>
								<tr>
									<td colspan="2"><hr/> </td>
								</tr>
								<tr>
									<td colspan="2">
										<div id="treeBox" style="width:100%">
											<?php echo $this->text["publishin"]?>
										</div>
										<script language="javascript">displayTree()</script>
									</td>
								</tr>
	
								<tr>
									<td colspan="2"></td>
								</tr>
	
								<tr>
									<td><?php echo $this->text["hits"]?></td>
									<td><input type="text" readonly="true" class="text_area" name="hitsText" id="hitsText" size="5" maxlength="10" value="<?php echo $this->hits?>"/>&nbsp;<input type="button" value="Reset" onclick="reset_onClick()"></td>
								</tr>
								<tr>
									<td><?php echo $this->text["createdby"]?></td>
									<td><input type="text" readonly="true" class="text_area" name="createdByText" id="createdByText" size="25" maxlength="25" value="<?php echo $this->createdBy?>"/></td>
								</tr>
								<tr>
									<td><?php echo $this->text["createdon"]?></td>
									<td><input type="text" readonly="true" class="text_area" name="createdOnText" id="createdOnText" size="25" maxlength="25" value="<?php echo $this->createdOn?>"/></td>
								</tr>
								<tr>
									<td><?php echo $this->text["updatedby"]?></td>
									<td><input type="text" readonly="true" class="text_area" name="updatedByText" id="updatedByText" size="25" maxlength="25" value="<?php echo $this->updatedBy?>"/></td>
								</tr>
								<tr>
									<td><?php echo $this->text["updatedon"]?></td>
									<td><input type="text" readonly="true" class="text_area" name="updatedOnText" id="updatedOnText" size="25" maxlength="25" value="<?php echo $this->updatedOn?>"/></td>
								</tr>	
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

<?php include $this->loadTemplate('endmain.tpl.php') ?>
<?php include $this->loadTemplate('footer.tpl.php') ?>