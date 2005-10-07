<?php

/**
 * Represents the relation between two objects
 *
 * @author despada 2005-03-09
 */
class ObjectRelation
{
	var $id;
	var $parentID;
	var $childID;
	var $position;
	
	var $parent;
	var $child;
	
	function ObjectRelation()
	{
		$this->parent = null;
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

	/**
	 * Gets the Object parent
	 * @return Object - object instance
	 */
	function getParent()
	{
		if (is_null($this->parent) == true)
		{
			$objectMapper = new ObjectMapper();
			$this->parent = $objectMapper->get($this->getParentID());
		}
				
		return $this->parent;
	}
	
	function setChildID($childID)
	{
		$this->childID = $childID;
	}
	
	function getChildID()
	{
		return $this->childID;
	}

	/**
	 * Gets the Object child
	 * @return Object - object instance
	 */
	function getChild()
	{
		if (is_null($this->child) == true)
		{
			$objectMapper = new ObjectMapper();
			$this->child = $objectMapper->get($this->getChildID());
		}
				
		return $this->child;
	}
	
	function setPosition($position)
	{
		$this->position = $position;
	}
	
	function getPosition()
	{
		return $this->position;
	}
	
}

?>
