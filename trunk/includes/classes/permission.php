<?php

require_once "mappers/actionMapper.php";
require_once "mappers/folderClassMapper.php";
require_once "mappers/roleMapper.php";

/**
 * Represents a permission that a user can have. A permission habilitates a user to do certain actions.
 *
 * @author despada 2005-04-09
 */
class Permission
{
	var $id;
	var $actionID;
	var $folderClassID;
	var $roleID;
	var $includeChildren;
	
	var $action;
	var $folderClass;
	var $role;
	
	/**
	 * Constructs a permission object
	 */
	function Permission()
	{
		$this->action = null;
		$this->folderClass = null;
		$this->role = null;
	}
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}

	function setActionID($actionID)
	{
		$this->actionID = $actionID;
	}
	
	function getActionID()
	{
		return $this->actionID;
	}

	function setFolderClassID($folderClassID)
	{
		$this->folderClassID = $folderClassID;
	}
	
	function getFolderClassID()
	{
		return $this->folderClassID;
	}

	function setRoleID($roleID)
	{
		$this->roleID = $roleID;
	}
	
	function getRoleID()
	{
		return $this->roleID;
	}

	function setIncludeChildren($includeChildren)
	{
		$this->includeChildren = $includeChildren;
	}
	
	function getIncludeChildren()
	{
		return $this->includeChildren;
	}
	
	/**
	 * Gets the action assigned to this permission
	 */
	function getAction()
	{
		if ($this->action == null)
		{
			$actionMapper = new ActionMapper();
			$this->action = $actionMapper->get($this->getActionID());
		}
		
		return $this->action;
	}
	
	/**
	 * Obtains a folderClass object
	 */
	function getFolderClass()
	{
		if ($this->folderClass == null)
		{
			$folderClassMapper = new FolderClassMapper();
			$this->folderClass = $folderClassMapper->get($this->getFolderClassID());
		}
		
		return $this->folderClass;
	}
	
	/**
	 * Gets the assigned role object
	 */
	function getRole()
	{
		if ($this->role == null)
		{
			$roleMapper = new RoleMapper();
			$this->role = $roleMapper->get($this->getRoleID());
		}
		
		return $this->role;
	}
}

?>
