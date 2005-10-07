<?php

require_once "frontAttributes/frontAttribute.php";

/**
 * Implements a Boolean attribute.
 *
 * @author despada
 */
class BooleanFrontAttribute extends FrontAttribute
{
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 */
	function drawWidget()
	{
		$checked = "";
		if ($this->getValue()) $checked = "checked=\"checked\"";
		
		?>
		<input type="checkbox" id="<?php echo $this->getName()?>" name="<?php echo $this->getName()?>" <?php echo $checked?> value="-1" />
		<?
	}
	
	/**
	 * Redefines collectWidget for collecting a Date
	 * @param $required boolean - this particular frontAttribute ignores it, because a boolean cannot be null
	 * @return true if the widget was successfully collected, false if the received value was not correct
	 */
	function collectWidget()
	{
		$value = $_REQUEST[$this->getName()];
		
		// Format value
		if ($value == "-1" || $value == "1") 
			$this->value = true;
		else 
			$this->value = false;
	}
	
	/**
	 * Determines if the given value is correct for a checkbox. The value must be a boolean.
	 * @return true if it's correct, false if it is not
	 */
	function isValid()
	{
		return true;
	}
	
}

?>