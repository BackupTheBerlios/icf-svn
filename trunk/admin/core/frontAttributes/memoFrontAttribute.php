<?php

require_once "frontAttributes/frontAttribute.php";

/**
 * Implements a memo
 *
 * @author despada
 */
class MemoFrontAttribute extends FrontAttribute
{
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 */
	function drawWidget()
	{
		?>
		<textarea cols="45" rows="5" class="text_area" id="<?php echo $this->getName()?>" name="<?php echo $this->getName()?>"><?php echo $this->getValue()?></textarea>
		<?
	}	
}

?>