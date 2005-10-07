<?php

require_once 'icfDatabase.php';
require_once 'service/persistence/persistence.php';

$test = new PersistenceTest();
$test->execute();

/**
 * Tests the persistence object
 */
class PersistenceTest
{
	/**
	 * Executes the test
	 */
	function execute()
	{
		$persistence = new Persistence("Object");
		$persistence->setProperty("ID", 1);
		assert($persistence->getSelectString() == "SELECT * FROM ##Object WHERE ID = 1");
		echo "Select string: " . $persistence->getSelectString();
	}
	
}

?>