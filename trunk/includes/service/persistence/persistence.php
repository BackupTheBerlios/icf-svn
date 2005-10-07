<?php

require_once "icfDatabase.php";

/**
 * Proportionates a basic relational DBMS persistence service to the business logic programmer,
 * built on PHP ADODB capabilities.
 * 
 * The persistence object gives you three basic capabilities:
 * 
 * 1) Save a new instance of an object -> make a transient object persistent.
 * 2) Update an existing instance -> synchronize a persisting object with the DBMS
 * 3) Physically delete an existing instance -> make a persisting object transient
 *
 * The general rules are as following:
 *
 * a) Create a new instance of the persistence object, passing as a constructor parameter the class
 * 		to be managed:
 *
 * $persistence = new Persistence("objectClass");
 *
 * b) Fill the needed properties of the object (NOTE: some operations need the special "id" property,
 * 		and will fail if you do not specify it):
 *
 * $persistence->setProperty("ID", $id);
 *
 * c) Invoke the needed operation:
 *
 * $persistence->save();
 * 
 */
class Persistence
{
	var $className;
	var $conn;
	var $properties;
	var $icfDatabase;
	
	/**
	 * Constructs a Persistence object
	 *
	 * @param $className string - name of the class to be persisted
	 * @param $conn	 conn - Optional, 
	 */
	function Persistence($className)
	{
		// Class name must start with upper case
		$className = strtoupper(substr($className, 0, 1)) . substr($className, 1);
		
		$this->className = $className;
		$this->properties = array();
		$this->icfDatabase = new IcfDatabase();
	}
	
	/**
	 * Sets an ADO connection, only use it if this persistence object should execute its operations in a transaction context.
	 * @param $conn ADO connection
	 */
	function setConnection(&$conn)
	{
		$this->conn =& $conn;
	}
	
	/**
	 * Sets the value of a property to be persisted
	 *
	 * @param $name string - name of the property
	 * @param $value string - value to be given to the property
	 */
	function setProperty($name, $value)
	{
		$this->properties[$name] = $value;
	}
	
	/**
	 * Gets the value given to a property
	 * @return string - given value
	 */
	function getProperty($name)
	{
		return $this->properties[$name];
	}	
	
	/**
	 * Executes a "save", making a transient object persistent
	 * @return int - the new id of the object
	 */
	function save()
	{	
		$saveString =& $this->getSaveString();
		
		$id = "";
		if ($this->conn == null)
			$id = $this->icfDatabase->dbExecute($saveString);
		else
			$id = $this->icfDatabase->dbExecuteInTx($saveString, $this->conn);
		
		if ($id == "") trigger_error("Could not save object");
		
		return $id;
	}
	
	/**
	 * Executes an "update", synchronizing the state of the object with the database
	 */
	function update()
	{
		$updateString =& $this->getUpdateString();
		
		if ($this->conn == null)
			$this->icfDatabase->dbExecute($updateString);
		else
			$this->icfDatabase->dbExecuteInTx($updateString, $this->conn);
	}
	
	/**
	 * Deletes the object, making it transient again
	 */
	function delete()
	{
		$deleteString =& $this->getDeleteString();
		
		if ($this->conn == null)
			$this->icfDatabase->dbExecute($deleteString);
		else
			$this->icfDatabase->dbExecuteInTx($deleteString, $this->conn);
	}
	
	/**
	 * Gets a given object in a recordset
	 * @return recordset - Recordset ojbect
	 */
	function get()
	{
		$getString =& $this->getSelectString();
		if ($this->conn == null)
			return $this->icfDatabase->dbQuery($getString);
		else
			return $this->icfDatabase->dbQueryInTx($getString, $this->conn);
	}
	
	/**
	 * Constructs the "save string" to be executed in the DBMS
	 * @access private
	 * @return string - the save string
	 */
	function& getSaveString()
	{
		// Extract field names
		$keys = array_keys($this->properties);
		if (count($keys) <= 0) trigger_error("Cannot invoke save without any properites to be persisted");
		$fields = "(";
		foreach ($keys as $key)
		{
			if ($fields != "(") $fields = $fields . ", ";
			$fields = $fields . $key;
		}
		$fields = $fields . ")";
		
		// Extract values
		$values = "(";
		foreach($this->properties as $value)
		{	
			// Sourround by '	
			$value = "'" . $value . "'";			
			
			// Separate parameters by commas
			if ($values != "(") $values = $values . ", ";	
			
			// If the parameter is empty, put NULL
			if ($value == "''")			
				$values = $values . "NULL";
			else
				$values = $values . $value;
		}
		$values = $values . ")";
		
		$string = "INSERT INTO ##" . $this->className . $fields . " VALUES "	. $values;
		
		// echo "getSaveString: " . $string;
		
		return $string;
	}
	
	/**
	 * Constructs the "update string" to be executed in the DBMS
	 * @access private
	 * @return string - the update string
	 */
	function& getUpdateString()
	{
		// Extract field names
		$keys = array_keys($this->properties);
		if (count($keys) <= 0) trigger_error("Cannot invoke update without any properites to be synchronized");
		$id = $this->properties["ID"];
		if ($id == null) trigger_error("Cannot invoke update without the ID property initialized");
		$fields = "";
		foreach ($keys as $key)
		{
			// Id does not get updated
			if ($key != "ID")
			{
				$value = "'" . $this->properties[$key] . "'";
				if ($value == "''")
					$value = "NULL";
				
				if ($fields != "") $fields = $fields . ", ";
				$fields = $fields . $key . " = " . $value;
			}
		}
				
		$string = "UPDATE ##" . $this->className . " SET "	. $fields . " WHERE ID = " . $id;
		return $string;
	}
	
	/**
	 * Constructs the "delete string" to be executed in the DBMS
	 * @access private
	 * @return string - the delete string
	 */
	function& getDeleteString()
	{
		$id = $this->properties["ID"];
		if ($id == null) trigger_error("Cannot invoke update without the ID property initialized");
		
		$string = "DELETE FROM ##" . $this->className . " WHERE ID = " . $id;
		return $string;
	}
	
	/**
	 * Constructs the "select string" to be executed in the DBMS
	 * @access private
	 * @return string - the select string
	 */
	function& getSelectString()
	{
		$id = $this->properties["ID"];
		if ($id == null) trigger_error("Cannot invoke select without the ID property initialized");
		
		$string = "SELECT * FROM ##" . $this->className . " WHERE ID = " . $id;
		return $string;
	}
	
}

?>