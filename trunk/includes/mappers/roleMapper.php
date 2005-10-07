<?php

require_once 'icfDatabase.php';
require_once "classes/role.php";

/**
 * Class that implements the mapper design pattern for the Role class
 *
 * @author despada 2005-04-09
 */
class RoleMapper
{
	var $icfDatabase;
	
	/**
	 * Constructs the mapper
	 */
	function& RoleMapper()
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
		$query = new Query("Role");
		$criteria = new Criteria($query, "ID", $id);
		$query->setCriterion($criteria);
		
		return $this->mapOne($query->execute());
	}

	/**
	 * Gets a role by userID
	 *
	 * @param $userID id of the user
	 * @return array with data
	 */
	function& findByUserId($userID)
	{
		$query = "
			SELECT ##Role.*
			FROM ##Role
			INNER JOIN ##RoleUser ON
			icfroles.ID = ##RoleUser.roleID
			INNER JOIN ##User ON
			##User.ID = ##RoleUser.userID
			WHERE
			##User.ID = " . $userID;
		
		$rs = $this->icfDatabase->dbQuery($query);
		
		return $this->mapAll($rs);
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
		
		$object = new Role();
		$object->setId($rs->fields["ID"]);
		$object->setRole($rs->fields["role"]);
		$object->setIsDefault($rs->fields["isDefault"]);
		
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