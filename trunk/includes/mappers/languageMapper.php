<?php

require_once 'icfDatabase.php';
require_once "classes/language.php";
require_once "service/query/query.php";
require_once "mappers/mapper.php";

/**
 * Class that implements the mapper design pattern for the Language class
 *
 * @author despada 2005-04-XX
 */
class LanguageMapper extends Mapper
{
	var $icfDatabase;
	
	/**
	 * Constructs the mapper
	 */
	function& LanguageMapper()
	{
		$this->Mapper("Language");
	}
	
	/**
	 * Gets all existing languages
	 * @return array - Array of language objects. First of all comes the main language.
	 */
	function& getAll()
	{
		$query = $this->newQueryObject();
		$order = new Order($query, "isMain", Order::OrderTypeDesc());
		return $this->mapAll($query->execute());
	}
	
	/**
	 * Gets main language
	 * @return Language - main Language object
	 */
	function& getMain()
	{
		$query = $this->newQueryObject();
		/* @var $query Query */		
		$criteria = new Criteria($query, "isMain", true);		
		$query->setCriterion($criteria);
		return $this->mapOne($query->execute());
	}

	/**
	 * Maps a Language object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Language object
	 */	
	function& mapOne($rs)
	{
		if ($rs == null)
			return null;
		
		$object = new Language();
		
		$object->setId($rs->fields["ID"]);
		$object->setCode($rs->fields["code"]);
		$object->setIsMain($rs->fields["isMain"]);
		$object->setTitle($rs->fields["title"]);
		
		return $object;
	}
}

?>
