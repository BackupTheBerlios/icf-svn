<?php

/**
 * Availables criterias for certain object searches
 *
 * @author despada 2005-04-XX
 */
class ObjectCriteria
{
	var $title;
	var $fulltext;
	
	/**
	 * Creates the object
	 */
	function ObjectCriteria()
	{
		$title = null;
		$fulltext = null;
	}
	
	function setTitle($title)
	{
		$this->title = $title;
	}
	
	function getTitle()
	{
		return $this->title;
	}
	
	function setFulltext($fulltext)
	{
		$this->fulltext = $fulltext;
	}
	
	function getFulltext()
	{
		return $this->fulltext;
	}
}

?>