<?php

/**
 * Represents a supported IDEA language
 *
 * @author despada 2005-04-09
 */
class Language
{
	var $id;
	var $title;
	var $code;
	var $isMain;
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}	
	
	function setTitle($title)
	{
		$this->title = $title;
	}
	
	function getTitle()
	{
		return $this->title;
	}

	function setCode($code)
	{
		$this->code = $code;
	}
	
	function getCode()
	{
		return $this->code;
	}

	function setIsMain($isMain)
	{
		$this->isMain = $isMain;
	}
	
	function getIsMain()
	{
		return $this->isMain;
	}

}

?>