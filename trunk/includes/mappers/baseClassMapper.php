<?php

require_once 'icfDatabase.php';
require_once "classes/baseClass.php";
require_once "service/query/query.php";

/**
 * Class that implements the mapper design pattern for the BaseClass class
 *
 * @author despada 2005-04-XX
 */
class BaseClassMapper
{
	var $icfDatabase;
	
	/**
	 * Constructs the mapper
	 */
	function& BaseClassMapper()
	{
		$this->icfDatabase = new IcfDatabase();
	}
	
	/**
	 * Gets a BaseClass object
	 *
	 * @param id the BaseClass Id
	 * @return the BaseClass object
	 */
	function& get($id)
	{
		$query = new Query("Class");
		$criteria = new Criteria($query, "ID", $id);
		$query->setCriterion($criteria);
		
		return $this->mapOne($query->execute());
	}

	/**
	 * Gets all classes
	 * @return array - list of BaseClass objects
	 */
	function& getAll()
	{
		$query = new Query("Class");
		$rs = $query->execute();
		return $this->mapAll($rs);
	}
	
	/**
	 * Gets the classes whose objects the user can execute certain action
	 *
	 * @param $action object - The action that the user should be allowed to do
	 * @param $user User - The user that holds de permissions
	 * @return Array - an array of class objects
	 */
	function& findByPermission($action, $user)
	{
		$classQuery = new Query("Class");
		
		// Navigate relationships
		$folderClassQuery =& $classQuery->queryRelationedClass("FolderClass");
		$permissionQuery =& $folderClassQuery->queryRelationedClass("Permission");
		$actionQuery =& $permissionQuery->queryRelationedClass("Action", Relationship::ManyToOneType());
		$roleQuery =& $permissionQuery->queryRelationedClass("Role", Relationship::ManyToOneType());
		$roleUserQuery =& $roleQuery->queryRelationedClass("RoleUser");
		$userQuery =& $roleUserQuery->queryRelationedClass("User", Relationship::ManyToOneType());
		
		// Criterias
		$criteriaGroup = new CriteriaGroup();
		
		$actionCriteria = new Criteria($actionQuery, "action", $action->getAction());
		$userCriteria = new Criteria($userQuery, "ID", $user->getId());
		
		$criteriaGroup->addCriterion($actionCriteria);
		$criteriaGroup->addCriterion($userCriteria);
		
		$classQuery->setCriterion($criteriaGroup);
		
		// Execute the query
		$recordset =& $classQuery->execute();
		$array = $this->mapAll($recordset);
		return $array;
	}
	
	/**
	 * Maps all the BaseClass objects contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped BaseClass object
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
	
	/**
	 * Maps a BaseClass object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped BaseClass object
	 */	
	function& mapOne($rs)
	{
		if ($rs == null)
			return null;
		
		$object = new BaseClass();
		$object->setId($rs->fields["ID"]);
		$object->setClassName($rs->fields["className"]);
		$object->setDescriptor($rs->fields["descriptor"]);
		$object->setShortDescription($rs->fields["shortDescription"]);
		$object->setTitle($rs->fields["title"]);
		$object->setLongDescription($rs->fields["longDescription"]);
		
		return $object;
	}
	
}

?>