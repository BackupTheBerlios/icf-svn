<?php

/**
 * Class that implements the mapper design pattern for the Action class
 *
 * @author despada DATE
 */
class ActionMapper
{
	var $icfDatabase;
	
	/**
	 * Constructs the mapper
	 */
	function ActionMapper()
	{
		$this->icfDatabase = new IcfDatabase();
	}
	
	/**
	 * Gets a Action object
	 *
	 * @param id the Action Id
	 * @return the Action object
	 */
	function get($id)
	{
		$query = new Query("Action");
		$criteria = new Criteria($query, "ID", $id);		
		$query->setCriterion($criteria);		
		
		return $this->mapOne($query->execute());
	}

	/**
	 * Maps an Action object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Action object
	 */	
	function mapOne($rs)
	{
		if ($rs == null)
			return null;
		
		$object = new Action();
		$object->setId($rs->fields["ID"]);
		$object->setAction($rs->fields["action"]);

		return $object;
	}
}

?>
