<?php

require_once "service/objectService.php";
require_once "service/newsService.php";

/**
 * Constructs new ObjectService instances
 */
class ObjectServiceFactory
{
	/**
	 * Constructs the adequate service object for a given class
	 * @param $class BaseClass - a base class object
	 * @return ObjectService - an ObjectService instance
	 */
	function newInstance($class)
	{
		$className = $class->getClassName();
		
		if ($className == null || $className == "")
		{
			// No predefined service class
			return new ObjectService();
		}
		
		$objectService = null;
		$code = "\$objectService = new " . $className . "();";		
		eval($code);
		
		if (is_null($objectService)) trigger_error("Could not create ObjectService implementor: " . $code);
		return $objectService;
	}
}

?>