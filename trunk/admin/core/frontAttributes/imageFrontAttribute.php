<?php

require_once "frontAttributes/frontAttribute.php";
require_once "service/mediaService.php";

/**
 * Implements a textbox
 *
 * @author despada 2005-05-08
 */
class ImageFrontAttribute extends FrontAttribute
{
	
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 */
	function drawWidget()
	{
		$mediaService = new MediaService();
		$fileArray = $mediaService->listImages();

		$visibility = "hidden";
		if (is_null($this->getValue()) == false)
		 $visibility = "visible";
		 
		?>		
		<select name="<?php echo $this->getName()?>" id="<?php echo $this->getName()?>" onchange="<?php echo $this->getName()?>_onChange()">
			<option value=""></option>
			<?php foreach($fileArray as $file)
			{ 
				/* @var $file File */
				$selected = "";
				if ($file->getHttpPath() == $this->getValue()) $selected = "selected=\"selected\"";
			?>			
			<option <?php echo $selected?> value="<?php echo $file->getHttpPath()?>"><?php echo $file->getTitle()?></option>
			<?php }?>
		</select>
		
		<div name ="<?php echo $this->getName()?>Div" id="<?php echo $this->getName()?>Div">
			<a href="#" onclick="openPreview('<?php echo $this->getValue()?>', 'modal=yes,location=no,menubar=no,directories=no,toolbar=no,status=no,width=100,height=100'); return false" style="visibility:<?php echo $visibility?>">Preview</a>
		</div>
		
		<script type="text/javascript">
		
			function openPreview(url)
			{
				window.open(url, null, 'modal=yes,location=no,menubar=no,directories=no,toolbar=no,status=no,width=300,height=300');
			}
		
			function <?php echo $this->getName()?>_onChange()
			{
				var select = document.getElementById("<?php echo $this->getName()?>");
				var div = document.getElementById("<?php echo $this->getName()?>Div");
				
				// Neutral value selected ?
				if (select.value == "")
				{
					div.style.visibility = "hidden";
					return;
				}
				
				// Show preview
				div.innerHTML = "<a href=\"#\" onclick=\"openPreview('" + select.value + "', 'modal=yes,location=no,menubar=no,directories=no,toolbar=no,status=no,width=100,height=100'); return false\">Preview</a>";
				div.style.visibility = "visible";
			}
			
		</script>
		<?
	}
	
	/**
	 * Determines if the given value is correct for a file
	 * @return true if it's correct, false if it is not
	 */
	function isValid()
	{
		return true;
	}
}

?>