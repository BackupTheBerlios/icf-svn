<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

/**
 * Represents the menu.
 */
class IcfMenu
{
	var $contents;
	var $folders;
	
	/**
	 * Creates a new menu
	 */
	function IcfMenu()
	{
		$content = array();
		$folders = array();
	}
	
	/**
	 * Sets the contents for the menu
	 * @param $contents array - set of contents
	 */
	function setContents($contents)
	{
		$this->contents = $contents;
	}
	
	/**
	 * Adds a new folder item to the folders menu
	 * @param $folder string - new folder to add
	 */
	function setFolders($folders)
	{
		$this->folders = $folders;
	}
	
	/**
	 * Gets the saved contents
	 * @return array - list of contents
	 */
	function getContents()
	{
		return $this->contents;
	}
	
	/**
	 * Gets the saved folders
		* @return array - list of folders
	 */
	function getFolders()
	{
		return $this->folders;
	}
}
?>