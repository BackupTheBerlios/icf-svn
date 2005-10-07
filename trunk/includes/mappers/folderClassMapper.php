<?php

require_once 'icfDatabase.php';
require_once "classes/folderClass.php";

/**
 * Class that implements the mapper design pattern for the FolderClass class
 *
 * @author despada 2005-04-XX
 */
class FolderClassMapper extends Mapper
{
	var $icfDatabase;
	
	/**
	 * Constructs the mapper
	 */
	function& FolderClassMapper()
	{
		$this->Mapper("FolderClass");
	}
	
	/**
	 * Saves a folderClass object
	 * @param $folderClass FolderClass - folderclass object
	 * @return FolderClass - object with new id
	 */
	function& save($folderClass)
	{
		/* @var $persistence Persistence */
		$persistence = $this->newPersistenceObject();
		$persistence->setProperty("classID", $folderClass->getClassID());
		$persistence->setProperty("folderID", $folderClass->getFolderID());
		$persistence->setProperty("isDefault", $folderClass->getIsDefault());
		
		$id = $persistence->save();
		$folderClass->setId($id);		
		return $folderClass;
	}

	/**
	 * Deletes all folderClass objects that belong to a folder
	 * @param $folderId int - Folder
	 */
	function deleteByFolderId($folderId)
	{
		$folderClassArray = $this->findByFolderId($folderId);
		
		foreach($folderClassArray as $folderClass)
			$this->delete($folderClass->getId());
	}
	
	/**
	 * Gets the folderClass objects asociated with a folder
	 * @param $folderId int - id of the folder
	 * @return Array - Folder class objects
	 */
	function findByFolderId($folderId)
	{
		$query = $this->newQueryObject();
		$criteria = new Criteria($query, "folderID", $folderId);
		$query->setCriterion($criteria);
		
		return $this->mapAll($query->execute());
	}

	/**
	 * Gets the folderClass objects asociated with a class
	 * @param $classId int - id of the baseClass
	 * @return Array - BaseClass objects
	 */
	function findByClassId($classId)
	{
		$query = $this->newQueryObject();
		$criteria = new Criteria($query, "classID", $classId);
		$query->setCriterion($criteria);
		
		return $this->mapAll($query->execute());
	}
	
	/**
	 * Maps a FolderClass object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped FolderClass object
	 */	
	function& mapOne($rs)
	{
		if ($rs == null)
			return null;
		
		$object = new FolderClass();
		
		$object->setId($rs->fields["ID"]);
		$object->setClassID($rs->fields["classID"]);
		$object->setFolderID($rs->fields["folderID"]);
		$object->setIsDefault($rs->fields["isDefault"]);
		$object->setPosition($rs->fields["position"]);
		
		return $object;
	}	
}

?>