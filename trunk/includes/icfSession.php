<?php

require_once "classes/user.php";

/**
 * Represents the ICF user session data. It holds the data, serialize and
 * deserialize into a string.
 */
class Session
{
	var $valid;
	
	/**
	 * Constructs a new session for a given user
	 *
	 * @param $loggedUser optional. If proportioned, the user that is going to open a new session. If not
	 * 										proportioned, then the class tries to recreate a session object from the PHP session state.
	 *										After constructing the object, verify it with the isValid method to determine if
	 *										the user had a session initialized.
	 */
	function Session($loggedUser = null)
	{
		// Use @ to stop PHP from issuing notices... it requires to call this funcion always, but
		// issues a notice if you do it...
		@session_start();
		
		if ($loggedUser == null)
		{
			// Try to reconstruct the session
			$this->reconstructSession();
		}
		else		
		{
			// Register the new session
			$this->createNewSession($loggedUser);
		}
	}
	
	/**
	 * Constructs a session from the serialized data in the PHP session
	 *
	 * After constructing the object, verify it with the isValid method to determine if the user
	 * had a session initialized.
	 */
	function reconstructSession()
	{		
		$this->valid = isset($_SESSION["user"]);
		
		// DEBUG echo "reconstructSession, user: " . $_SESSION["user"] . "<br>";
	}
	
	/**
	 * Creates a new session for a user
	 */
	function createNewSession($loggedUser)
	{
	  $_SESSION["user"] = $loggedUser;
	  
	  // DEBUG echo "createNewSession, user: " . $_SESSION["user"]->get_name() . "<br>";
		// DEBUG echo "<script language='javascript'>alert('createNewSession, isset: " . isset($_SESSION["user"]) . "')</script>";		
	}

	/**
	 * Determines if the present session is valid or is empty. Tipically, is empty when the user
	 * has not yet logged in to the system.
	 *
	 * @return boolean - true if is valid, false if is not
	 */
	function isValid()
	{
		return $this->valid;
	}
	
	/**
	 * Gets the user logged in to the session. 
	 *
	 * @return User - The user asked, or null if the session is not valid.
	 */ 
	function getSessionUser()
	{
		if ($this->isValid() == false)
			return null;

		// DEBUG echo "getSessionUser, username:" . $_SESSION["user"] . "<br>";
			
		return $_SESSION["user"];
	}
}
?>