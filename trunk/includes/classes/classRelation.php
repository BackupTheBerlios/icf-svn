<?php

/**
 * Represents a relation between classes
 *
 * @author despada 2005-04-06 07:38
 */
class ClassRelation
{
	var $id;
	var $parentID;
	var $childID;
	var $position;
	var $cardinality;
	var $title;
	var $helpText;
	var $isRequired;
	
	var $child;
	
	/**
	 * Creates a new ClassRelation object
	 */
	function ClassRelation()
	{
		$this->child = null;
	}
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function setParentID($parentID)
	{
		$this->parentID = $parentID;
	}
	
	function getParentID()
	{
		return $this->parentID;
	}

	function setChildID($childID)
	{
		$this->childID = $childID;
	}
	
	function getChildID()
	{
		return $this->childID;
	}

	function setPosition($position)
	{
		$this->position = $position;
	}
	
	function getPosition()
	{
		return $this->position;
	}

	function setCardinality($cardinality)
	{
		$this->cardinality = $cardinality;
	}
	
	function getCardinality()
	{
		return $this->cardinality;
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

	function setIsRequired($isRequired)
	{
		$this->isRequired = $isRequired;
	}
	
	function getIsRequired()
	{
		return $this->isRequired;
	}
	
	/**
	 * Gets the child class associated to this object
	 * @return BaseClass - BaseClass object
	 */
	function getChild()
	{
		if (is_null($this->child))
		{
			$baseClassMapper = new BaseClassMapper();			
			$this->child = $baseClassMapper->get($this->getChildID());
		}
				
		return $this->child;
	}

}

?>