<?php $icfConfig = new IcfConfig(); ?>
	<table class="menubar" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody>
				<tr>
					<td class="menubackgr">
						<div id="myMenuID"></div>

						<script language="JavaScript" type="text/javascript">

							var myMenu =
							[
								[null,'<?php echo $this->text["mnuHome"]?>','home.php','_self',null],
								[null,'<?php echo $this->text["mnuContents"]?>',null,'_self',null,
								<?php $separator = false ?>
								<?php foreach ($this->menu->getContents() as $class) {?>
								<?php if ($separator) echo ","?>
								[null,'<?php echo $class->getTitle()?>','list.php?classIdSelect=<?php echo $class->getId()?>','_self',null]
								<?php $separator = true;?>
								<?}?>
								],
								[null,'<?php echo $this->text["mnuFiles"]?>',null,'_self',null,
									[null,'<?php echo $this->text["mnuImages"]?>','folder.php?folderId=<?php echo $icfConfig->cfg_image_folder_id?>','_self',null],
									[null,'<?php echo $this->text["mnuMedia"]?>','folder.php?folderId=<?php echo $icfConfig->cfg_media_folder_id?>','_self',null]
								],
								[null,'<?php echo $this->text["mnuFolders"]?>',null,'_self',null,
								<?php $separator = false ?>
								<?php foreach ($this->menu->getFolders() as $folder) {?>
									<?php if ($separator) echo ","?>
								[null,'<?php echo $folder->getTitle()?>','folder.php?folderId=<?php echo $folder->getId()?>', '_self',null]
									<?php $separator = true;?>
								<?}?>
								]
							];

							cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
						</script>
					</td>

					<td class="menubackgr" align="right">
						<div id="wrapper1">
							<div>&nbsp;</div>
						</div>
					</td>

					<td class="menubackgr" align="right"><a href="useredit.php?id=<?php echo $this->user->getId()?>"><strong><?php echo $this->user->getName()?></strong></a>&nbsp;
					</td>
				</tr>
			</tbody>
		</table>
