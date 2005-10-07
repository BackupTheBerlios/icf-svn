<?php

require_once "icfDatabase.php";
require_once "classes/folder.php";
require_once "service/query/query.php";

/**
 * Class that implements the mapper design pattern for the Folder class
 *
 * @author despada 2005-04-XX
 */
class FolderMapper extends Mapper
{
	/**
	 * Constructs the mapper
	 */
	function FolderMapper()
	{
		$this->Mapper("Folder");
	}
	
	/**
	 * Gets a Folder object
	 *
	 * @param id the Folder Id
	 * @return the Folder object
	 */
	function& get($id)
	{
		$query = $this->newQueryObject();
		$criteria = new Criteria($query, "ID", $id);	
		$query->setCriterion($criteria);
		
		return $this->mapOne($query->execute());
	}

	/**
	 * Gets the root folders. If there is more than one, only the first is returned
	 * @return Folder - root Folder object
	 */
	function& getRoot()
	{
		$query = $this->newQueryObject();
		$criteria = new Criteria($query, "parentID", null);	
		$query->setCriterion($criteria);
		
		return $this->mapOne($query->execute());
	}
	
	/**
	 * Gets the folders with a specific parent
	 * 
	 * @param $parentId id of parent folder
	 * @return array of Folder objects
	 */
	function& findByParentId($parentId)
	{
		$query = $this->newQueryObject();
		$criteria = new Criteria($query, "parentID", $parentId);	
		$query->setCriterion($criteria);
		
		$order = new Order($query, "position", Order::OrderTypeAsc());
		$query->addOrder($order);
		
		return $this->mapAll($query->execute());
	}
	
	/**
	 * Gets the folders for a user to do certain action.
	 *
	 * @param $action Action - that the user intends to do
	 * @param $user User - the user that tries to get his folders.
	 * @return Array - vector of folder objects
	 */
	function& findByPermission($action, $user)
	{
		$folderQuery = $this->newQueryObject();
		$folderClassQuery =& $folderQuery->queryRelationedClass("FolderClass");
		$permissionQuery =& $folderClassQuery->queryRelationedClass("Permission");
		$actionQuery =& $permissionQuery->queryRelationedClass("Action", Relationship::ManyToOneType());
		$roleQuery =& $permissionQuery->queryRelationedClass("Role", Relationship::ManyToOneType());
		$roleUserQuery =& $roleQuery->queryRelationedClass("RoleUser");

		$criteriaGroup = new CriteriaGroup();
		
		$actionCriteria = new Criteria($actionQuery, "action", $action->getAction());
		$criteriaGroup->addCriterion($actionCriteria);
		
		$userCriteria = new Criteria($roleUserQuery, "userID", $user->getId());		
		$criteriaGroup->addCriterion($userCriteria);
		
		// Set the criterion
		$folderQuery->setCriterion($criteriaGroup);
		
		// Execute
		return $this->mapAll($folderQuery->execute());
	}
	
	/**
	 * Persists a folder object in the DBMS
	 * @param $folder Folder - Folder object
	 * @return Folder - same folder object with its id set
	 */
	function& save($folder)
	{
		$persistence =& $this->newPersistenceObject();
		$persistence->setProperty("title", $folder->getTitle());
		$persistence->setProperty("longDescription", $folder->getLongDescription());
		$persistence->setProperty("shortDescription", $folder->getShortDescription());
		$persistence->setProperty("parentId", $folder->getParentId());
		$persistence->setProperty("position", $folder->getPosition());
		
		$id = $persistence->save();
		$folder->setId($id);
		
		return $folder;
	}	

	/**
	 * Synchronizes a folder object in the DBMS
	 * @param $folder Folder - Folder object
	 */
	function update($folder)
	{
		$persistence =& $this->newPersistenceObject();
		
		$persistence->setProperty("ID", $folder->getId());
		$persistence->setProperty("title", $folder->getTitle());
		$persistence->setProperty("longDescription", $folder->getLongDescription());
		$persistence->setProperty("shortDescription", $folder->getShortDescription());
		$persistence->setProperty("parentID", $folder->getParentId());
		$persistence->setProperty("position", $folder->getPosition());
		
		$persistence->update();
	}
	
	/**
	 * Maps a Folder object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Folder object
	 */	
	function& mapOne($rs)
	{
		if ($rs == null)
			return null;
		
		$object = new Folder();
		
		$object->setId($rs->fields["ID"]);
		$object->setLongDescription($rs->fields["longDescription"]);
		$object->setParentId($rs->fields["parentID"]);
		$object->setPosition($rs->fields["position"]);
		$object->setShortDescription($rs->fields["shortDescription"]);		
		$object->setTitle($rs->fields["title"]);
		
		return $object;
	}
}

?>