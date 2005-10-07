<?php

/**
 * Represents a join made by a query object, with the purpose of walking an object graph. A
 * relationship can be of two types, and defaults always to non-mandatory.
 */
class Relationship
{
	var $query;
	var $type;
	var $mandatory = false;
	
	/**
	 * Defines the one-to-many relationship between two objects
	 * @static
	 * @return object - A OneToMany join type
	 */
	function OneToManyType()
	{
		return "OneToMany";
	}
	
	/**
	 * Defines the many-to-one relationship between two objects
	 * @static
	 * @return object - A ManyToOne join type
	 */
	function ManyToOneType()
	{
		return "ManyToOne";
	}
	
	/**
	 * Constructs a new relationship query within two objects.
	 *
	 * @param $query Query - query object of the target point of the relationship
	 * @param $type object - optional, the relationship type, default is "OneToMany"
	 */
	function Relationship(&$query, $type = "OneToMany")
	{
		$this->query =& $query;
		$this->type = $type;
		$this->mandatory = false;
	}
	
	/**
	 * Returns the query assigned to this relationship
	 *
	 * @return query - query assigned
	 */
	function& getQuery()
	{
		return $this->query;
	}
	
	/**
	 * Determines if the present relationship is mandatory or not
	 * @return boolean - true if it is, false if is not
	 */
	function isMandatory()
	{
		return $this->mandatory;
	}
	
	/**
	 * Determines if the present relationship is mandatory or not
	 * @param $mandatory boolean - set to true if this relationship is mandatory
	 */
	function setMandatory($mandatory)
	{
		$this->mandatory = $mandatory;
	}
	
	/**
	 * Returns the string representation of this relationship
	 * @param $query Query - the source of the relationship
	 * @return string - string representation
	 */
	function toString($query)
	{
		$joinString = "";
		
		// If the relationship is mandatory, enforce this using an INNER JOIN
		if ($this->mandatory)
			$joinString = "INNER JOIN ";
		else
			$joinString = "LEFT OUTER JOIN ";
		
		if ($this->type == RelationShip::OneToManyType())
		{	
			$string = "";
			$string = $string . $joinString . "##" . $this->query->getClassName() . " ON";
			$string = $string . " ##" . $query->getClassName() . ".ID = ##" . $this->query->getClassName() . "." . $query->getClassName() . "ID";		
			return $string;
		}

		if ($this->type == RelationShip::ManyToOneType())
		{
			$string = "";
			$string = $string . $joinString . "##" . $this->query->getClassName() . " ON";
			$string = $string . " ##" . $this->query->getClassName() . ".ID = ##" . $query->getClassName() . "." . $this->query->getClassName() . "ID";		
			
			return $string;
		}
		
		trigger_error("The relationship type " . $this->type . " is unknown");		
	}
}

?>