<?php

require_once "util/date.php";
require_once "util/dateFormat.php";

/**
 * A standard DateFormat obeys the format "dd/mm/yyyy" in the case of a date,
 * and the format "dd/mm/yyyy hh:mm:ss" in the case of a datetime.
 * Hours must go between 0 and 23
 */
class StandardDateFormat extends DateFormat
{
	
	/**
	 * Returns the format description string so it can be shown to the user.
	 * @return String - the format description, e.g. "dd/mm/yyyy"
	 */
	function getDateFormatString()
	{
		return "dd/mm/yyyy";
	}
	
	/**
	 * Returns the format description string so it can be shown to the user.
	 * @return String - the format description, e.g. "dd/mm/yyyy hh:mm:ss"
	 */
	function getDatetimeFormatString()
	{
		return "dd/mm/yyyy hh:mm:ss";
	}
	
	/**
	 * Returns the string that Calendar JS Object uses to represent this format
	 * @return String - the format description, e.g. "dd/mm/y"
	 * @abstract
	 */
	function getCalendarDateFormatString()
	{
		return "dd/mm/y";
	}
	
	/**
	 * Parses the string and returns the date instance
	 * @param $string String - date string
	 * @return Date - the Date object if the $string is valid, or null if the $string is not valid
	 */
	function parseDate($string)
	{
		$array = split("/", $string);
		
		if (count($array) != 3) return null;
		
		// Take the day
		$day = $array[0];
		// Take the month
		$month = $array[1];
		// Take the year
		$year = $array[2];
		
		// Year has to be a four digit number
		if (strlen($year) != 4) return null;
		
		// Every parameter must be a number
		if (is_numeric($day) == false) return null;
		if (is_numeric($month) == false) return null;
		if (is_numeric($year) == false) return null;
		
		$day = intval($day);
		$month = intval($month);
		$year = intval($year);		
		
		// Respect limits
		if ($day < 1 || $day > 31) return null;
		if ($month < 1 || $month > 12) return null;
		
		// Check if the given day is correct for the given month
		if (checkdate($month, $day, $year) == false) return null;
		
		// Everything done, return the Date instance
		return new Date($year, $month, $day);
	}
	
	/**
	 * Parses the string and returns the date instance
	 * @param $string String - date string
	 * @return Date - the Date object if the $string is valid, or null if the $string is not valid
	 */
	function parseDatetime($string)
	{
		$array = split(" ", $string);
		
		if (count($array) != 2) return null;
		
		// Take the date
		$dateString = $array[0];
		// Take the hour
		$hourString = $array[1];
		
		// Parse date
		$date = $this->parseDate($dateString);
		if ($date == null) return null;
		
		// Parse hour
		$hourArray = split(":", $hourString);
		// Take the hour
		$hour = $hourArray[0];
		// Take the minute
		$minute = $hourArray[1];
		// Take the seconds
		$second = $hourArray[2];
		
		// Every parameter must be a number
		if (is_numeric($hour) == false) return null;
		if (is_numeric($minute) == false) return null;
		if (is_numeric($second) == false) return null;
		
		$hour = intval($hour);
		$minute = intval($minute);
		$second = intval($second);
				
		// Respect limits
		if ($hour < 0 || $hour > 23) return null;
		if ($minute < 0 || $minute > 59 ) return null;
		if ($second < 0 || $second > 59 ) return null;
		
		// Everything done, return the Date instance
		return new Date($date->getYear(), $date->getMonth(), $date->getDay(), $hour, $minute, $second);
	}
	
	/**
	 * Given a date instance, translates it to a string valid to this format
	 * @param $date Date - date instance
	 * @return String - a date string 
	 */
	function toDateString($date)
	{
		return $date->getDay() . "/" . $date->getMonth() . "/" . $date->getYear();
	}
	
	/**
	 * Given a date instance, translates it to a string valid to this format
	 * @param $date Date - date instance
	 * @return String - a datetime string 
	 */
	function toDatetimeString($date)
	{
		return $date->getDay() . "/" . $date->getMonth() . "/" . $date->getYear() . " " . $date->getHour() . ":" . $date->getMinute() . ":" . $date->getSecond();			
	}
	
}
?>