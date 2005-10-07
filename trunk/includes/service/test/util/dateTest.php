<?php

require_once "util/dateFormat.php";
require_once "util/standardDateFormat.php";
require_once "util/usaDateFormat.php";
require_once "util/isoDateFormat.php";

$test = new DateTest();
$test->execute();

/**
 * Tests the Date objects
 */
class DateTest
{
	/**
	 * Executes the test
	 */
	function execute()
	{
		$standardDateFormat = new StandardDateFormat();
		$usaDateFormat = new UsaDateFormat();
		$isoDateFormat = new IsoDateFormat();
		
		// Test parsing
		assert($standardDateFormat->parseDate("13/01/2005") != null);
		assert($standardDateFormat->parseDatetime("13/01/2005 23:20:30") != null);
		assert($usaDateFormat->parseDate("12/15/2005") != null);
		assert($usaDateFormat->parseDatetime("01/05/2005 10:20:30 PM") != null);
		assert($isoDateFormat->parseDate("2005-11-25") != null);
		assert($isoDateFormat->parseDatetime("2004-01-25 22:10:33") != null);
		
		// Test date object
		$date = $standardDateFormat->parseDatetime("15/01/2004 10:20:11");
		assert($date->getYear() == 2004);
		assert($date->getMonth() == 01);
		assert($date->getDay() == 15);
		assert($date->getHour() == 10);
		assert($date->getMinute() == 20);
		assert($date->getSecond() == 11);
		
		// Reparse date object
		echo "toDatetimeString: " . $standardDateFormat->toDatetimeString($date);
	}
	
}

?>