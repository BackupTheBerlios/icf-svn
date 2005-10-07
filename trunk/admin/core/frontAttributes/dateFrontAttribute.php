<?php

require_once "frontAttributes/frontAttribute.php";
require_once "util/dateFormatFactory.php";

/**
 * Implements an Date attribute. Its value property only admits a Date object.
 *
 * @author despada
 */
class DateFrontAttribute extends FrontAttribute
{
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 */
	function drawWidget()
	{
		$dateFormat = DateFormatFactory::getDateFormat();
		
		// Transform my date object if its valid
		if ($this->isValid() && $this->getValue != null)
			$dateString = $dateFormat->toDateString($this->getValue());
		else
			$dateString = $this->value;
		
		?>
		<input type="text" maxlength="10" size="10" class="text_area" id="<?php echo $this->getName()?>" name="<?php echo $this->getName()?>" value="<?php echo $dateString?>" />&nbsp;<input type="button" value="..." onClick="return showCalendar('<?php echo $this->getName()?>', '<?php echo $dateFormat->getCalendarDateFormatString()?>');">&nbsp;<?php echo $dateFormat->getDateFormatString() ?>
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
		$date = $dateFormat->parseDate($this->value);
		
		if ($date == null) return false;
		
		return true;
	}
	
}

?>