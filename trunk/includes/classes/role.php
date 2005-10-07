<?php

require_once "mappers/permissionMapper.php";
require_once "mappers/roleUserMapper.php";

/**
 * Represents a role in the ICF. A role is composed of permissions, and can be assigned
 * to users so they can do certain actions.
 *
 * @author despada 2005-04-09
 */
class Role
{
	var $id;
	var $role;
	var $isDefault;
	
	var $permissions;
	var $roleUsers;
	
	/**
	 * Constructs a role object
	 */
	function Role()
	{
		$this->permissions = null;
		$this->roleUsers = null;
	}
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function setRole($role)
	{
		$this->role = $role;
	}
	
	function getRole()
	{
		return $this->role;
	}

	function setIsDefault($isDefault)
	{
		$this->isDefault = $isDefault;
	}
	
	function getIsDefault()
	{
		return $this->isDefault;
	}
	
	/**
	 * Gets the array of RoleUser objects
	 * @return Array - array of RoleUser objects
	 */
	function getRoleUsers()
	{
		if ($this->roleUsers == null)
		{
			$roleUserMapper = new RoleUserMapper();
			$this->roleUsers = $roleUserMapper->findByRoleId($this->getId());
		}
		
		return $this->roleUsers;
	}
	
	/**
	 * Obtains the permissions list
	 *
	 * @return Array of Permission objects
	 */
	function getPermissions()
	{
		if ($this->permissions == null)
		{
			$permissionMapper = new PermissionMapper();
			$this->permissions = $permissionMapper->findByRoleId($this->getId());
		}
		
		return $this->permissions;
	}
}

?>
