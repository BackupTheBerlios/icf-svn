<?php

require_once "mappers/baseClassMapper.php";
require_once "mappers/datatypeMapper.php";

/**
 * Represents an attribute of a given class.
 *
 * @author despada 2005-04-06 07:18 AM
 */
class ClassAttribute
{
	var $id;
	
	var $classID;
	var $datatypeID;
	
	var $name;
	var $title;
	var $helpText;
	var $len;
	var $defaultValue;
	var $isRequired;
	var $isSearchable;
	var $isTranslatable;
	var $position;

	var $baseClass;
	var $datatype;
	
	/**
	 * Constructs an attribute for a class
	 */
	function ClassAttribute()
	{
		$this->baseClass = null;
		$this->datatype = null;
	}
	
	// attributes
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function setClassId($classId)
	{
		$this->classId = $classId;
	}
	
	function getClassId()
	{
		return $this->classId;
	}	

	function setDatatypeId($datatypeId)
	{
		$this->datatypeId = $datatypeId;
	}
	
	function getDatatypeId()
	{
		return $this->datatypeId;
	}
	
	function setName($name)
	{
		$this->name = $name;
	}
	
	function getName()
	{
		return $this->name;
	}
	
	function setTitle($title)
	{
		$this->title = $title;
	}
	
	function getTitle()
	{
		return $this->title;
	}

	function setHelpText($helpText)
	{
		$this->helpText = $helpText;
	}
	
	function getHelpText()
	{
		return $this->helpText;
	}

	function setLen($len)
	{
		$this->len = $len;
	}
	
	function getLen()
	{
		return $this->len;
	}

	function setDefaultValue($defaultValue)
	{
		$this->defaultValue = $defaultValue;
	}
	
	function getDefaultValue()
	{
		return $this->defaultValue;
	}

	function setIsRequired($isRequired)
	{
		$this->isRequired = $isRequired;
	}
	
	function getIsRequired()
	{
		return $this->isRequired;
	}

	function setIsSearchable($isSearchable)
	{
		$this->isSearchable = $isSearchable;
	}
	
	function getIsSearchable()
	{
		return $this->isSearchable;
	}

	function setIsTranslatable($isTranslatable)
	{
		$this->isTranslatable = $isTranslatable;
	}
	
	function getIsTranslatable()
	{
		return $this->isTranslatable;
	}

	function setPosition($position)
	{
		$this->position = $position;
	}
	
	function getPosition()
	{
		return $this->position;
	}
		
	// many-to-one	
	
	/**
	 * Gets the class that owns this attribute
	 */
	function getBaseClass()
	{		
		if ($this->baseClass == null)
		{			
			$baseClassMapper = new BaseClassMapper();		
			$this->baseClass = $baseClassMapper-get($this->getClassId());
		}
		
		return $this->baseClass;
	}
	
	/**
	 * Gets the datatype of this attribute
	 */
	function getDatatype()
	{
		if ($this->datatype == null)
		{
			$datatypeMapper = new DataTypeMapper();
			$this->datatype = $datatypeMapper->get($this->getDatatypeId());
		}
		
		return $this->datatype;
	}
}

?>