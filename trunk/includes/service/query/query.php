<?php

require_once "icfDatabase.php";
require_once "criteriaGroup.php";
require_once "criteria.php";
require_once "relationship.php";
require_once "order.php";

/**
 * Implementation of the Query Object Design Pattern, allows the programmer
 * to issue ICF object queries to the database in an object-oriented way.
 *
 * To use the Query object implementation, create a new Query object passing the name of the object
 * to query as a constructor parameter.
 *
 * $query = new Query("object");
 *
 * Depending on the complexity of your query, you have many options:
 *
 * 1) If you have only one criteria, add a new Criteria object to the query like this:
 *
 * // Default criteria type is "equal"
 * $criteria = new Criteria($query, "field", "10");
 * $query->setCriterion($criteria);
 *
 * Note that, in the Criteria constructor, you explicit the type of clause you issue, the field
 * being queried and the value of the query.
 *
 * 2) If you have any other criteria situation, the CriteriaGroup object may have all the flexibility you need.
 * 		CriteriaGroups may contain as many criteria objects as you want, and other CriteriaGroups. This
 *		is possible because both CriteriaGroup and Criteria implement the Criterion interface.
 *
 * // Note that you have to define what kind of separator you'll use between the criterias composing
 * // the CriteriaGroup.
 * $criteriaGroup = new CriteriaGroup(CriteriaGroup::getAndSeparator());
 *
 * // Create and add some criteria objects
 * $criteria = new Criteria($query, "hits", "10", Criteria::lessEqualThanType());
 * $criteriaGroup->addCriterion($criteria);
 * // Default criteria type is "equal"
 * $criteria = new Criteria($query, "hits", "1");
 * $criteriaGroup->addCriterion($criteria);
 *
 * // Add the criterion to the query
 * $query->setCriterion($criteriaGroup);
 *
 * 3) You may issue object relational queries, navigating the object graph through the relationships.
 * To do this, you must call &queryRelationedClass($className):
 *
 * // Note that you have to supply the class that has a relationship with the class queried by the
 * // $query object.
 * $classQuery =& $query->queryRelationedClass("class");
 *
 * // Now you have a new Query object, totally independent from (but included in) our previous root query object.
 * // You may add new Criterias or CriteriaGroups to the root query, using the new $classQuery as a parameter
 * // for their constructor.
 *
 * There is an important point to take into account: when specifying a relationed class, you must specify
 * too the cardinality of the relationship. The default is OneToMany, but you have the option ManyToOne
 * at your disposal. You can issue the request in this way:
 *
 * $classQuery =& $query->queryRelationedClass("class", Relationship::ManyToOne);
 * 
 * Lastly, you have the option of adding one or many orders to the results. The order objects are assigned
 * to the root query, like this:
 *
 * // You may specify two different types of orders as a third argument to the constructor, OrderTypeAsc() or
 * // OrderTypeDesc()
 * $order = new Order($query, "hits");
 * // Add this order to the query
 * $query->addOrder($order);
 * 
 * After doing all this, you are prepared to issue a query to the EIL. This is done using the execute()
 * method, as follows:
 *
 * $rows = $query->execute();
 *
 * After this, you can iterate the rows freely, and you should be able to map the results to objects using a mapper.
 */
class Query
{
	var $className;
	var $relationships;
	var $criterion;
	var $orders;
	var $conn;
	
	/**
	 * Constructs a query object
	 *
	 * @param $className string - the class to query
	 */
	function Query($className)
	{
		// Class name must start with upper case
		$className = strtoupper(substr($className, 0, 1)) . substr($className, 1);
		
		$this->className = $className;
		$this->relationships = array();
		$this->criterion = null;
		$this->orders = array();
	}
	
	/**
	 * Injects a connection object to this query object
	 * @param $conn Connection - if a transaction context is needed.
	 */
	function setConnection(&$conn)
	{
		$this->conn =& $conn;
	}

	/**
	 * Allows querying an associated class to this one, creates a new Query Object and returns it.
	 * The programmer must add criterions to the returned query.
	 *
	 * @param $className string - the class associated to the root class being queried
	 * @param $type object - type of relationship specified in the Relationship class, default is "OneToMany"
	 * @return Query object
	 */
	function &queryRelationedClass($className, $type = "OneToMany")
	{
		$query = new Query($className);
		$relationship = new Relationship(&$query, $type);
		array_push($this->relationships, &$relationship);

		return $query;
	}

	/**
	 * Gets the name of the class being queried by this query
	 *
	 * @return name of the class
	 */
	function& getClassName()
	{
		return $this->className;
	}

	/**
	 * Sets the criterion to use in this query. You can use the Criteria implementation
	 * if you only want one criteria, or employ the CriteriaGroup class for more flexibility.
	 *
	 * @param $criterion Criterion - An implementation of the $criterion interface, tipically Criteria or CriteriaGroup
	 */
	function setCriterion(&$criterion)
	{
		$this->criterion = $criterion;
	}

	/**
	 * Adds an order object to this query, with the purpose of sorting the resulting objects
	 *
	 * @param $order The order object to apply
	 */
	function addOrder(&$order)
	{
		array_push($this->orders, $order);
	}
	
	/**
	 * Gets the query issued in a string
	 * @access private
	 * @return string - the generated query
	 */
	function getQueryString()
	{
		$string = "SELECT DISTINCT ##" . $this->className . ".* FROM ##" . $this->className;

		// Joins
		$string = $string . $this->getJoinString();

		// Criterions of this query
		$whereString = $this->getWhereString();

		if ($whereString != "")
		{
			$string = $string . " WHERE ";
			$string = $string . $whereString;
		}

		// The orders imposed
		$orderString = $this->getOrderString();
		
		if ($orderString != "")
		{
			$string = $string . " ORDER BY ";
			$string = $string . $orderString;
		}
		
		return $string;
	}

	/**
	 * Gets the where criterias assigned to this query in a string
	 * @access protected
	 * @return string to append to the main query. If there are no where clauses, returns ""
	 */
	function getWhereString()
	{
		$string = "";

		// Criterion of this query
		if ($this->criterion != null)
		{
			$string = $string . $this->criterion->toString();
		}

		return $string;
	}

	/**
	 * Gets the table joins assigned to this query
	 * @access protected
	 * @return string to append to the main query. If there are no joins, returns ""
	 */
	function getJoinString()
	{
		$string = "";

		foreach ($this->relationships as $relationship)
		{
			$string = $string . " ";
			$string = $string . $relationship->toString($this);

			// Subjoins
			$query =& $relationship->getQuery();
			$string = $string . $query->getJoinString();
		}

		return $string;
	}

	/**
	 * Gets the sorting assigned to this criteria. The sorts are not recursive, only the order
	 * for the main criteria is recolected.
	 * @return string, the assigned order
	 */
	function getOrderString()
	{
		$string = "";
		
		foreach ($this->orders as $order)
		{
			if ($string != "") $string = $string . ", ";
			$string = $string . $order->toString();
		}
		
		return $string;
	}
	
	/**
	 * Executes the query and returns the rows received from the EIL
	 * @return Recordset - ADO Recordset that can be read in a standard way (and should be able to be mapped by a mapper)
	 */
	function& execute()
	{
		// Get the query
		$query = $this->getQueryString();

		// echo "Query: " . $query . "<br/>";
		
		// Execute it
		$icfDatabase = new IcfDatabase();
		if ($this->conn == null)
			$rs = $icfDatabase->dbQuery($query);
		else
			$rs = $icfDatabase->dbQueryInTx($query, $this->conn);
		
		// echo "RECORDSET (" . $query . "): " . $rs . "<br/>";
		
		return $rs;
	}
}

?>