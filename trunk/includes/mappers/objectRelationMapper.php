<?php

require_once 'icfDatabase.php';
require_once "classes/objectRelation.php";
require_once "mappers/mapper.php";

/**
 * Class that implements the mapper design pattern for the ObjectRelation class
 *
 * @author despada 2005-04-XX
 */
class ObjectRelationMapper extends Mapper
{	
	/**
	 * Constructs the mapper
	 */
	function& ObjectRelationMapper()
	{
		$this->Mapper("ObjectRelation");
	}
	
	/**
	 * Gets the relations by its parent
	 *
	 * @return an array of ObjectRelation objects
	 */
	function& findByParentId($objectId)
	{
		$query = $this->newQueryObject();
		/* @var $query Query */		
		$criteria = new Criteria($query, "parentID", $objectId);
		$query->setCriterion($criteria);
		
		$order = new Order($query, "position", Order::OrderTypeAsc());		
		$query->addOrder($order);	
		
		return $this->mapAll($query->execute());
	}	

	/**
	 * Saves an ObjectRelation
	 * @param $objectRelation ObjectRelation - an object relation
	 * @return ObjectRelation - same object with the new id loaded in it
	 */
	function& save($objectRelation)
	{
		$persistence = $this->newPersistenceObject();
		/* @var $persistence Persistence */
		$persistence->setProperty("parentID", $objectRelation->getParentID());
		$persistence->setProperty("childID", $objectRelation->getChildID());
		$persistence->setProperty("position", $objectRelation->getPosition());
		
		// Save and retrieve new ID
		$id = $persistence->save();
		$objectRelation->setId($id);
		
		// Return object
		return $objectRelation;
	}
	
	/**
	 * Deletes every ObjectRelation for an object
	 * @param $parentId int - parent object id
	 */
	function deleteByParentId($parentId)
	{
		$objectRelationArray = $this->findByParentId($parentId);
		
		$persistence = $this->newPersistenceObject();
		/* @var $persistence Persistence */
		
		foreach($objectRelationArray as $objectRelation)
		{
			/* @var $objectRelation ObjectRelation */
			$persistence->setProperty("ID", $objectRelation->getId());
			$persistence->delete();
		}
	}
	
	/**
	 * Maps a ObjectRelation object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped ObjectRelation object
	 */	
	function& mapOne($rs)
	{
		if ($rs == null)
			return null;
		
		$object = new ObjectRelation();
		
		$object->setId($rs->fields["ID"]);
		$object->setParentID($rs->fields["parentID"]);
		$object->setChildID($rs->fields["childID"]);
		$object->setPosition($rs->fields["position"]);
		
		return $object;
	}
}

?>
