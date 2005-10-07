<?php include $this->loadTemplate('header.tpl.php') ?>
<?php include $this->loadTemplate('menu.tpl.php') ?>
<?php include $this->loadTemplate('toolbar.tpl.php') ?>
<?php include $this->loadTemplate('startmain.tpl.php') ?>

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
			 "ID","<?php echo $this->text["title"]?>", "<?php echo $this->text["created"]?>", "<?php echo $this->text["username"]?>"
		];
		
		var data = 
		[
			<?php $separator = false ?>
			<?php foreach ($this->controllerData["objects"] as $object) 
			{
			?>
			<?php $user = $object->getCreatedByUser()?>
			<?php if ($separator) echo "," ?>
			[
				"<?php echo $object->getId()?>",
				"<?php echo $object->getTitle()?>", 
				"<?php echo $object->getCreated()?>", 
				"<?php echo $user->getName()?>"
			]
			<?php $separator = true ?>
			<?php }?>
		];
			
		// create ActiveWidgets Grid javascript
		var grid = new Active.Controls.Grid;
	
		// set number of rows/columns
		grid.setRowProperty("count", <?php echo count($this->controllerData["objects"])?>);
		grid.setColumnProperty("count", 4);
	
		// provide cells and headers text
		grid.setColumnProperty("text", function(i) { return columns[i] } );
		grid.setDataProperty("text", function(i, j) { return data[i][j] } );		
	
		// set headers width/height
		grid.setRowHeaderWidth("28px");
		grid.setColumnHeaderHeight("20px");

		// allow multiple selection
		grid.setSelectionProperty("multiple", true);	
	</script>	
	
	<script language="javascript">

	/**
	 * Executed when the user pushes de publish button in the toolbar
	 */
	function publishButton_onClick()
	{
		var valuesArray = grid.getSelectionProperty("values");
		
		var selectedContents = "";
		for (var i = 0; i < valuesArray.length; i++)
		{
			if (selectedContents != "") selectedContents += ",";
			// Get id
			selectedContents += data[valuesArray[i]][0];
		}
		
		var url = "pending.php?method=publish&selectedContents=" + selectedContents;
		window.location.href = url;
	}
	
	</script>

	<tr height="300px" valign="top">
		<td>
			<script language="javascript">
				if (data.length == 0)
					document.write("<?php echo $this->text["none"]?>");
				else				
					// write grid html to the page
					document.write(grid);
			</script>
		</td>
	</tr>
	
<?php include $this->loadTemplate('endmain.tpl.php') ?>
<?php include $this->loadTemplate('footer.tpl.php') ?>