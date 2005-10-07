<?php

require_once "util/date.php";
require_once "util/dateFormat.php";

/**
 * A UsaDateFormat obeys the format "mm/dd/yyyy" and, in the case of a datetime,
 * "dd/mm/yyyy hh:MM:ss AM/PM". Hour must go between 1 and 12 and the user must specify AM or PM
 */
class UsaDateFormat extends DateFormat
{

	function UsaDateFormat()
	{
	}
	
	/**
	 * Returns the format description string so it can be shown to the user.
	 * @return String - the format description, e.g. "dd/mm/yyyy"
	 */
	function getDateFormatString()
	{
		return "mm/dd/yyyy";
	}
	
	/**
	 * Returns the format description string so it can be shown to the user.
	 * @return String - the format description, e.g. "dd/mm/yyyy hh:mm:ss"
	 */
	function getDatetimeFormatString()
	{
		return $this->getDateFormatString() . " hh:mm:ss AM/PM";
	}

	/**
	 * Returns the string that Calendar JS Object uses to represent this format
	 * @return String - the format description, e.g. "dd/mm/y"
	 * @abstract
	 */
	function getCalendarDateFormatString()
	{
		return "mm/dd/y";
	}

	/**
	 * Parses the string and returns the date instance
	 * @param $string String - date string
	 * @return the Date object if the $string is valid, or null if the $string is not valid
	 */
	function parseDate($string)
	{
		$array = split("/", $string);
		
		if (count($array) != 3) return null;			
		
		// Take the month
		$month = $array[0];
		// Take the day
		$day = $array[1];
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
	 * @return the Date object if the $string is valid, or null if the $string is not valid
	 */
	function parseDatetime($string)
	{
		$array = split(" ", $string);
		
		if (count($array) != 3) return null;
		
		// Take the date
		$dateString = $array[0];
		// Take the hour
		$hourString = $array[1];
		// Take the AM/PM
		$meridian = $array[2];
		
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
		if ($hour < 1 || $hour > 12) return null;
		if ($minute < 0 || $minute > 59 ) return null;
		if ($second < 0 || $second > 59 ) return null;
		
		// Check the meridian
		$meridian = strtoupper($meridian);
		if ($meridian != "AM" && $meridian != "PM") return null;
		
		// Everything done, return the Date instance
		// Translate the hour to the standard format for date
		if ($hour == 12 && $meridian == "AM") $hour = 0;
		if ($meridian == "PM" && $hour < 12) $hour += 12;
		
		return new Date($date->getYear(), $date->getMonth(), $date->getDay(), $hour, $minute, $second);
	}

	/**
	 * Given a date instance, translates it to a string valid to this format
	 * @param $date Date - date instance
	 * @return a date string 
	 */
	function toDateString($date)
	{
		return $date->getMonth() . "/" . $date->getDay() . "/" . $date->getYear();
	}
	
	/**
	 * Given a date instance, translates it to a string valid to this format
	 * @param $date Date - date instance
	 * @return a datetime string 
	 */
	function toDatetimeString($date)
	{
		$hour = $date->getHour();
		$meridian = "AM";
		
		if ($hour > 12) { $meridian = "PM"; $hour -= 12; };
		if ($hour == 0)  $hour = 12;
		
		return $date->getMonth() . "/" . $date->getDay() . "/" . $date->getYear() . " " . $hour . ":" . $date->getMinute() . ":" . $date->getSecond() . " " . $meridian;
	}
}
?>