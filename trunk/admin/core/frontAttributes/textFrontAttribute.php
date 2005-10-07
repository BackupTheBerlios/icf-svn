<?php

require_once "frontAttributes/frontAttribute.php";

/**
 * Implements a textbox
 *
 * @author despada 2005-05-08
 */
class TextFrontAttribute extends FrontAttribute
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
	 * Determines if the given value is correct for a textbox
	 * @return true if it's correct, false if it is not
	 */
	function isValid()
	{
		if (strlen($this->value) > 255)
			return false;
			
		return true;
	}
}

?>