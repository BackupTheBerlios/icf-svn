<?php

require_once "frontAttributes/frontAttribute.php";
require_once "mappers/userMapper.php";

/**
 * Implements an Email attribute.
 *
 * @author despada
 */
class UserFrontAttribute extends FrontAttribute
{
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 */
	function drawWidget()
	{	
		$userMapper = new UserMapper();
		$userArray = $userMapper->getAll();
		
		?>
		<select name="<?php echo $this->getName()?>" id="<?php echo $this->getName()?>">
			<option value=""></option>
			<?php foreach($userArray as $user) 
			{ 
				/* @var $user User */
				$selected = "";
				if ($user->getId() == $this->getValue()) $selected = "selected=\"selected\"";
			?>			
			<option <?php echo $selected?> value="<?php echo $user->getId()?>"><?php echo $user->getName()?></option>
			<?php }?>
		</select>
		<?
	}
		
	/**
	 * Determines if the given value is correct for a User
	 * @return true if it's correct, false if it is not
	 */
	function isValid()
	{
		return is_numeric($this->getValue());
	}
	
}

?>