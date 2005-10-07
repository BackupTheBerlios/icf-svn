<?php

require_once "criterion.php";

/**
 * A criteria grouper that can host many criterias inside
 */
class CriteriaGroup extends Criterion
{
	/**
	 * States that every criteria in a group has to be separated with an AND
	 * @static
	 * @return Object - the AND type of separator.
	 */
	function getAndSeparator()
	{
		return "AND";
	}
	
	/**
	 * States that every criteria in a group has to be separated with an OR
	 * @static
	 * @return Object - the OR type of separator.
	 */
	function getOrSeparator()
	{
		return "OR";
	}
	
	var $separator;
	var $criterionArray;
	
	/**
	 * Constructs a CriteriaGroup object
	 * 
	 * @param $separator object - Optional, The separator for every two criteria object in this class,
	 * 				default is "AND".
	 */
	function CriteriaGroup($separator= "AND")
	{
		$this->separator = $separator;
		$this->criterionArray = array();
	}
	
	/**
	 * Adds a new criteria to the query. The object added must be anyone that implements
	 * the Criterion interface.
	 */
	function addCriterion($criterion)
	{
		array_push($this->criterionArray, $criterion);
	}
	
	/**
	 * Returns true if the present criteria group has criterions added
	 * @return boolean - true if it has criterions, false if it doesn't
	 */
	function hasCriterions()
	{
		if (count($this->criterionArray) > 0);
			return true;
		
		return false;
	}
	
	/**
	 * Gets the string form of this CriteriaGroup
	 */
	function toString()
	{
		$string = "";
		
		foreach ($this->criterionArray as $criterion)
		{	
			// Add an space if the string has already something
			if ($string != "") $string = $string . " ";
			// append the other criteria
			$string = $string . $criterion->toString() . " " . $this->separator;
		}
		
		// Trim the last separator and surround the string with parenthesys
		return "(" . substr($string, 0, strlen($string) - strlen($this->separator) - 1) . ")";
	}
}

?>