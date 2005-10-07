<?php

require_once "mappers/classRelationMapper.php";
require_once "mappers/classAttributeMapper.php";

/**
 * Content class, it gives shape to the content.
 *
 * @author despada 2005-04-06 07:12 AM
 */
class BaseClass
{
	var $id;
	var $title;
	var $shortDescription;
	var $longDescription;
	var $className;
	var $descriptor;
	
	var $attributes;
	var $classRelations;
	var $folderClasses;
	var $objects;
	
	/**
	 * Constructs a BaseClass object
	 */
	function BaseClass()
	{
		$this->attributes = null;
		$this->classRelations = null;
		$this->folderClasses = null;
		$this->objects = null;
	}
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function setTitle($Title)
	{
		$this->title = $Title;
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

	function setClassName($className)
	{
		$this->className = $className;
	}
	
	function getClassName()
	{
		return $this->className;
	}

	function setDescriptor($descriptor)
	{
		$this->descriptor = $descriptor;
	}
	
	function getDescriptor()
	{
		return $this->descriptor;
	}

	// one-to-many
	
	/**
	 * Gets the attributes collection
	 */
	function getAttributes()
	{
		if ($this->attributes == null)
		{
			$classAttributeMapper = new ClassAttributeMapper();
			$this->attributes = $classAttributeMapper->findByClassId($this->id);
		}
	
		return $this->attributes;
	}
	
	/**
	 * Given a language, it returns all the attributes that must be filled for it
	 * @param $language Language - Language object
	 * @return array - array of ClassAttribute objects
	 */
	function getAttributesForLanguage($language)
	{
		if ($language->getIsMain())
		{
			// If its the main language, return all atributes
			return $this->getAttributes();
		}
		
		// If its not the main language, just return those "translatables"
		$attributes = $this->getAttributes();
		
		$translatableAttributes = array();
		foreach ($attributes as $attribute)
		{
			if ($attribute->getIsTranslatable()) array_push($translatableAttributes, $attribute);
		}
		return $translatableAttributes;
	}
	
	/**
	 * Gets the relations of this object
	 */
	function getClassRelations()
	{
		if ($this->classRelations == null)
		{
			$classRelationsMapper = new ClassRelationMapper();
			$this->classRelations = $classRelationsMapper->findByParentId($this->getId());			
		}
		
		return $this->classRelations;
	}
	
	/**
	 * Gets FolderClass objects assigned to this class
	 * @return Array - array of FolderClass objects
	 */
	function getFolderClasses()
	{
		if ($this->folderClasses == null)
		{
			$folderClassMapper = new FolderClassMapper();
			$this->folderClasses = $folderClassMapper->findByClassId($this->id);
		}
		
		return $this->folderClasses;
	}
	
	/**
	 * Gets the ClassAttribute objects assigned in the descriptor as fields. If there is no description, TITLE is looked up
	 * @return Array - array of ClassAttribute objects
	 */
	function getTitleClassAttributes()
	{
		$languageMapper = new LanguageMapper();
		$language = $languageMapper->getMain();
		
		$classAttributeArray = $this->getAttributesForLanguage($language);
		
		// If it has no descriptor, search for a TITLE attribute
		if ($this->getDescriptor() == null || $this->getDescriptor() == "")
		{
			foreach($classAttributeArray as $classAttribute)
			{
				/* @var $classAttribute ClassAttribute */
				if ($classAttribute->getName() == "TITLE") return $classAttribute;
			}
			
			trigger_error("Class has no descriptor defined and no TITLE attribute: " . $this->getTitle());
		}

		// Has a descriptor, get its title properties
		$descriptor = $this->getDescriptor();
		$titleArray = array();
		while (ereg("<[A-Z]*>", $descriptor, $tempTitleArray) != false)
		{
			array_push($titleArray, $tempTitleArray[0]);
			$descriptor = str_replace($tempTitleArray[0], "", $descriptor);
		}
		
		if ($titleArray == null || count($titleArray) == 0) trigger_error("Class do not has a valid title defined: " . $this->getDescriptor());
		
		$titleClassAttributeArray = array();
		foreach($titleArray as $title)
		{
			$titleNoTags = str_replace(">", "", str_replace("<", "", $title));
			
			$found = false;
			foreach($classAttributeArray as $classAttribute)
			{
				if ($classAttribute->getName() == $titleNoTags)
				{
					array_push($titleClassAttributeArray, $classAttribute);
					$found = true;

					break;
				}
			}
			
			if ($found == false) trigger_error("Could not found ClassAttribute " . $titleNoTags);
		}
				
		return $titleClassAttributeArray;
	}
	
	/**
	 * Gets the objects associated to this class. Be careful with this method, you
	 * should only use it if the number of objects is minimmum.
	 * @return Array - instances of Object class
	 */
	function getObjects()
	{
		if (is_null($this->objects))
		{
			$objectMapper = new ObjectMapper();
			$this->objects = $objectMapper->findByClassId($this->getId());
		}
		
		return $this->objects;
	}
}
?>