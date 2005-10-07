<?php

require_once "service/objectService.php";
require_once "service/objectServiceFactory.php";

/**
 * Service object for the News class, gives access to the GUI programmer to Business Logic
 * coordinating transactions.
 */
class NewsService extends ObjectService
{	
	
	/**
	 * Updates an object
	 * @param $object Object to update, must have its ID set
	 */
	function publish($id)
	{		
		echo "Employing NewsService";	
		ObjectService::publish($id);
	}	
}

?>