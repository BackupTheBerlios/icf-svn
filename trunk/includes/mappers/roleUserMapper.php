<?php

require_once 'icfDatabase.php';
require_once "classes/roleUser.php";

/**
 * Class that implements the mapper design pattern for the RoleUser class
 *
 * @author despada
 */
class RoleUserMapper
{
	var $icfDatabase;
	
	/**
	 * Constructs the mapper
	 */
	function& RoleUserMapper()
	{
		$this->icfDatabase = new IcfDatabase();
	}
	
	/**
	 * Gets a Role object
	 *
	 * @param id the Role Id
	 * @return the Role object
	 */
	function& get($id)
	{
		$query = new Query("RoleUser");
		$criteria = new Criteria($query, "ID", $id);
		$query->setCriterion($criteria);
		
		return $this->mapOne($query->execute());
	}

	/**
	 * Gets RoleUser objects by a roleId
	 *
	 * @param $roleId role id looked up
	 * @return Array - array with RoleUser objects
	 */
	function& findByRoleId($roleId)
	{
		$query = new Query("RoleUser");
		$criteria = new Criteria($query, "roleID", $roleId);
		$query->setCriterion($criteria);
		
		return $this->mapAll($query->execute());
	}
	
	/**
	 * Maps a Role object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Role object
	 */	
	function& mapOne($rs)
	{
		if ($rs == null)
			return null;
		
		$object = new RoleUser();
		$object->setId($rs->fields["ID"]);
		$object->setUserId($rs->fields["userID"]);
		$object->setRoleId($rs->fields["roleID"]);
		
		return $object;
	}

		/**
	 * Maps all the Roles objects contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Role object
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
}

?>
