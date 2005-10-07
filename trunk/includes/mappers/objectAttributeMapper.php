<?php

require_once 'icfDatabase.php';
require_once "mappers/mapper.php";
require_once "classes/objectAttribute.php";

/**
 * Class that implements the mapper design pattern for the ObjectAttribute class
 *
 * @author despada 2005-04-XX
 */
class ObjectAttributeMapper extends Mapper
{
	/**
	 * Constructs the mapper
	 */
	function& ObjectAttributeMapper()
	{
		$this->className = "ObjectAttribute";
	}
	
	/**
	 * Gets a ObjectAttribute object
	 *
	 * @param id the ObjectAttribute Id
	 * @return the ObjectAttribute object
	 */
	function& get($id)
	{
		$query = $this->newQueryObject();

		$criteria = new Criteria($query, "id", $id);
		$query->setCriterion($criteria);
		
		return $this->mapOne($query->execute());
	}

	/**
	 * Gets the ObjectAttribute objects of a given object
	 *
	 * @param objectId id of an object
	 * @return array of ObjectAttribute objects
	 */
	function findByObjectId($objectId)
	{
		$query = $this->newQueryObject();
		
		$criteria = new Criteria($query, "objectID", $objectId);
		$query->setCriterion($criteria);
		
		return $this->mapAll($query->execute());
	}
	
	/**
	 * Saves an ObjectAttribute
	 * @param $objectAttribute ObjectAttribute - ObjectAttribute object
	 * @return ObjectAttribute - Same object with ID set
	 */
	function save($objectAttribute)
	{
		$persistence = $this->newPersistenceObject();
		
		$persistence->setProperty("classAttributeID", $objectAttribute->getClassAttributeID());
		$persistence->setProperty("objectID", $objectAttribute->getObjectID());
		$persistence->setProperty("languageID", $objectAttribute->getLanguageID());
		$persistence->setProperty("value", $objectAttribute->getValue());
		
		$id = $persistence->save();
		
		$objectAttribute->setId($id);
		return $objectAttribute;
	}

	/**
	 * Updates an ObjectAttribute
	 * @param $objectAttribute ObjectAttribute - ObjectAttribute object with ID set
	 */
	function update($objectAttribute)
	{
		$persistence = $this->newPersistenceObject();
		
		$persistence->setProperty("ID", $objectAttribute->getId());
		$persistence->setProperty("classAttributeID", $objectAttribute->getClassAttributeID());
		$persistence->setProperty("objectID", $objectAttribute->getObjectID());
		$persistence->setProperty("languageID", $objectAttribute->getLanguageID());
		$persistence->setProperty("value", $objectAttribute->getValue());
		
		$persistence->update();
	}
	
	/**
	 * Deletes an ObjectAttribute
	 * @param $objectAttributeId ObjectAttribute id
	 */
	function delete($objectAttributeId)
	{
		$persistence = $this->newPersistenceObject();
		
		$persistence->setProperty("ID", $objectAttributeId);
		$persistence->delete();
	}
	
	/**
	 * Deletes all ObjectAttributes for an object
	 * @param $objectId Object id
	 */
	function deleteByObjectId($objectId)
	{
		$objectAttributeArray = $this->findByObjectId($objectId);
		
		foreach($objectAttributeArray as $objectAttribute)
			$this->delete($objectAttribute->getId());
	}
	
	/**
	 * Maps a ObjectAttribute object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped ObjectAttribute object
	 */	
	function& mapOne($rs)
	{
		if ($rs == null)
			return null;
		
		$object = new ObjectAttribute();
		
		$object->setId($rs->fields["ID"]);
		$object->setClassAttributeID($rs->fields["classAttributeID"]);
		$object->setObjectID($rs->fields["objectID"]);
		$object->setLanguageID($rs->fields["languageID"]);
		$object->setValue($rs->fields["value"]);
		
		return $object;
	}
}

?>
