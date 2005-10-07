<?php

/**
 * Represents a Date.
 * This class implements the Immutable pattern, that means, it cannot be modified after initialized.
 *
 * Being a highly normalized representation of a date, you can get the day, month, year, hour, minute or
 * second part of a Date using getters.
 * 
 * This class is part of the solution proposed to the problem of Date mapping from GUI to business logic,
 * taking into account the possibility of having many different formats. See DateFormatFactory for more
 * details.
 */
class Date
{
	// Date
	var $day;
	var $month;
	var $year;
		
	// Time
	var $hour;
	var $minute;
	var $second;	
	
	/**
	 * Constructs a Date class
	 * @param $year int - a year
	 * @param $month int - a month
	 * @param $day int - a day 
	 * @param $hour int - a hour
	 * @param $minute int - a minute
	 * @param $second int - a second
	 */
	function Date($year, $month, $day, $hour = 00, $minute = 00, $second = 00)
	{
		$this->year = $year;
		$this->month = $month;
		$this->day = $day;
		
		$this->hour = $hour;
		$this->minute = $minute;
		$this->second = $second;
	}
	
	/**
	 * Gets a Date object filled with data of today
	 * @return Date - Date object
	 * @static
	 */
	function getTodayDate()	
	{	
		$today = getdate();
		$date = new Date($today["year"], $today["mon"], $today["mday"], $today["hours"], $today["minutes"], $today["seconds"]);
		
		return $date;
	}
	
	/**
	 * Gets the day
	 * @return int - day, from 1 to 31
	 */
	function getDay()
	{
		return $this->day;
	}
	
	/**
	 * Gets the month
	 * @return int - month, from 1 to 12
	 */
	function getMonth()
	{
		return $this->month;
	}
	
	/**
	 * Gets the year
	 * @return int - year, from -intMax to +intMax
	 */
	function getYear()
	{
		return $this->year;
	}
	
	/**
	 * Gets the hour
	 * @return int - hour, from 0 to 23
	 */
	function getHour()
	{
		return $this->hour;
	}
	
	/**
	 * Gets the minute part
	 * @return int - minute, from 0 to 59
	 */
	function getMinute()
	{
		return $this->minute;
	}
	
	/**
	 * Gets the seconds part
	 * @return int - seconds, from 0 to 59
	 */
	function getSecond()
	{
		return $this->second;
	}

}
?>