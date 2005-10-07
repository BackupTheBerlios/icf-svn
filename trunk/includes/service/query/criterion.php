<?php

/**
 * An interface that must implement every class that is hosted
 * inside a Criteria group.
 */
class Criterion
{
	var $query;
	
	/**
	 * Returns the query prompted by this criterion.
	 * @return Query - the one that owns this criterion	 
	 */
	function &getQuery()
	{
		return $this->query;
	}
	
	/** 
	 * Gets the criterion as a string, prepared to append to the real query
	 */
	function toString()
	{
		return "Criterion: should be overloaded !!";
	}
}

?>