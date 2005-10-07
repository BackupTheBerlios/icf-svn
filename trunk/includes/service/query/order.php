<?php

/**
 * An order for the results returned by the query
 */
class Order
{
	var $query;
	var $field;
	var $orderType;
	
	/**
	 * Order type that sorts the results in a descendent way
	 * @static 
	 * @return object - and order type
	 */
	function OrderTypeDesc()
	{
		return "DESC";
	}
	
	/**
	 * Order type that sorts the results in an ascendent way
	 * @static 
	 * @return object - and order type
	 */
	function OrderTypeAsc()
	{
		return "ASC";
	}
	
	/**
	 * Constructs an order object.
	 *
	 * @param $query Query - the query object whose field is being ordered
	 * @param $field string - the field name to be ordered
	 * @param $orderType object - optional, the order type to be imposed to this field, default is OrderTypeDesc
	 */
	function Order($query, $field, $orderType = "DESC")
	{
		$this->query = $query;
		$this->field = $field;
		$this->orderType = $orderType;
	}
	
	/**
	 * Gets the field name of this order
	 * @return string - the field to be ordered
	 */
	function getField()
	{
		return $this->field;
	}

	/**
	 * Gets the order type imposed to this field
	 * @return object - type of the order
	 */
	function getOrderType()
	{
		return $this->orderType;
	}

	/**
	 * Gets the query object that owns the field
	 * @return Query - assigned query object
	 */
	function getQuery()
	{
		return $this->query;
	}
	
	/**
	 * Translates this query object to its string representation
	 *
	 * @return string - string representation 
	 */
	function toString()
	{
		return "##" . $this->query->getClassName() . "." . $this->getField() . " " . $this->getOrderType();
	}
	
}

?>