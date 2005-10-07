<?php

/**
 * Represents a File object
 */
class File
{		
	var $name;
	var $httpPath;	
	var $fsPath;
	var $title;
	
	/**
	 * Constructs a new representation of a file Object from an ICF Object
	 * @param $object Object - object that represents the file
	 * @param $pathAttributeName String - unique name of the attribute that holds the HTTP path to the file
	 * @param $imagePathFs String - Filesystem path stated in the configuration file
	 */
	function File($object, $pathAttributeName, $imagePathFs)
	{
		$icfConfig = new IcfConfig();
			
		/* @var $object Object */
		$objectAttribute = $object->findAttributeByName($pathAttributeName);
		/* @var $objectAttribute ObjectAttribute */			
		$this->httpPath = $objectAttribute->getValue();
		$objectAttribute = $object->findAttributeByName("TITLE");
		$this->title = $objectAttribute->getValue();
		// Name
		$this->name = $this->extractName($this->httpPath);					
		// Fspath
		$this->fsPath = $icfConfig->imagePathFs . "/" . $this->name;			
	}
		
	/**
	 * Gets a title that can be shown to the user
	 * @return String - string title
	 */
	function getTitle()
	{
		return $this->title;
	}
	
	function getName()
	{
		return $this->name;
	}
	
	function getHttpPath()
	{
		return $this->httpPath;
	}
		
	function getFsPath()
	{
		return $this->fsPath;
	}	
	
	/**
	 * Extracts the name of a file from a HTTP path
	 * @return String - string name
	 * @abstract private
	 */
	function extractName($httpPath)
	{
		$pos = strrpos($httpPath, "/");
		if ($pos == false) trigger_error("Path not valid: " . $httpPath);
		return substr($httpPath, ++$pos);
	}
}

?>