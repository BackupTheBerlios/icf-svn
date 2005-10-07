<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

/**
 * Represents the toolbar. It includes toolbar objects.
 */
class IcfToolbar
{
	var $toolbarItem;

	/**
	 * Constructs a toolbar
	 */
	function icfToolbar() 
	{
		$this->toolbarItem = array();
	}
	
	/**
	 * Adds a new toolbar item
	 */
	function addToolbarItem(&$toolbarItem)
	{
		array_push($this->toolbarItem, $toolbarItem);
	}
	
	/**
	 * Returns the toolbar as an array
	 * @return array - The array is reversed, so the first added item is the last shown
	 */
	function& toArray()
	{
		return array_reverse($this->toolbarItem);
	}	
}
?>