<?php

require_once "util/date.php";

/**
 * DateFormat classes must implement this interface. DateFormat implementors parse a string
 * into a Date instance, controlling that obeys every format constraint.
 *
 * @abstract
 */
class DateFormat
{
	/**
	 * Constructs a DateFormat
	 */	
	function DateFormat()
	{
	}
	
	/**
	 * Returns the format description string so it can be shown to the user.
	 * @return String - the format description, e.g. "dd/mm/yyyy"
	 * @abstract
	 */
	function getDateFormatString()
	{
		trigger_error("Not implemented");
	}
	
	/**
	 * Returns the format description string so it can be shown to the user.
	 * @return String - the format description, e.g. "dd/mm/yyyy hh:mm:ss"
	 * @abstract
	 */
	function getDatetimeFormatString()
	{
		trigger_error("Not implemented");
	}
	
	/**
	 * Returns the string that Calendar JS Object uses to represent this format
	 * @return String - the format description, e.g. "dd/mm/y"
	 * @abstract
	 */
	function getCalendarDateFormatString()
	{
		trigger_error("Not implemented");
	}
	
	/**
	 * Parses a given date in a string and returns a date object containing it
	 * @param $string String - a string containing a date
	 * @return Date - a date object, or null if the string is not valid for the format
	 * @static
	 * @abstract
	 */
	function parseDate($string)
	{
		trigger_error("Not implemented");
	}
	
	/**
	 * Parses a given datetime in a string and returns a date object containing it
	 * @param $string String - a string containing a date
	 * @return Date - a date object, or null if the string is not valid for the format
	 * @static
	 * @abstract
	 */
	function parseDatetime($string)
	{
		trigger_error("Not implemented");
	}
	
	/**
	 * Given a date instance, translates it to a string valid to this format
	 * @param $date Date - date instance
	 * @return String - a date string 
	 * @abstract
	 */
	function toDateString($date)
	{
		trigger_error("Not implemented");
	}
	
	/**
	 * Given a date instance, translates it to a string valid to this format
	 * @param $date Date - date instance
	 * @return String - a datetime string 
	 * @abstract
	 */
	function toDatetimeString($date)
	{
		trigger_error("Not implemented");
	}
}
?>