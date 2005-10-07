<?php

require_once 'icfDatabase.php';
require_once "classes/classRelation.php";
require_once "mappers/mapper.php";

/**
 * Class that implements the mapper design pattern for the ClassRelation class
 *
 * @author despada 2005-04-XX
 */
class ClassRelationMapper extends Mapper
{	
	/**
	 * Constructs the mapper
	 */
	function& ClassRelationMapper()
	{
		$this->Mapper("ClassRelation");
	}	

	/**
	 * Gets a list of relations searching them by its father
	 *
	 * @param $classId unique id of a class
	 * @return Array of ClassRelation objects
	 */
	function& findByParentId($classId)
	{
		$query = $this->newQueryObject();
		/* @var $query Query */		
		$criteria = new Criteria($query, "parentID", $classId);
		$query->setCriterion($criteria);
		
		$order = new Order($query, "position", Order::OrderTypeAsc());		
		$query->addOrder($order);	
		
		return $this->mapAll($query->execute());
	}	
	/**
	 * Maps a ClassRelation object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped ClassRelation object
	 */	
	function& mapOne($rs)
	{
		if ($rs == null)
			return null;
		
		$object = new ClassRelation();
		$object->setId($rs->fields["ID"]);
		$object->setCardinality($rs->fields["cardinality"]);
		$object->setChildID($rs->fields["childID"]);
		$object->setHelpText($rs->fields["helpText"]);
		$object->setIsRequired($rs->fields{"isRequired"});
		$object->setParentID($rs->fields["parentID"]);
		$object->setPosition($rs->fields["position"]);
		$object->setTitle($rs->fields["title"]);
		
		return $object;
	}
}

?>
