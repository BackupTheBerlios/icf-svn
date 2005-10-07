<?php

/**
 * Represents an attribute of a ICF object.
 *
 * @author despada 2005-04-09
 */
class ObjectAttribute
{
	var $id;
	var $classAttributeID;
	var $objectID;
	var $languageID;
	var $value;
	
	var $classAttribute;
	var $object;
	var $language;
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function setClassAttributeID($classAttributeID)
	{
		$this->classAttributeID = $classAttributeID;
	}
	
	function getClassAttributeID()
	{
		return $this->classAttributeID;
	}

	function setObjectID($objectID)
	{
		$this->objectID = $objectID;
	}
	
	function getObjectID()
	{
		return $this->objectID;
	}

	function setLanguageID($languageID)
	{
		$this->languageID = $languageID;
	}
	
	function getLanguageID()
	{
		return $this->languageID;
	}

	function setValue($value)
	{
		$this->value = $value;
	}
	
	function getValue()
	{
		return $this->value;
	}
	
	/**
	 * Gets the datatype of the attribute
	 * @return Datatype - Datatype object
	 */
	function getDatatype()
	{
		$classAttribute = $this->getClassAttribute();
		return $classAttribute->getDatatype();
	}
	
	/**
	 * Gets the language object associated with this object
	 * @return Language - a language object
	 */
	function getLanguage()
	{
		if ($this->language == null)
		{
			$languageMapper = new LanguageMapper();
			$this->language = $languageMapper->get($this->getLanguageID());
		}
		
		return $this->language;
	}
	
	/**
	 * Gets the ClassAttribute object associated with this object
	 * @return ClassAttribute - a ClassAttribute object
	 */
	function getClassAttribute()
	{
		if ($this->classAttribute == null)
		{
			$classAttributeMapper = new ClassAttributeMapper();
			$this->classAttribute = $classAttributeMapper->get($this->getClassAttributeID());
		}
		
		return $this->classAttribute;
	}
	
	/**
	 * Gets the Object instance associated with this attribute
	 * @return Object - an Object instance
	 */
	function getObject()
	{
		if ($this->object == null)
		{
			$objectMapper = new ObjectMapper();
			$this->object = $objectMapper->get($this->getObjectID());
		}
		
		return $this->object;
	}
}

?>
