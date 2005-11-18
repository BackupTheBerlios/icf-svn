<?php include $this->loadTemplate('header.tpl.php') ?>
<?php include $this->loadTemplate('menu.tpl.php') ?>
<?php include $this->loadTemplate('toolbar.tpl.php') ?>
<?php include $this->loadTemplate('startmain.tpl.php') ?>

<script language="javascript" type="text/javascript">

/* Function called to get the folders list */
function getFolders(){

	var selID = getComboSelectedValue(document.form.classIdSelect);

	ajaxExec('<?php echo $this->templatePath?>/irequest.php?action=get_folders&id=' + selID, handleFolders);
}

/* Function called to handle the list that was returned from the internal_request.php file.. */
function handleFolders(){

	if(icfHTTP.readyState == 4){ //Finished loading the response
		var response = icfHTTP.responseText;
		var cb = document.getElementById('folderIdSelect');

		comboClear(cb);

		eval("var data = " + response);

		for (var i = 0; i < data.length ; i++)
		{
			var pos = data[i].indexOf("|");

			cb.options[i] = new Option(data[i].substr(pos+1),data[i].substr(0, pos-1));
		}

	}
}
</script>

<div align="right">
	<form name="form" id="form" method="POST">
		<table>
			<tr>
				<td>
					<?php echo $this->text["class"]?>:
					<select name="classIdSelect" id="classIdSelect" class="inputbox" onChange="getFolders();">
						<?php foreach ($this->controllerData["classes"] as $class) {?>
						<option value="<?php echo $class->getId()?>" <?php if ($class->getId() == $this->controllerData["classIdSelect"]) echo "selected" ?>><?php echo $class->getTitle()?></option>
						<?php }?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $this->text["inFolder"]?>:
					<select name="folderIdSelect" id="folderIdSelect" class="inputbox">
						<option value="-1" <?php if ($folder->getId() == $this->controllerData["folderIdSelect"]) echo "selected" ?>> <?php echo $this->text["(all)"]?>  </option>

						<?php foreach ($this->controllerData["folders"] as $folder) {?>
						<option value="<?php echo $folder->getId()?>" <?php if ($folder->getId() == $this->controllerData["folderIdSelect"]) echo "selected" ?>><?php echo $folder->getPathway()?></option>
						<?php }?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $this->text["search"]?>: <input type="text" name="titleText" id="titleText" value="<?php echo $this->controllerData["titleText"]?>" class="inputbox" />
					<select name="searchTypeSelect" class="inputbox" size="1">
						<option value="title" <?php if ($this->controllerData["searchTypeSelect"] == "title") echo "selected" ?>><?php echo $this->text["intitle"]?></option>
						<option value="fulltext" <?php if ($this->controllerData["searchTypeSelect"] == "fulltext") echo "selected" ?>><?php echo $this->text["fulltext"]?></option>
					</select> 
				</td>
			</tr>
			<tr height="5px">
				<td>
					<!-- Hidden inputs, here we submit additional values -->
					<input type="hidden" name="method" id="method" value="showView" />
					<input type="hidden" name="objectId" id="objectId" value="" />
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" value="<?php echo $this->text["search"]?>" />
				<td>
			</tr>
			<tr height="5px">
				<td>					
				</td>
			</tr>
		</table>
	</form>
	
	<!-- Paginable grid patch -->
	<script src="../../includes/activewidgets/patches/paging1.js"></script>

	<!-- grid format -->
	<style>
		.active-controls-grid {height: 100%; font: menu;}
		.active-column-0 { width: 50px }
		.active-column-1 { width: 250px }
		.active-column-2 { width: 200px }
		.active-column-3 { width: 300px }
		.active-grid-column {border-right: 1px solid threedlightshadow;}
		.active-grid-row {border-bottom: 1px solid threedlightshadow;}
	</style>

	<!-- grid data -->
	<script>
	
		var columns = 
		[
			 "ID","<?php echo $this->text["title"]?>", "<?php echo $this->text["published"]?>", "<?php echo $this->text["created"]?>", "<?php echo $this->text["username"]?>"
		];
		
		var data = 
		[
			<?php $separator = false ?>
			<?php $rows = 0;?>
			<?php foreach ($this->controllerData["objects"] as $object) 
			{
				if ($object->canDoAction(null, Action::LIST_OBJECTS_ACTION()) == false) continue;
				$rows++;
				?>
				<?php $user = $object->getCreatedByUser()?>
				<?php if ($separator) echo "," ?>
				[
					"<?php echo $object->getId()?>",
					"<?php echo $object->getTitle()?>",
					"<?php if ($object->getIsPublished()) echo $this->text["yes"]; else echo $this->text["no"];?>",
					"<?php echo $object->getCreated()?>", 
					"<?php echo $user->getName()?>"
				]
				<?php $separator = true ?>
			<?php 
			}?>
		];
			
		// create ActiveWidgets Grid javascript
		var grid = new Active.Controls.Grid;
	
		//	replace the built-in row model with the new one (defined in the patch)
		grid.setModel("row", new Active.Rows.Page);

		// set number of rows/columns
		grid.setProperty("row/count", <?php echo $rows?>);
		grid.setProperty("column/count", 5);
	
		// provide cells and headers text
		grid.setProperty("data/text", function(i, j) { return data[i][j] } );
		grid.setProperty("column/texts", columns);

		//	set page size
		grid.setProperty("row/pageSize", 15);
	
		// set headers width/height
		grid.setRowHeaderWidth("28px");
		grid.setColumnHeaderHeight("20px");
	</script>	
	
	<script language="javascript">

	/**
	 * Gets the selected id
	 * @return id
	 */
	function getSelectedObjectId()
	{
		var index = grid.getSelectionProperty("index");
		if (index < 0)
		{
			alert('<?php echo $this->text["selectObject"]?>');
			return null;
		}
		
		return data[index][0];
	}
	
	/**
	 * Executed when the user pushes the publish button in the toolbar
	 */
	function publishButton_onClick()
	{	
		var id = getSelectedObjectId();
		if (id == null) return;
		
		window.document.getElementById("method").value = "publish";
		window.document.getElementById("objectId").value = id;
		window.document.getElementById("form").submit();
	}
	
	/**
	 * Executed when the user pushes the unpublish button in the toolbar
	 */
	function unpublishButton_onClick()
	{
		var id  = getSelectedObjectId();
		if (id == null) return;
		
		window.document.getElementById("method").value = "unpublish";
		window.document.getElementById("objectId").value = id;
		window.document.getElementById("form").submit();
	}
	
	/**
	 * Executed when the user pushes the edit button in the toolbar
	 */
	function addButton_onClick()
	{		
		window.document.getElementById("method").value = "add";
		window.document.getElementById("form").submit();
	}
	
	/**
	 * Executed when the user pushes the add button in the toolbar
	 */
	function editButton_onClick()
	{
		var id = getSelectedObjectId();
		if (id == null) return;
		
		window.document.getElementById("method").value = "edit";
		window.document.getElementById("objectId").value = id;
		window.document.getElementById("form").submit();
	}
	
	/**
	 * Executed when the user pushes the add button in the toolbar
	 */
	function deleteButton_onClick()
	{
		var id = getSelectedObjectId();
		if (id == null) return;
		
		if (confirm("<?php echo $this->text["deleteconfirmation"]?>") == false) return;
		
		window.document.getElementById("method").value = "delete";
		window.document.getElementById("objectId").value = id;
		window.document.getElementById("form").submit();
	}
	</script>

	<tr height="300px" valign="top">
		<td>
			<script language="javascript">
				if (data.length == 0)
					document.write("<?php echo $this->text["none"]?>");
				else
				{
					// write grid html to the page
					document.write(grid);
				}
			</script>
		</td>
	</tr>
	<tr>
		<td>
				<!-- bottom page control buttons -->
				<div>
					<button onclick='goToPage(-Infinity)'>&lt;&lt;</button>
					<button onclick='goToPage(-1)'>&lt;</button>
					<span id='pageLabel'></span>
					<button onclick='goToPage(1)'>&gt;</button>
					<button  onclick='goToPage(Infinity)'>&gt;&gt;</button>
				</div>


	<!-- button click handler -->
	<script>

		function goToPage(delta)
		{
			var count = grid.getProperty("row/pageCount");
			var number = grid.getProperty("row/pageNumber");

			number += delta;

			if (number < 0) number = 0;
			if (number > count-1) number = count-1;
			document.getElementById("pageLabel").innerHTML = (number + 1) + " / " + count + " ";

			grid.setProperty("row/pageNumber", number);
			grid.refresh();
		}

		// Init in page 0
		goToPage(-Infinity);
	</script>

		</td>
	</tr>
	
</div>

<br />

<?php include $this->loadTemplate('endmain.tpl.php') ?>
<?php include $this->loadTemplate('footer.tpl.php') ?>