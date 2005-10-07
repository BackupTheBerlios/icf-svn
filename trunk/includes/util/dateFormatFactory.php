<?php

require_once "util/standardDateFormat.php";
require_once "util/usaDateFormat.php";
require_once "util/isoDateFormat.php";

/**
 * This class constructs the adequate DateFormat interface implementor for your configuration settings.
 * You can use this DateFormat object to parse a date string and enforce a given format.
 *
 * This class is the central part of the solution proposed to the problem of Date mapping from GUI to
 * business logic, taking into account the possibility of having many different formats.
 *
 * To make use of this feature, you have two steps:
 *
 * 1) Firstly, get the adequate DateFormat according to your configuration file, like this:
 *
 * $dateFormat = DateFormatFactory::getDateFormat();
 *
 * 2) Parse your date string
 *
 * $dateString = "04/12/1978";
 * $date = $dateFormat->parseDate($string);
 *
 * Have in mind that parseDate will return null if the $string is not a valid date
 * The Date class is a highly normalized logic representation of a date. See its reference for more details.
 */
class DateFormatFactory
{
	/**
	 * Gets the adequate DateFormat interface implementor for your Configuration settings.
	 * @return DateFormat - DateFormat implementor instance
	 */
	function getDateFormat()
 	{
 		// Get configuration
 		$icfConfig = new IcfConfig();
 		$dateFormatClass = $icfConfig->cfg_date_format_class;

 		// Instance class
		$dynamicCode = "\$dateFormat = new " . $dateFormatClass . "();";		
		$dateFormat = null;
		// $dateFormat = new UsaDateFormat();
		eval($dynamicCode);
		
		// echo "DATE FORMAT: " . $dateFormat;

		// Test
		if (is_null($dateFormat))
			trigger_error("Cannot instantiate dateFormat dynamically: " . $dynamicCode);
			
		// Return the configured DateFormat
		return $dateFormat;
 	}
}
?>