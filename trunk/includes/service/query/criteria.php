<?php

require_once "criterion.php";

/**
 * A criteria to append to a query
 */
class Criteria extends Criterion 
{
	/** 
	 * Returns an equal criteria type
	 * @return CriteriaType
	 */
	function equalType()
	{
		return "=";
	}
	
	/** 
	 * Returns a like criteria type
	 * @return CriteriaType
	 */
	function likeType()
	{
		return "LIKE";
	}
	
	/** 
	 * Returns a "less than" criteria type
	 * @return CriteriaType
	 */
	function lessThanType()
	{
		return "<";
	}
	
	/**
	 * Returns a "less or equal than" criteria type
	 * @return CriteriaType	 	 
	 */
	function lessEqualThanType()
	{
		return "<=";
	}
	
	/**
	 * Returns a "more than" criteria type
	 * @return CriteriaType	 
	 */
	function moreThanType()
	{
		return ">";		
	}
	
	/**
	 * Returns a "more or equal than" criteria type
	 * @return CriteriaType	 
	 */
	function moreEqualThanType()
	{
		return ">=";
	}
	
	var $type;
	var $field; 
	var $value;
	
	/**
	 * Constructs a criteria object.
	 * @param $query Query - the query object that prompts this criteria
	 * @param $field string -  field to be queried
	 * @param $value string - value of the query
	 * @param $type Object - Optional, one of the types exposed by static members of the criteria class, default value is equal
	 */
	function Criteria($query, $field, $value, $type = "=")
	{
		$this->query = $query;
		$this->type = $type;
		$this->field = $field;
		$this->value = $value;
	}
	
	/**
	 * Gets the type of query
	 */
	function getType()
	{
		return $this->type;
	}
	
	function getField()
	{
		return $this->field;		
	}
	
	function getValue()
	{
		return $this->value;
	}
	
	/**
	 * Gets the string form of this criteria
	 * @return string with the criteria
	 */
	function toString()
	{
		$string = "";
		
		// Append the table name
		$query = $this->getQuery();
		$className = $query->getClassName();
		$string = "##" . $className . ".";
	
		// If the programmer tries "equal null", we must look for NULL in a special way
		if ($this->value == null && $this->getType() == Criteria::equalType())
		{
			$string = $string . $this->field . " IS NULL";
			return $string;
		}

		if ($this->type == "IN")
			$value = $this->value;
		else
			$value = "'" . $this->value . "'";

		$string = $string . $this->field . " " . $this->type . " " .  $value;
		
		return $string;
	}
}

?>