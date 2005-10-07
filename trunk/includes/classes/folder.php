<?php

require_once "mappers/folderMapper.php";
require_once "mappers/folderClassMapper.php";
require_once "mappers/permissionMapper.php";
require_once "mappers/objectMapper.php";

/**
 * Representa una carpeta de contenidos
 *
 * @author despada 2005-01-09
 */
class Folder
{
	var $id;
	var $parentID;
	var $title;
	var $shortDescription;
	var $longDescription;
	var $position;
	
	var $objects;
	var $objectFolders;
	var $folderClasses;
	var $children;
	var $parent;
	
	/**
	 * Constructs a Folder object
	 */ 
	function Folder()
	{
		$this->objects = null;
		$this->children = null;
		$this->folderClasses = null;
		$this->parent = null;
	}
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function setParentId($parentID)
	{
		$this->parentID = $parentID;
	}
	
	function getParentId()
	{
		return $this->parentID;
	}
	
	function setTitle($title)
	{
		$this->title = $title;
	}
	
	function getTitle()
	{
		return $this->title;
	}

	function setShortDescription($shortDescription)
	{
		$this->shortDescription = $shortDescription;
	}
	
	function getShortDescription()
	{
		return $this->shortDescription;
	}

	function setLongDescription($longDescription)
	{
		$this->longDescription = $longDescription;
	}
	
	function getLongDescription()
	{
		return $this->longDescription;
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
	 * Gets the ObjectFolder objects assigned to this folder
	 * @return ObjectFolder - ObjectFolder object
	 */
	function getObjectFolders()
	{
		if ($this->objectFolders == null)
		{
			$objectFolderMapper = new ObjectFolderMapper();
			$this->objectFolders = $objectFolderMapper->findByFolder($this->getId());
		}
		
		return $this->objectFolders;
	}
	
	/**
	 * Gets the objects in this folder
	 */
	function getObjects()
	{
		if ($this->objects == null)
		{
			$objectMapper = new ObjectMapper();
			$this->objects = $objectMapper->findByFolder($this->getId());
		}
		
		return $this->objects;
	}
	
	/**
	 * Gets the FolderClass objects
	 * @return Array - FolderClass objects
	 */
	function getFolderClasses()
	{
		if ($this->folderClasses == null)
		{
			$folderClassMapper = new FolderClassMapper();
			$this->folderClasses = $folderClassMapper->findByFolderId($this->getId());
		}
		
		return $this->folderClasses;
	}
	
	/**
	 * Sets the FolderClass objects
	 * @return Array - FolderClass objects
	 */
	function setFolderClasses($folderClassArray)
	{
		$this->folderClasses = $folderClassArray;
	}
	
	/**
	 * Determines if this folder can have instances of a certain class
	 * @param $class BaseClass - BaseClass object
	 * @return true if it can, false if it not
	 */
	
	function isClassAllowed($class)
	{
		if ($this->getFolderClass($class) != null)
			return true;
			
		return false;
	}
	
	/**
	 * Gets the FolderClass object for a given class
	 * @param $class BaseClass - class object
	 * @return FolderClass - a FolderClass object, or null, if it wasn't found
	 */
	function getFolderClass($class)
	{
		foreach ($this->getFolderClasses() as $folderClass)
		{
			if ($folderClass->getClassId() == $class->getId())
				return $folderClass;
		}

		// It doesn't have this object...
		return null;	
	}
	
	/**
	 * Get the children folders of this folder
	 * @return Array - array of folder objects
	 */
	function getChildren()
	{
		if ($this->children == null)
		{
			$folderMapper = new FolderMapper();
			$this->children = $folderMapper->findByParentId($this->getId());
		}
		
		return $this->children;
	}
	
	/**
	 * Gets the parent folder for this folder
	 * @return Folder - folder object, or null, if this folder has no parent
	 */
	function getParent()
	{
		// If it has no parent id, it has no parent object also...
		if ($this->getParentId() == null) return null;
		
		if ($this->parent == null)
		{
			$folderMapper = new FolderMapper();
			$this->parent = $folderMapper->get($this->getParentId());
		}
		
		return $this->parent;
	}
	
		/**
	 * Checks if a certain user can do certain action for one of the classes linked to this folder
	 * @param $user User - user that intends to do action. If null, session user is used
	 * @param $action Action - action to be done
	 * @return boolean - true if the user should be allowed, false if not
	 */
	function canDoAction($user = null, $action)
	{		
		if ($user == null)
		{
			$session = new Session();
			$user = $session->getSessionUser();
		}		
				
		foreach($this->getFolderClasses() as $folderClass)
		{
			if ($folderClass->canDoAction($user, $action)) return true;
		}
		
		return false;
	}
	
	/**
	 * Switches position with the given folder
	 * @param $folder Folder - folder with whom this object is going to switch positions, given by reference
	 */
	function switchPositionWith(&$folder)
	{
		// Switch positions
		$positionUp = $this->getPosition();
		$positionDown = $folder->getPosition();
		
		// Check invalid state...
		if ($positionUp == $positionDown)
			trigger_error("ATTENTION: Invalid state, both folders have the same position number, cannot be switched");
			
		$this->setPosition($positionDown);
		$folder->setPosition($positionUp);
	}
	
	/**
	 * Determines if the given ObjectFolder is the first in the set belonging to this object
	 * @param $objectFolder ObjectFolder - the object
	 * @return boolean - true if it is, false if it is not
	 */
	function isFirstObjectFolder($objectFolder)
	{
		$objectFolders = $this->getObjectFolders();
		assert(count($objectFolders) > 0);
		
		if ($objectFolder->getId() == $objectFolders[0]->getId())
			return true;
			
		return false;
	}
	
	/**
	 * Determines if the given ObjectFolder is the last positioned in the set belonging to this object
	 * @param $objectFolder ObjectFolder - the object
	 * @return boolean - true if it is, false if it is not
	 */
	function isLastObjectFolder($objectFolder)
	{
		$objectFolders = $this->getObjectFolders();
		assert(count($objectFolders) > 0);
		
		if ($objectFolder->getId() == $objectFolders[count($objectFolders) - 1]->getId())
			return true;
			
		return false;
	}
	
	/**
	 * Determines if the given Folder is the first in the set belonging to this object
	 * @param $folder Folder - the object
	 * @return boolean - true if it is, false if it is not
	 */
	function isFirstChild($folder)
	{
		$folders = $this->getChildren();
		assert(count($folders) > 0);
		
		if ($folder->getId() == $folders[0]->getId())
			return true;
			
		return false;
	}
	
	/**
	 * Determines if the given Folder is the last one in the set belonging to this object
	 * @param $folder Folder - the object
	 * @return boolean - true if it is, false if it is not
	 */
	function isLastChild($folder)
	{
		$folders = $this->getChildren();
		assert(count($folders) > 0);
		
		if ($folder->getId() == $folders[count($folders) - 1]->getId())
			return true;
			
		return false;
	}
	
	/**
	 * Gets the maximum folder position + 1 of its children, so a new folder can be added as the last one.
	 * This method is not thread-safe
	 * @return int - the next folder position of its children
	 */
	function getNextFolderPosition()
	{
		$folderArray = $this->getChildren();
		if (count($folderArray) == 0) return 1;
		
		return ($folderArray[count($folderArray) - 1]->getPosition() + 1);
	}

		/**
	 * Gets the maximum ObjectFolder position + 1 of its children, so a new ObjectFolder can be added as the last one.
	 * This method is not thread-safe
	 * @return int - the next ObjectFolder position of its children
	 */
	function getNextObjectFolderPosition()
	{
		$objectFolderArray = $this->getObjectFolders();
		if (count($objectFolderArray) == 0) return 1;
		
		return ($objectFolderArray[count($objectFolderArray) - 1]->getPosition() + 1);
	}
	
	/**
	 * Determines if the present folder is the special image folder 
	 * @return boolean - true if it is
	 */
	function isImageFolder()
	{
		$icfConfig = new IcfConfig();
		
		if ($this->getId() == $icfConfig->cfg_image_folder_id)
			return true;
			
		return false;
	}
	
		/**
	 * Determines if the present folder is the special media folder
	 * @return boolean - true if it is
	 */
	function isMediaFolder()
	{
		$icfConfig = new IcfConfig();
		
		if ($this->getId() == $icfConfig->cfg_media_folder_id)
			return true;
			
		return false;
	}

	/**
	 * Evaluates if the given folder (if it is a leaf) o one of its children
	 * allows certain action for a given class
	 * @param $class BaseClass - Class object
	 * @param $action Action - action object;
	 * @return boolean - true if it does, false if it does not
	 */
	function hasAllowedLeaf($class, $action)
	{
		$folderArray = $this->getChildren();
		
		if (is_null($folderArray) || count($folderArray) == 0)
		{
			// It is a leaf, determine if it has the asked permission
			$folderClass = $this->getFolderClass($class);
			
			// It has not even a link...
			if ($folderClass == null) return false;
			/* @var $folderClass FolderClass */
			return $folderClass->canDoAction(null, $action);
		}
		
		// It is not a folder, have to check its children
		foreach($folderArray as $folder)
		{
			/* @var $folder Folder */
			if ($folder->hasAllowedLeaf($class, $action)) return true;
		}
		
		// It has no child folder allowed, do not display
		return false;
	}
}

?>