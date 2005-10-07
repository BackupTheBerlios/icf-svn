<?php

$sessionFilter = new SessionFilter();
$sessionFilter->execute();

/**
 * Filters all the requests to the site and redirects the request to the login page
 * if the user is not logged
 */
class SessionFilter
{
	/**
	 * Executes this filter.
	 */
	function execute()
	{
		$icfConfig = new IcfConfig();	
		
		// Check if the request is for another resource than login. Only if it is we should check the session
		if ($_SERVER["PHP_SELF"] == $icfConfig->cfg_page_login)
			return;
					
		$session = new Session();
		
		if ($session->isValid() != true)
		{
			// echo "<script language=javascript>window.location.href='" . $icfConfig->cfg_page_login . "?method=unauthorized_access'</script>";
			echo "<script language=javascript>alert('No hay sesion !!')</script>";
		}		
	}
}

?>