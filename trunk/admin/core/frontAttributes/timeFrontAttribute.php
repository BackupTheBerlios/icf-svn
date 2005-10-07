<?php

require_once "frontAttributes/frontAttribute.php";
require_once "util/dateFormatFactory.php";

/**
 * Implements an Time attribute. Its value property only admits a Date object.
 *
 * @author despada
 */
class TimeFrontAttribute extends FrontAttribute
{
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 */
	function drawWidget()
	{
		$dateFormat = DateFormatFactory::getDateFormat();
		
		// Transform my date object if its valid
		if ($this->isValid() && $this->getValue != null)
			$dateString = $dateFormat->toDatetimeString($this->getValue());
		else
			$dateString = $this->value;
			
		?>
		<input type="text" maxlength="23" size="23" class="text_area" id="<?php echo $this->getName()?>" name="<?php echo $this->getName()?>" value="<?php echo $dateString ?>" /> <?php echo $dateFormat->getDatetimeFormatString() ?>
		<?
	}
		
	/**
	 * Determines if the given value is correct for a date textbox. The value
	 * must be an instance of the Date class.
	 * @return true if it's correct, false if it is not
	 */
	function isValid()
	{
		if ($this->required == true && $value == null) return false;
		
		$dateFormat = DateFormatFactory::getDateFormat();
		$date = $dateFormat->parseDatetime($this->value);
		
		if ($date == null) return false;
		
		return true;
	}
	
}

?>