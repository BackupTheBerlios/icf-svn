<?php

/**
 * Represents the link between a user and a role.
 *
 * @author despada 2005-03-09
 */
class RoleUser
{
	var $id;
	var $roleID;
	var $userID;
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}

	function setRoleId($roleID)
	{
		$this->roleID = $roleID;
	}
	
	function getRoleId()
	{
		return $this->roleID;
	}

	function setUserId($userID)
	{
		$this->userID = $userID;
	}
	
	function getUserId()
	{
		return $this->userID;
	}
	
}

?>
