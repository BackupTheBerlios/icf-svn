<?php

/**
 * Represents a datatype of an attribute
 *
 * @author despada 2005-04-09
 */
class Datatype
{
	var $id;
	var $datatype;
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function setDatatype($datatype)
	{
		$this->datatype = $datatype;
	}
	
	function getDatatype()
	{
		return $this->datatype;
	}
	
}

?>