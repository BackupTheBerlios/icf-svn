<?php

require_once "mappers/userMapper.php";
require_once "mappers/languageMapper.php";
require_once "mappers/objectFolderMapper.php";

/**
 * Represents an object managed by the ICF. This object can be shown in the CMS in many views.
 *
 * @author despada 2005-04-09
 */
class Object
{
	var $id;
	var $updatedBy;
	var $createdBy;
	var $classID;
	var $title;
	var $created;
	var $updated;
	var $startPublishing;
	var $endPublishing;
	var $hits;
	var $fullTextIndex;
	var $isPublished;

	var $updatedByUser;
	var $createdByUser;
	var $class;
	
	var $objectRelations;
	var $attributes;
	var $objectFolders;

	/**
	 * Constructs a new ICF Object
	 */
	function Object()
	{
		$this->class = null;
		$this->createdByUser = null;
		$this->updatedByUser = null;
		
		$this->objectRelations = null;
		$this->attributes = null;
		$this->objectFolders = null;
	}
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function setUpdatedBy($updatedBy)
	{
		$this->updatedBy = $updatedBy;
	}
	
	function getUpdatedBy()
	{
		return $this->updatedBy;
	}

	function setCreatedBy($createdBy)
	{
		$this->createdBy = $createdBy;
	}
	
	function getCreatedBy()
	{
		return $this->createdBy;
	}

	function setClassID($classID)
	{
		$this->classID = $classID;
	}
	
	function getClassID()
	{
		return $this->classID;
	}
		
	/**
	 * Returns the title, looking it up in the ObjectAttribute array
	 * @return string - title of the object
	 */
	function getTitle()
	{
		$objectAttributeArray = $this->getAttributes();
		
		$languageMapper = new LanguageMapper();
		$language = $languageMapper->getMain();
		
		$class = $this->getClass();
		/* @var $class BaseClass */
		$titleClassAttributeArray = $class->getTitleClassAttributes();

		$descriptor = $class->getDescriptor();
		foreach($titleClassAttributeArray as $titleClassAttribute)
		{
			/* @var $titleClassAttribute ClassAttribute */
			/* @var $objectAttribute ObjectAttribute */
			$objectAttribute = $this->getAttributeForLanguage($titleClassAttribute->getId(), $language->getId());
			// echo "ObjectAttribute: " . $objectAttribute . "-";
			$descriptor = str_replace("<" . $titleClassAttribute->getName() . ">", $objectAttribute->getValue(), $descriptor);
		}
		
		// Return the descriptor with the fields replaced		
		return $descriptor;
	}
	
	function setCreated($created)
	{
		$this->created = $created;
	}
	
	function getCreated()
	{
		return $this->created;
	}

	function setUpdated($updated)
	{
		$this->updated = $updated;
	}
	
	function getUpdated()
	{
		return $this->updated;
	}

	function setStartPublishing($startPublishing)
	{
		$this->startPublishing = $startPublishing;
	}
	
	function getStartPublishing()
	{
		return $this->startPublishing;
	}

	function setEndPublishing($endPublishing)
	{
		$this->endPublishing = $endPublishing;
	}
	
	function getEndPublishing()
	{
		return $this->endPublishing;
	}

	function setHits($hits)
	{
		$this->hits = $hits;
	}
	
	function getHits()
	{
		return $this->hits;
	}

	function setFullTextIndex($fullTextIndex)
	{
		$this->fullTextIndex = $fullTextIndex;
	}
	
	function getFullTextIndex()
	{
		return $this->fullTextIndex;
	}

	function setIsPublished($isPublished)
	{
		$this->isPublished = $isPublished;
	}
	
	function getIsPublished()
	{
		return $this->isPublished;
	}
	
	/**
	 * Gets this object's class
	 * @return BaseClass - an instance of the BaseClass class
	 */
	function getClass()
	{
		if ($this->class == null)
		{
			$baseClassMapper = new BaseClassMapper();
			$this->class = $baseClassMapper->get($this->classID);
		}
		
		return $this->class;
	}
	
	/**
	 * Gets the last user that updated this object
	 *
	 * @return User object
	 */
	function getUpdatedByUser()
	{
		if (is_null($this->getUpdatedBy())) return null;
		
		if (is_null($this->updatedByUser))
		{
			$userMapper = new UserMapper();
			$this->updatedByUser = $userMapper->get($this->getUpdatedBy());
		}		
		return $this->updatedByUser;
	}
	
	/**
	 * Gets the user that created this object
	 *
	 * @return User object
	 */
	function getCreatedByUser()
	{
		if (is_null($this->createdByUser))
		{
			$userMapper = new UserMapper();
			$this->createdByUser = $userMapper->get($this->getCreatedBy());
		}
		return $this->createdByUser;
	}
	
	/**
	 * Obtains the children objects of this object
	 * 
	 * @return Array of ObjectRelation objects
	 */
	function getObjectRelations()
	{
		if (is_null($this->objectRelations))
		{
			$objectRelationMapper = new ObjectRelationMapper();
			$this->objectRelations = $objectRelationMapper->findByParentId($this->getId());
		}
		
		return $this->objectRelations;
	}
	
	/**
	 * Sets new object relations
	 * @param $objectRelationArray Array - list of ObjectRelation objects
	 */
	function setObjectRelations(&$objectRelationArray)
	{
		$this->objectRelations =& $objectRelationArray;
	}
	
	/**
	 * Determines if this object has a concrete relationship to another
	 * @param $childID int - child object id 
	 * @return boolean - true if it has
	 */
	function hasObjectRelation($childID)
	{
		if (is_null($this->getObjectRelation($childID)))
			return false;
		
		return true;
	}
	
	/**
	 * Gets an ObjectRelation to another Object. If it does not exist, returns null
	 * @param $childID int - child object id
	 * @return ObjectRelation - ObjectRelation object
	 */
	function getObjectRelation($childID)
	{
		$objectRelationArray = $this->getObjectRelations();
		
		foreach ($objectRelationArray as $objectRelation)
		{
			/* @var $objectRelation ObjectRelation */
			if ($objectRelation->getChildID() == $childID)
				return $objectRelation;
		}
		
		return null;
	}
	
	/**
	 * Obtains the ObjectAttribute values of this object
	 *
	 * @return Array of ObjectAttribute objects
	 */
	function getAttributes()
	{
		if (is_null($this->attributes))
		{
			$objectAttributeMapper = new ObjectAttributeMapper();
			$this->attributes = $objectAttributeMapper->findByObjectId($this->getId());
		}
			
		return $this->attributes;
	}
	
	/**
	 * Sets new ObjectAttribute values for this object
	 * @param $attributeArray array - attribute arrya
	 */
	function setAttributes($attributeArray)
	{
		$this->attributes = $attributeArray;
	}
	
	/**
	 * Gets the ObjectAttribute that has the given id and belongs to the specified language
	 *
	 * Please note that is possible that this method does not find your ObjectAttribute instance, mainly
	 * because a new ClassAttribute could have been created after this object was created. Your code
	 * must accept the fact that this method may return null.
	 *
	 * @param $classAttributeId int - ClassAttribute object id
	 * @param $languageId int - Language object id
	 * @return ObjectAttribute object, or null, if it doesn't exist
	 */
	function getAttributeForLanguage($classAttributeId, $languageId)
	{	
		$attributeArray = $this->getAttributes();
		
		foreach($attributeArray as $attribute)
		{
			if (($attribute->getClassAttributeID() == $classAttributeId) && ($attribute->getLanguageID() == $languageId))
			{				
				// Found it !! return the object
				return $attribute;
			}
		}
		
		// Couldn't find it
		return null;
	}
	
	/**
	 * Obtains the objectFolder objects relationed to this object
	 * @return array - array of ObjectFolder objects
	 */
	function getObjectFolders()
	{
		if (is_null($this->objectFolders))
		{
			$objectFolderMapper = new ObjectFolderMapper();
			$this->objectFolders = $objectFolderMapper->findByObject($this->getId());
		}
		
		return $this->objectFolders;
	}
	
	/**
	 * Saves the ObjectFolder objects for this object
	 * @param $objectFolderArray array - array of objectFolder objects
	 */
	function setObjectFolders(&$objectFolderArray)
	{
		$this->objectFolders = $objectFolderArray;
	}
	
	/**
	 * Determines if this object is present in a folder
	 * @param $folderId int - Id of the folder
	 * @return boolean - true if it is, false if it isn't
	 */
	function isObjectInFolder($folderId)
	{
		$objectFolders = $this->getObjectFolders();
		
		foreach($objectFolders as $objectFolder)
		{						
			if ($objectFolder->getFolderID() == $folderId)
				return true;
		}
		
		return false;
	}
	
	/**
	 * Determines if the given user can do certain action. This is determined
	 * examining the folders that this object is in and thus extracting the permissions.
	 * @param $user User instance, tipically from the session, If $user is null, the user from session is used.
	 * @param $action Action instance, obtained from one of its static accesors
	 * @return true if the user is allowed, false if it is not
	 */
	function canDoAction($user = null, $action)
	{
		if ($user == null)
		{
			$session = new Session();
			$user = $session->getSessionUser();
		}
		
		$objectFoldersArray = $this->getObjectFolders();
		
		foreach ($objectFoldersArray as $objectFolder)
		{
			/* @var $objectFolder ObjectFolder */
			$folder = $objectFolder->getFolder();
			$folderClass = $folder->getFolderClass($this->getClass());
			
			// If it has no record for this class, continue (shouldn't happen unless the user erases a folderClass previously owned)
			if ($folderClass == null) continue;
			
			if ($folderClass->canDoAction($user, $action)) return true;
		}
		
		return false;
	}
	
	/**
	 * Finds an attribute by its name
	 * @param $name String - attribute key name
	 * @param $language Language - Optional, if not given, the main language is used
	 * @return ObjectAttribute - ObjectAttribute or null, if it couldn't be found
	 */
	function findAttributeByName($name, $language = null)
	{
		if (is_null($language))
		{
			$languageMapper = new LanguageMapper();
			$language = $languageMapper->getMain();
		}
		
		$objectAttributeArray = $this->getAttributes();
		foreach($objectAttributeArray as $objectAttribute)
		{
			/* @var $objectAttribute ObjectAttribute */
			$classAttribute = $objectAttribute->getClassAttribute();
			/* @var $classAttribute ClassAttribute */
			if ($objectAttribute->getLanguageID() == $language->getId() && $classAttribute->getName() == $name) return $objectAttribute;
		}
		
		// Couldn't find it
		return null;
	}
}

?>