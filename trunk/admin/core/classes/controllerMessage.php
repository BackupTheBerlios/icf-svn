<?php

/**
 * A message sent from the MVC controller to the user. You can use this class
 * to display messages to the user in any view of ICF. 
 *
 * To use the feature, just create an array, push
 * a number of instances of this ControlerMessage class to it, and save the array
 * in the template engine with the name "controllerMessageArray"
 *
 * @author despada
 */
class ControllerMessage
{
	var $message;
	var $type;
	
	/**
	 * Gets the "error" type of message
	 * @return Object - error type of message
	 * @static
	 */
	function getErrorType()
	{
		return "error";
	}
	
	/**
	 * Gets the "info" type of message
	 * @return Object - info type of message
	 * @static
	 */
	function getInfoType()
	{
		return "info";
	}
	
	/**
	 * Constructs a ControllerMessage, setting its type to "info" by default
	 * @param $message Message to be displayed
	 * @param $type Type of the message, info type is the default
	 */
	function ControllerMessage($message, $type = "info")
	{
		$this->message = $message;
		$this->type = $type;
	}
	
	/**
	 * Gets the message to be displayed
	 * @return String - message
	 */
	function getMessage()
	{
		return $this->message;
	}
	
	/**
	 * Sets the message to be displayed
	 * @param $message String - message
	 */
	function setMessage($message)
	{
		$this->message = $message;
	}
	
	/**
	 * Type of message, one of the types specified by static members
	 * @param $type Object - type of message
	 */
	function setType($type)
	{
		$this->type = $type;
	}
	
	/**
	 * Type of message, one of the types specified by static members
	 * @return Object - type of message
	 */
	function getType()
	{
		return $this->type;
	}
}

?>