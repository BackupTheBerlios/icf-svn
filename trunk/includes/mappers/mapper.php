<?php

require_once "icfDatabase.php";
require_once "service/query/query.php";
require_once "service/persistence/persistence.php";

/**
 * Base class for mapper objects
 */
class Mapper
{
	var $icfDatabase;
	var $conn;
	var $className;
	
	/**
	 * Base construct for the mapper
	 * @param $className class being mapped
	 */
	function& Mapper($className)
	{
		$this->icfDatabase = new IcfDatabase();
		$this->conn = null;
		$this->className = $className;
	}
	
	/**
	 * Injects a connection object to the mapper, making it part of a transaction coordination effort
	 * @param $conn connection object
	 */
	function setConnection(&$conn)
	{
		$this->conn =& $conn;
	}
	
	/**
	 * Constructs a persitence object for the appropiate class
	 * @return Persistence - persistence object
	 */
	function& newPersistenceObject()
	{
		$persistence = new Persistence($this->className);
		$persistence->setConnection($this->conn);
		return $persistence;
	}
	
	/**
	 * Constructs a query object for the present class.
	 * @return Query - query object
	 */
	function& newQueryObject()
	{
		$query = new Query($this->className);
		$query->setConnection($this->conn);
		return $query;
	}
	
	/**
	 * Gets an instance of the appropiate class
	 *
	 * @param id the Object Id
	 * @return Object - the Object object
	 */
	function& get($id)
	{		
		$persistence =& $this->newPersistenceObject();
		$persistence->setProperty("ID", $id);
		$rs = $persistence->get($id);
				
		return $this->mapOne($rs);
	}
	
	/**
	 * Gets all the entities. Use with care, entity quantity shouldn't be too large.
	 *
	 * @return Array - Array of entities
	 */
	function& getAll()
	{		
		$query = $this->newQueryObject();
		/* @var $query Query */
				
		return $this->mapAll($query->execute());
	}
	
	/**
	 * Makes the object persistent
	 * @param $object object to be made persistent
	 * @return object - the same object with its new id
	 * @abstract
	 */
	function& save($object)
	{
		trigger_error("Not implemented");
	}

	/**
	 * Synchronizes an object with the database
	 * @param $object object to be synchronized. It should already be persistent (had its id assigned)
	 * @abstract
	 */
	function update($object)	
	{
		trigger_error("Not implemented");
	}
	
	/**
	 * Makes an object transient again
	 * @param $id int - object id
	 */
	function delete($id)
	{
		$persistence =& $this->newPersistenceObject();		
		$persistence->setProperty("ID", $id);
		$persistence->delete();
	}
	
	/**
	 * Maps all the Objects objects contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Object object
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
	 * Maps an object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset - structure filled with the object data
	 * @return Object - Mapped Object
	 * @abstract
	 */	
	function& mapOne($rs)
	{
		trigger_error("Not implemented");
	}
}

?>