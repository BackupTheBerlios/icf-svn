<?php

require_once "frontAttributes/frontAttribute.php";

/**
 * Implements an Email attribute.
 *
 * @author despada
 */
class UrlFrontAttribute extends FrontAttribute
{
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 */
	function drawWidget()
	{			
		?>
		<input type="text" maxlength="255" size="20" class="text_area" id="<?php echo $this->getName()?>" name="<?php echo $this->getName()?>" value="<?php echo $this->getValue()?>" />
		<?
	}
		
	/**
	 * Determines if the given value is correct for a boolean textbox.
	 * @return true if it's correct, false if it is not
	 */
	function isValid()
	{
		$pattern = "[A-Z0-9]*://[/A-Z0-9\.\-_\\]*";
		if (eregi($pattern, $this->value) == false) return false;
		return true;
	}
	
}

?>