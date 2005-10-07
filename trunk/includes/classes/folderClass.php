<?php

require_once "mappers/baseClassMapper.php";
require_once "mappers/folderMapper.php";

/**
 * Representa el enlace entre una carpeta y una clase
 *
 * @author despada 2005-04-09
 */
class FolderClass
{
	var $id;
	var $classID;
	var $folderID;
	var $position;
	var $isDefault;
	
	var $class;
	var $folder;
  var $permissions;
  
	/**
	 * Constructs a FolderClass object
	 */
	function FolderClass($classID = null, $folderID = null)
	{
		$this->class = null;
		$this->folder = null;
		$this->permissions = null;
		
		$this->classID = $classID;
		$this->folderID = $folderID;
	}
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function setClassID($classID)
	{
		$this->classID = $classID;
	}
	
	function getClassID()
	{
		return $this->classID;
	}
	
	function setFolderID($folderID)
	{
		$this->folderID = $folderID;
	}
	
	function getFolderID()
	{
		return $this->folderID;
	}

	function setPosition($position)
	{
		$this->position = $position;
	}
	
	function getPosition()
	{
		return $this->position;
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
	 * Gets the class object
	 */
	function getClass()
	{
		if ($this->class == null)
		{
			$baseClassMapper = new BaseClassMapper();
			$this->class = $baseClassMapper->get($this->classID);			
		}
		
		return $this->class;
	}
	
	/**
	 * Gets the assigned folder object
	 * @return Folder - a folder object
	 */
	function getFolder()
	{
		if ($this->folder == null)
		{
			$folderMapper = new FolderMapper();
			$this->folder = $folderMapper->get($this->folderID);
		}
		
		return $this->folder;
	}
	
	/**
	 * Checks if a certain user can do certain action
	 * @param $user User - user that intends to do action, if null, session user is employed
	 * @param $action Action - action to be done
	 * @return boolean - true if the user should be allowed, false if not
	 */
	function canDoAction($user = null, $action)
	{
		if ($user == null)
		{
			$session = new Session();
			$user = $session->getSessionUser();
		}
		
		// Has this folder the permission itself ?
		if ($this->getPermission($user, $action) != null)
			return true;
		
		// It doesn't... has any of his parents an inheritable permission ?
		$folder = $this->getFolder();
		$parent = $folder->getParent();		
		while ($parent != null)
		{
			$folderClass = $parent->getFolderClass($this->getClass());
			
			// If its father folder does not have a relationship to the wanted class, go to the grandfather...
			if ($folderClass == null)
			{
				$parent = $parent->getParent();
				continue;
			}
			
			$permission = $folderClass->getPermission($user, $action);
			if ($permission != null)
			{
				// The permission exists... if it is inheritable, then this object should inherit it
				if ($permission->getIncludeChildren())
					return true;				
			}
			
			// Continue the search in the parent of this parent
			$parent = $parent->getParent();
		}
		
		return false;
	}
	
	/**
	 * Looks for the permission that allows a given user to do certain action. If
	 * the permission does not exist (because it is not assigned or is assigned but the
	 * user does not own it) it returns null.
	 *
	 * @param $user User - user that intends to do action
	 * @param $action Action - action to be done
	 * @return Permission - permission object, or null
	 */
	function getPermission($user, $action)
	{
		$permissions = $this->getPermissions();
		foreach($permissions as $permission)
		{
			$permissionAction = $permission->getAction();
			
			if ($action->getAction() == $permissionAction->getAction())
			{
				// Is the permission looked for... does the user have it ?
				$role = $permission->getRole();				
				$roleUsers = $role->getRoleUsers();

				foreach($roleUsers as $roleUser)
				{
					if ($roleUser->getUserId() == $user->getId())
						return $permission;
				}
				
				// The permission exists, but the user does not have it...
				break;
			}
		}
		
		// Permission does not exists... the folder does not allow to do this action
		return null;

	}
	
	/**
	 * Gets the permissions for this folder
	 * @return Array - Permission objects array
	 */
	function getPermissions()
	{
		if ($this->permissions == null)
		{
			$permissionMapper = new PermissionMapper();
			$this->permissions = $permissionMapper->findByFolderClassId($this->getId());
		}
		
		return $this->permissions;
	}
}

?>
