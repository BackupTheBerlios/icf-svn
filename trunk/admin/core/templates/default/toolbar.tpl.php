		<table class="menubar" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody>
				<tr>

					<td class="menudottedline" align="right">
						<script language="JavaScript" type="text/JavaScript">
							<!--
							function MM_swapImgRestore() { //v3.0
							var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
							}
							//-->
						</script>

						<table border="0" cellpadding="3" cellspacing="0">
							<tbody>
								<tr>

								<?php foreach ($this->toolbar->toArray() as $item):
									renderToolbarItem($item, $this->templatePath);
								endforeach; ?>

								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>

<?php function renderToolbarItem($item, $templatePath) { ?>

	<td>
		<a class="toolbar" href="<?php echo $item->url;?>" onclick="<?php echo $item->getOnclick()?>"> 
			<img src="<?php echo $templatePath?><?php echo $item->image2;?>" alt="<?php echo $item->title;?>" name="<?php echo $item->name;?>" align="middle" border="0" /><?php echo $item->title;?>
		</a>&nbsp;
	</td>

<?php }?>