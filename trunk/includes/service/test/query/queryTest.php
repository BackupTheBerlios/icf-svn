<?php

require_once "service/query/query.php";
require_once "service/query/criteria.php";
require_once "service/query/criteriaGroup.php";
require_once "service/query/order.php";

echo "Simple query to object doing a join with class and looking up the class.className field<br/>";

$query = new Query("object");
$classQuery =& $query->queryRelationedClass("class");

$criteria = new Criteria($classQuery, "className", "news");
$query->setCriterion($criteria);

echo "Resulting query: " . $query->getQueryString() . "<br/>";
assert("SELECT DISTINCT ##Object.* FROM ##Object LEFT OUTER JOIN ##Class ON ##Object.ID = ##Class.ObjectID WHERE ##Class.className = 'news'" == $query->getQueryString());

echo "Query with CriteriaGroup instead of only one Criteria<br/>";

$query = new Query("object");

$criteriaGroup = new CriteriaGroup(CriteriaGroup::getAndSeparator());

$criteria = new Criteria($query, "hits", "10", Criteria::lessEqualThanType());
$criteriaGroup->addCriterion($criteria);
$criteria = new Criteria($query, "hits", "1", Criteria::moreEqualThanType());
$criteriaGroup->addCriterion($criteria);

$query->setCriterion($criteriaGroup);
echo "Resulting query: " . $query->getQueryString() . "<br/>";

echo "Query with multiples Criteria Groups and an order<br/>";

$query = new Query("object");

$criteriaGroupRoot = new CriteriaGroup(CriteriaGroup::getAndSeparator());

$criteriaGroupOne = new CriteriaGroup(CriteriaGroup::getAndSeparator());
$criteria = new Criteria($query, "created", "2005-04-15", Criteria::lessThanType());
$criteriaGroupOne->addCriterion($criteria);

$criteriaGroupTwo = new CriteriaGroup(CriteriaGroup::getOrSeparator());
$criteria = new Criteria($query, "updatedBy", "1");
$criteriaGroupTwo->addCriterion($criteria);
$criteria = new Criteria($query, "updatedBy", "2");
$criteriaGroupTwo->addCriterion($criteria);

$criteriaGroupRoot->addCriterion($criteriaGroupOne);
$criteriaGroupRoot->addCriterion($criteriaGroupTwo);

$query->setCriterion($criteriaGroupRoot);

$order = new Order($query, "hits", Order::OrderTypeAsc());
$query->addOrder($order);

echo "Resulting query: " . $query->getQueryString() . "<br/>";
$rs = $query->execute();
echo "Executed query: ";
print_r($rs);
?>