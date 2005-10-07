<?php

require_once 'icfDatabase.php';
require_once "classes/user.php";

/**
 * Implements the data mapper pattern for the User class
 *
 * @author despada 2005-03-19 18:34
 */
class UserMapper extends Mapper
{
	/**
	 * Constructs a mapper
	 */
	function UserMapper()
	{
		$this->Mapper("User");
	}
	
	/**
	 * Obtiene un usuario por su nombre
	 * @param name Nombre de usuario
	 * @return Usuario cuyo nombre es el enviado, o null, si no existe
	 */
	function findByName($name)
	{
		$query = $this->newQueryObject();
		/* @var $query Query */
		
		$criteria = new Criteria($query, "name", $name);
		$query->setCriterion($criteria);
			
		return $this->mapOne($query->execute());
	}
	
	/**
	 * Mapea un usuario de un recordset al objeto
	 *
	 * @param $rs Recordset con datos del usuario
	 * @param $mapCollections boolean, si true, las colecciones se mapean en el objeto empleado N selects
	 * @return objeto User mapeado
	 */
	function mapOne($rs)
	{
		if ($rs == null)
			return array();
			
		$user = new User();
		$user->setId($rs->fields["ID"]);
		$user->setName($rs->fields["name"]);
		$user->setNick($rs->fields["nick"]);
		$user->setPwd($rs->fields["pwd"]);
		$user->setAttributesId($rs->fields["attributesID"]);
		
		return $user;
	}
}

?>