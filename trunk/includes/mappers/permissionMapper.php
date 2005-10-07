<?php

require_once "classes/permission.php";
require_once "service/query/query.php";

/**
 * Class that implements the mapper design pattern for the Permission class
 *
 * @author despada DATE
 */
class PermissionMapper
{
	var $icfDatabase;
	
	/**
	 * Constructs the mapper
	 */
	function PermissionMapper()
	{
		$this->icfDatabase = new IcfDatabase();
	}
	
	/**
	 * Gets a Permission object
	 *
	 * @param id the Permission Id
	 * @return the Permission object
	 */
	function get($id)
	{
		$rs = $this->icfDatabase->dbQuery("SELECT * FROM ##Permission WHERE ID = " . $id);
		
		return $this->mapOne($rs);
	}

	/**
	 * Obtians a list of permissions using their role
	 *
	 * @param roleID The role whose permissions are being searched
	 * @return array of permission objects
	 */
	function findByRoleId($roleID)
	{
		$rs = $this->icfDatabase->dbQuery("SELECT * FROM ##Permission WHERE roleID = " . $roleID);
		
		return $this->mapAll($rs);
	}
	
	/**
	 * Gets a list with permissions for a folderClass object
	 * @param $folderClassId FolderClass - the folderClass object
	 * @return Array with permission objects
	 */
	function findByFolderClassId($folderClassId)
	{
		$query = new Query("Permission");
		
		$criteria = new Criteria($query, "folderClassID", $folderClassId);
		$query->setCriterion($criteria);
		
		return $this->mapAll($query->execute());
	}
	
	/**
	 * Maps a Permission object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Permission object
	 */	
	function mapOne($rs)
	{
		if ($rs == null)
			return null;
			
		$object = new Permission();
		
		$object->setId($rs->fields["ID"]);
		$object->setActionID($rs->fields["actionID"]);
		$object->setFolderClassID($rs->fields["folderClassID"]);
		$object->setIncludeChildren($rs->fields["includeChildren"]);
		$object->setRoleID($rs->fields["roleID"]);
		
		return $object;
	}

	/**
	 * Maps all the Permission objects contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Permission object
	 */	
	function mapAll($rs)
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
}

?>
