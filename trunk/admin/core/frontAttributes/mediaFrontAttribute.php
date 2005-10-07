<?php

require_once "frontAttributes/frontAttribute.php";
require_once "service/mediaService.php";

/**
 * Implements a textbox
 *
 * @author despada 2005-05-08
 */
class MediaFrontAttribute extends FrontAttribute
{
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 */
	function drawWidget()
	{
		$mediaService = new MediaService();
		$fileArray = $mediaService->listMedia();
		
		?>
		<select name="<?php echo $this->getName()?>" id="<?php echo $this->getName()?>">
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