<table class="<?php echo $this->table->tableClass;?>">

	<tr>
		<?php if ($this->table->selectableRows):?>
			<td class="<?php echo $this->table->toogleButtonClass;?>">
				<input type="checkbox" name="toggle"/>
			</td>
		<?endif;?>

		<?php foreach ($this->table->cells as $key => $item): 
			echo "<td class='". $item->cssClass . "' width='". $item->width . "' align='" . $item->align . "' " . $item->custom  . ">". $item->title . "</td>";
		endforeach; ?>

	</tr>

		<?php
		
		$i = 0;
		$rs = $this->table->data;

		while (!$rs->EOF) {
			if ($i % 2 == 0):
				echo '<tr class="' . $this->table->evenRowClass . '">';
			else:
				echo '<tr class="' . $this->table->oddRowClass . '">';
			endif;
			
			echo '<td><input type="checkbox" name="toggle"/></td>';
	
			foreach ($this->table->cells as $key => $item): 
				echo '<td>' . $rs->fields[$item->expression]  .'</td>';
			endforeach; 

			echo '</tr>';
			$i++;

			$rs->MoveNext();
		}?>


	</table>

	<table class="adminlist">
		<tr>
			<th colspan="3" align="center">
				<span class="pagenav"><< Start</span>
				<span class="pagenav">< Previous</span>
				<span class="pagenav"> 1 </span>
				<span class="pagenav">Next ></span>
				<span class="pagenav">End >></span>
			</th>
		</tr>

		<tr>
			<td nowrap="true" width="48%" align="right">
				Display #
			</td>
			
			<td>
				<select name="limit" class="inputbox" size="1" onchange="document.adminForm.submit();">
					<option value="5">5</option>
					<option value="10">10</option>
					<option value="15">15</option>
					<option value="20" selected="selected">20</option>
					<option value="25">25</option>
					<option value="30">30</option>
					<option value="50">50</option>
				</select>

				<input type="hidden" name="limitstart" value="0" />
			</td>
			
			<td nowrap="true" width="48%" align="left">
				Results 1 - 10 of 10
			</td>
		</tr>
	</table>
