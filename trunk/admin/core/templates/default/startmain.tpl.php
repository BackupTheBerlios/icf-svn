<?php require_once "classes/controllerMessage.php" ?>

	<br>
		<div align="center">
			<div class="main">
				<table class="adminheading" border="0">
					<tbody>
						<tr>
							<th class="cpanel"><?php echo $this->pageTitle?></th>
						</tr>
						<tr>
							<td>
								<?php if ($this->controllerMessageArray != null) {?>
									<?php foreach ($this->controllerMessageArray as $controllerMessage) {?>
										<span class="<?php echo $controllerMessage->getType()?>"><?php echo $controllerMessage->getMessage()?></span><br/>
									<?php }?>
								<?php }?>
							</td>
						</tr>
						<tr>
							<td>
								<table border="0" class="adminform">
									<tbody>