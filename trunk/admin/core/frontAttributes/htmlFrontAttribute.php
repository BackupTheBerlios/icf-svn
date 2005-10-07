<?php

require_once "frontAttributes/frontAttribute.php";

/**
 * Implements a HTML WYSIWYG Editor
 *
 * @author despada
 */
class HtmlFrontAttribute extends FrontAttribute
{
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 */
	function drawWidget()
	{
		?>
		<textarea cols="60" rows="30" class="text_area" mce_editable="true" id="<?php echo $this->getName()?>" name="<?php echo $this->getName()?>"><?php echo $this->getValue()?></textarea>
		<?
	}	
}

?>