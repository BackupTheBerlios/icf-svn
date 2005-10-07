<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

/**
 * Represents an item of the toolbar. It is included inside a toolbar object.
 */
class IcfToolbarItem
{
	var $name;
	var $title;
	var $url;
	var $image;
	var $image2;
  var $onclick;
  
	/**
	 * Constructs a toolbar item
	 */
	function icfToolbarItem() 
	{
		$this->onclick = "";
	}
	
	/**
	 * Name of the item
	 */
	function setName($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Name of the item
	 */
	function getName()
	{
		return $this->name;
	}

	/**
	 * Title of the item
	 */
	function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
	 * Title of the item
	 */
	function getTitle()
	{
		return $this->title;
	}

	/**
	 * URL that points the its destination view
	 */
	function setUrl($url)
	{
		$this->url = $url;
	}
	
	/**
	 * URL that points the its destination view
	 */
	function getUrl()
	{
		return $this->url;
	}
	
	/**
	 * Image that can be used to represent it in the toolbar
	 */
	function getImage()
	{
		return $this->image;
	}

	/**
	 * Image that can be used to represent it in the toolbar
	 */
	function setImage($image)
	{
		$this->image = $image;
	}
	
	/**
	 * Another image that can be used to represent it in the toolbar
	 */
	function getImage2()
	{
		return $this->image2;
	}

	/**
	 * Another image that can be used to represent it in the toolbar
	 */
	function setImage2($image2)
	{
		$this->image2 = $image2;
	}
	
	/**
	 * Sets the name of the jscript method to be called when clicked on the toolbar button
	 * @param $onclick string - the onclick button
	 */
	function setOnclick($onclick)
	{
		$this->onclick = $onclick;
	}
	
	/**
	 * Returns the name of the jscript method to be called when clicked on the toolbar button
	 * @return string - the jscript to execute
	 */
	function getOnclick()
	{
		return $this->onclick;
	}
}
?>