<?php

require_once 'icfDatabase.php';
require_once "classes/classAttribute.php";

/**
 * Class that implements the mapper design pattern for the ClassAttribute class
 *
 * @author despada 2005-04-XX
 */
class ClassAttributeMapper
{
	var $icfDatabase;
	
	/**
	 * Constructs the mapper
	 */
	function& ClassAttributeMapper()
	{
		$this->icfDatabase = new IcfDatabase();
	}
	
	/**
	 * Gets a ClassAttribute object
	 *
	 * @param id the ClassAttribute Id
	 * @return the ClassAttribute object
	 */
	function& get($id)
	{
		$query = new Query("ClassAttribute");
		$criteria = new Criteria($query, "ID", $id);
		$query->setCriterion($criteria);
		
		return $this->mapOne($query->execute());
	}

	/**
	 * Gets a class' attributes
	 *
	 * @param classId id of a class
	 * @return array of class objects
	 */
	function findByClassId($classId)
	{		
		$query = new Query("ClassAttribute");
		$criteria = new Criteria($query, "classID", $classId);
		$query->setCriterion($criteria);
		$order = new Order($query, "position", Order::OrderTypeAsc());
		$query->addOrder($order);
					
		return $this->mapAll($query->execute());
	}
	
	/**
	 * Maps all the ClassAttributes objects contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped ClassAttribute object
	 */	
	function& mapAll($rs)
	{
		if ($rs == null)
			return array();
			
		$array = array();		
		$rs->moveFirst();
		while ($rs->EOF == false)
		{
			$object = $this->mapOne($rs);
			array_push($array, $object);
			
			$rs->moveNext();
		}
		
		return $array;		
	}

	/**
	 * Maps a ClassAttribute object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped ClassAttribute object
	 */	
	function& mapOne($rs)
	{
		if ($rs == null)
			return null;
		
		$object = new ClassAttribute();
		
		$object->setId($rs->fields["ID"]);
		$object->setClassId($rs->fields["classID"]);
		$object->setDatatypeId($rs->fields["datatypeID"]);
		$object->setDefaultValue($rs->fields["defaultValue"]);
		$object->setHelpText($rs->fields["helpText"]);
		$object->setIsRequired($rs->fields["isRequired"]);
		$object->setIsSearchable($rs->fields["isSearchable"]);
		$object->setIsTranslatable($rs->fields["isTranslatable"]);
		$object->setLen($rs->fields["len"]);
		$object->setName($rs->fields["name"]);
		$object->setPosition($rs->fields["position"]);
		$object->setTitle($rs->fields["title"]);
		
		return $object;
	}
}

?>
