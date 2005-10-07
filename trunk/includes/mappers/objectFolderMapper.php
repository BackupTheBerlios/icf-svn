<?php

require_once "icfDatabase.php";
require_once "classes/objectFolder.php";
require_once "service/query/query.php";
require_once "service/persistence/persistence.php";
require_once "mappers/mapper.php";

/**
 * Class that implements the mapper design pattern for the ObjectFolder class
 */
class ObjectFolderMapper extends Mapper
{
	/**
	 * Constructs the mapper
	 */
	function ObjectFolderMapper()
	{
		$this->className = "ObjectFolder";
	}
	
	/**
	 * Makes the ObjectFolder object persistent
	 * @param $objectFolder ObjectFolder - Object to be made persistent
	 * @return ObjectFolder - the same object with its new id
	 */
	function& save($objectFolder)
	{
		$persistence =& $this->newPersistenceObject();
		
		$persistence->setProperty("objectID", $objectFolder->getObjectID());
		$persistence->setProperty("folderID", $objectFolder->getFolderID());
		$persistence->setProperty("position", $objectFolder->getPosition());
		
		$id = $persistence->save();		
		$objectFolder->setId($id);
		return $object;
	}

	/**
	 * Synchronizes an object with the database
	 * @param $objectFolder objectFolder to be synchronized. It should already be persistent (had its id assigned)
	 */
	function update($objectFolder)
	{
		$persistence =& $this->newPersistenceObject();
		
		$persistence->setProperty("ID", $objectFolder->getId());
		$persistence->setProperty("objectID", $objectFolder->getObjectID());
		$persistence->setProperty("folderID", $objectFolder->getFolderID());
		$persistence->setProperty("position", $objectFolder->getPosition());
		
		$persistence->update();
	}
	
	/**
	 * Deletes an existing object from the database
	 * @param $id int - id of object to be deleted
	 */
	function delete($id)
	{
		$persistence =& $this->newPersistenceObject();
		
		$persistence->setProperty("ID", $id);
		$persistence->delete();
	}
	
	/**
	 * Deletes every ObjectFolder for an object
	 * @param $objectId Object id
	 */
	function deleteByObjectId($objectId)
	{
		$objectFolderArray = $this->findByObject($objectId);
		
		foreach($objectFolderArray as $objectFolder)
			$this->delete($objectFolder->getId());
	}
	
	/**
	 * Gets the objectFolder objects for a given object
	 * 
	 * @param $objectId id of a certain object
	 * @return List of ObjectFolder class objects
	 */
	function& findByObject($objectId)
	{
		$objectFolderQuery =& $this->newQueryObject();
		
		$criteria =& new Criteria($objectFolderQuery, "objectID", $objectId);
		$objectFolderQuery->setCriterion($criteria);
		
		$order = new Order($objectFolderQuery, "position", Order::OrderTypeAsc());
		$objectFolderQuery->addOrder($order);
		
		return $this->mapAll($objectFolderQuery->execute());
	}

	/**
	 * Gets the objectFolder objects for a given folder
	 * 
	 * @param $folderId id of a certain folder
	 * @return List of ObjectFolder class objects
	 */
	function& findByFolder($folderId)
	{
		$objectFolderQuery =& $this->newQueryObject();
		/* @var $objectFolderQuery Query */
		
		$criteria =& new Criteria($objectFolderQuery, "folderID", $folderId);
		$objectFolderQuery->setCriterion($criteria);
		
		$order = new Order($objectFolderQuery, "position", Order::OrderTypeAsc());
		$objectFolderQuery->addOrder($order);
		
		return $this->mapAll($objectFolderQuery->execute());
	}
	
	/**
	 * Maps a Object object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Object object
	 */	
	function& mapOne($rs)
	{
		if ($rs->EOF)
			return null;
		
		$object = new ObjectFolder();
		
		$object->setId($rs->fields["ID"]);
		$object->setObjectID($rs->fields["objectID"]);
		$object->setFolderID($rs->fields["folderID"]);
		$object->setPosition($rs->fields["position"]);
		
		return $object;
	}
}

?>