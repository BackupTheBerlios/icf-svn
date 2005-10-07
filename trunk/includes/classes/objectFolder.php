<?php

/**
 * Represents the relation between an object and the folder that contains it
 *
 * @author despada 2005-03-09
 */
class ObjectFolder
{
	var $id;
	var $objectID;
	var $folderID;
	var $position;
	
	var $folder;
	var $object;
	
	/**
	 * Constructs an ObjectFolder object
	 * @param $objectID int - Object id
	 * @param $folderID int - Folder id
	 */
	function ObjectFolder($objectID = null, $folderID = null)
	{
		$this->objectID = $objectID;
		$this->folderID = $folderID;
		$this->position = null;
		
		$this->folder = null;
		$this->object = null;
	}
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function setObjectID($objectID)
	{
		$this->objectID = $objectID;
	}
	
	function getObjectID()
	{
		return $this->objectID;
	}

	function setFolderID($folderID)
	{
		$this->folderID = $folderID;
	}
	
	function getFolderID()
	{
		return $this->folderID;
	}

	function setPosition($position)
	{
		$this->position = $position;
	}
	
	function getPosition()
	{
		return $this->position;
	}
	
	/**
	 * Gets the folder associated with this ObjectFolder object
	 * @return Folder - a folder object
	 */
	function getFolder()
	{
		if ($this->folder == null)
		{
			$folderMapper = new FolderMapper();
			$this->folder = $folderMapper->get($this->folderID);
		}
		
		return $this->folder;
	}
	
	/**
	 * Gets the object associated to this ObjectFolder
	 * @return Object - an instance of Object class
	 */
	function getObject()
	{
		if ($this->object == null)
		{
			$objectMapper = new ObjectMapper();
			$this->object = $objectMapper->get($this->objectID);
		}
		
		return $this->object;
	}
	
		/**
	 * Switches position with the given ObjectFolder
	 * @param $objectFolder ObjectFolder - ObjectFolder with whom this object is going to switch positions, given by reference
	 */
	function switchPositionWith(&$objectFolder)
	{
		// Switch positions
		$positionUp = $this->getPosition();
		$positionDown = $objectFolder->getPosition();
		
		// Check invalid state...
		if ($positionUp == $positionDown)
			trigger_error("ATTENTION: Invalid state, both folders have the same position number, cannot be switched");
			
		$this->setPosition($positionDown);
		$objectFolder->setPosition($positionUp);
	}

}

?>
