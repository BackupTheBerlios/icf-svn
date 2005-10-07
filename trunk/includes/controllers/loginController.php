<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/
require_once "mappers/userMapper.php";
require_once "icfHorizontal.php";

/**
 * Login feature controller
 */
class LoginController
{
	var $icfTemplating;
  var $tpl;
  var $controllerData;
  
	/**
	 * Cosntructs LoginController and calls the method issued by parameter
	 *
	 * @param $method Name of the method to call
	 * @param $tpl Template engine implementation
	 */
	function LoginController($method = null, $icfTemplating)
	{
		$this->icfTemplating = $icfTemplating;
		$this->tpl = $icfTemplating->getTpl();
		
		switch ($method)
		{
			case "login":
			{
				$this->login();
				break;
			}
			case "unauthorized_access":
			{
				$this->unauthorized_access();
			}
			
		default:
			$this->show_view();
		}
	}
		
	/**
	 * Shows the login view
	 */
	function show_view()
	{		
		
		$this->tpl->assign("controllerData", $this->controllerData);
		$this->tpl->display("login.tpl.php");
	}
		
	/**
	 * Shows the home view
	 */
	function show_home_view()
	{
		echo "<script language=javascript>window.location.href='home.php'</script>";
	}
	
	/**
	 * Shows the adequate view for a user that tried to access the site without the appropiate clearance
	 */
	function unauthorized_access()
	{
		$text = $this->icfTemplating->getText();
		
		$this->controllerData["loginfailed"] = $text["incorrectinput"];
		$this->show_view();
	}

	
	/**
	 * Tries to log the user using the username and password received in the post.
	 */
	function login()
	{
		$text = $this->icfTemplating->getText();
				
		$username = $_POST["username"];
		$password = $_POST["password"];
	
		// No data for login... 
		if ($username == "")
		{
			$this->controllerData["loginfailed"] = $text["incorrectinput"];
			$this->show_view();
			return;
		}
			
		// Get the user
		$userMapper = new UserMapper();
		$user = $userMapper->findByName($username);
		
		if ($user != null)
		{
			// The user exists, validate the password
			$login = $user->login($password);
			
			if ($login)
			{
				// User logged in !! register it in the session
				$session = new Session($user);
				
				// Redirect to home
				$this->show_home_view();
				return;
			}
		}
		
		// The login has failed, send the error
		$this->controllerData["loginfailed"] = $text["loginfailed"];
		// .. and display the data that the user gave to us
		$this->controllerData["username"] = $username;
		$this->controllerData["password"] = $password;
		
		$this->show_view();
	}
}


?>