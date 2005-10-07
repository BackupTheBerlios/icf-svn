<?php

require_once "frontAttributes/frontAttribute.php";

/**
 * Implements an Integer attribute
 *
 * @author despada
 */
class IntegerFrontAttribute extends FrontAttribute
{
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 */
	function drawWidget()
	{
		?>
		<input type="text" maxlength="8" size="8" class="text_area" id="<?php echo $this->getName()?>" name="<?php echo $this->getName()?>" value="<?php echo $this->getValue()?>" />
		<?
	}	
	
	/**
	 * Determines if the given value is correct for a textbox
	 * @return true if it's correct, false if it is not
	 */
	function isValid()
	{
		if ($this->value == null || $this->value == "")
		{
			if ($this->attribute->getIsRequired()) return false;
			return true;
		}

		return is_numeric($this->value);
	}
	
}

?>