<?php

require_once 'icfDatabase.php';
require_once "classes/datatype.php";

/**
 * Class that implements the mapper design pattern for the Datatype class
 *
 * @author despada 2005-04-XX
 */
class DatatypeMapper
{
	var $icfDatabase;
	
	/**
	 * Constructs the mapper
	 */
	function& DatatypeMapper()
	{
		$this->icfDatabase = new IcfDatabase();
	}
	
	/**
	 * Gets a Datatype object
	 *
	 * @param id the Datatype Id
	 * @return the Datatype object
	 */
	function& get($id)
	{
		$query = new Query("Datatype");
		$criteria = new Criteria($query, "id", $id);
		$query->setCriterion($criteria);
		
		return $this->mapOne($query->execute());
	}

	/**
	 * Maps all the Datatypes objects contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Datatype object
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
	 * Maps a Datatype object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Datatype object
	 */	
	function& mapOne($rs)
	{
		if ($rs == null)
			return null;
			
		$object = new Datatype();
		
		$object->setId($rs->fields["ID"]);
		$object->setDatatype($rs->fields["datatype"]);
		
		return $object;
	}
}

?>
