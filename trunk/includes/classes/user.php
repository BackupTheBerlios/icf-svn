<?php

require_once "mappers/roleMapper.php";

/**
 * Representa logicamente a un usuario del sistema
 *
 * @author despada 2005-03-19 18:33
 */
class User
{
	var $id;
	var $name;
	var $nick;
	var $pwd;
	var $attributesId;
	var $attributes;
		
	var $roles;
	
	function User()
	{
		$this->roles = null;
	}
	
	function& setId($id)
	{
		$this->id = $id;		
	}
	
	function& getId()
	{
		return $this->id;
	}
	
	function& setName($name)
	{
		$this->name = $name;
	}
	
	function& getName()
	{
		return $this->name;
	}
	
	function& setNick($nick)
	{
		$this->nick = $nick;
	}
	
	function& getNick()
	{
		return $this->nick;
	}
	
	function& setPwd($pwd)
	{
		$this->pwd = $pwd;
	}
	
	function& getPwd()
	{
		return $this->pwd;
	}
	
	function& setAttributesId($attributesId)
	{
		$this->attributesId = $attributesId;
	}
	
	function& getAttributesId()
	{
		return $this->attributesId;
	}
	
	function& setAttributes($attributes)
	{
		$this->attributes = $attributes;
	}
	
	function& getAttributes()
	{
		return $this->attributes;
	}
	
	/**
	 * Logs the user into the system
	 *
	 * @param $pwd Password to test
	 * @return true if the password was valid, false if not
	 */
	function& login($pwd)
	{		
		if ($this->pwd == $pwd)
			return true;
		else 
			return false;
	}
	
	/**
	 * Gets the roles of the user
	 */
	function& getRoles()
	{
		if ($this->roles == null)
		{			
			$roleMapper = new RoleMapper();
			$this->roles = $roleMapper->findByUserId($this->getId());
		}
		
		return $this->roles;
	}
}

?>