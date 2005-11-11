<?php

require_once "icfDatabase.php";
require_once "classes/object.php";
require_once "classes/action.php";
require_once "service/query/query.php";
require_once "service/persistence/persistence.php";
require_once "util/dateFormatFactory.php";
/**
 * Class that implements the mapper design pattern for the Object class
 *
 * @author despada 2005-04-XX
 */
class ObjectMapper extends Mapper
{
	/**
	 * Constructs the mapper
	 * @param $conn connection - Connection object that contains a transaction context
	 */
	function& ObjectMapper()
	{
		$this->Mapper("Object");
	}
		
	/**
	 * Makes the object persistent
	 * @param $object object to be made persistent
	 * @return object - the same object with its new id
	 */
	function& save($object)
	{
		$persistence = $this->newPersistenceObject();
		
		// Set created and createdBy
		$isoDateFormat = new IsoDateFormat();
		$session = new Session();
		
		$date = Date::getTodayDate();
		$user = $session->getSessionUser();
		
		$object->setCreated($isoDateFormat->toDatetimeString($date));
		$object->setCreatedBy($user->getId());
		$object->setHits(0);
		
		// Save !!
		$persistence->setProperty("classID", $object->getClassID());
		$persistence->setProperty("created", $object->getCreated());
		$persistence->setProperty("createdBy", $object->getCreatedBy());
		$persistence->setProperty("endPublishing", $object->getEndPublishing());
		$persistence->setProperty("fullTextIndex", $object->getFullTextIndex());
		$persistence->setProperty("hits", $object->getHits());
		$persistence->setProperty("isPublished", $object->getIsPublished());
		$persistence->setProperty("startPublishing", $object->getStartPublishing());
		$persistence->setProperty("updated", $object->getUpdated());
		$persistence->setProperty("updatedBy", $object->getUpdatedBy());
		
		$id = $persistence->save();		
		$object->setId($id);
		return $object;
	}

	/**
	 * Synchronizes an object with the database
	 * @param $object object to be synchronized. It should already be persistent (had its id assigned)
	 */
	function update($object)	
	{
		// Set updated and updatedBy
		$isoDateFormat = new IsoDateFormat();
		$session = new Session();
		
		$date = Date::getTodayDate();
		$user = $session->getSessionUser();
		
		$object->setUpdated($isoDateFormat->toDatetimeString($date));
		$object->setUpdatedBy($user->getId());
		
		$persistence = $this->newPersistenceObject();
		
		$persistence->setProperty("ID", $object->getId());
		$persistence->setProperty("classID", $object->getClassID());
		$persistence->setProperty("created", $object->getCreated());
		$persistence->setProperty("createdBy", $object->getCreatedBy());
		$persistence->setProperty("endPublishing", $object->getEndPublishing());
		$persistence->setProperty("fullTextIndex", $object->getFullTextIndex());
		$persistence->setProperty("hits", $object->getHits());
		$persistence->setProperty("isPublished", $object->getIsPublished());
		$persistence->setProperty("startPublishing", $object->getStartPublishing());
		$persistence->setProperty("updated", $object->getUpdated());
		$persistence->setProperty("updatedBy", $object->getUpdatedBy());
		
		$persistence->update();
	}
		
	/**
	 * Gets the objects in a given folder
	 * 
	 * @param $folderId id of a certain folder
	 * @return List of Object class objects
	 */
	function& findByFolder($folderId)
	{
		$objectQuery = $this->newQueryObject();
		
		$objectFolderQuery =& $objectQuery->queryRelationedClass("ObjectFolder");
		$folderQuery =& $objectFolderQuery->queryRelationedClass("Folder", Relationship::ManyToOneType());
		
		$criteria =& new Criteria($folderQuery, "ID", $folderId);		
		$objectQuery->setCriterion($criteria);
		
		$order = new Order($objectFolderQuery, "position", Order::OrderTypeAsc());
		$objectQuery->addOrder($order);
		
		return $this->mapAll($objectQuery->execute());
	}
	
	/**
	 * Search the objects that belong to a class
	 * @param $classId int - Class id
	 * @return Array - List of Classes
	 */
	function& findByClassId($classId, $sortByTitle = false)
	{
		$query = $this->newQueryObject();
		
		$criteria =& new Criteria($query, "classID", $classId);
		$query->setCriterion($criteria);
		
		$rv = $this->mapAll($query->execute());

		if ($sortByTitle)
		{
			$ln = count($rv);
			$titles = array();

			for ($i = 0; $i < $ln; $i++)
			{
				$titles[$i] = $rv[$i]->getTitle();
			}
			
			$rv = $this->quicksortObjectByTitle($rv, $titles);
		
		}
		
		return $rv;
	}


	function& quicksortObjectByTitle($seq, $titles) {

		if(count($seq)>1) 
		{
			$k = $seq[0];
			$kt = $titles[0];

			$x = array();
			$y = array();
		
			$xt = array();
			$yt = array();

			for($i=1; $i<count($seq); $i++) 
			{
				if($titles[$i] <= $kt) 
				{
					$x[] = $seq[$i];
					$xt[] = $titles[$i];
				} 
				else 
				{
					$y[] = $seq[$i];
					$yt[] = $titles[$i];
				}
			}

			$x = $this->quicksortObjectByTitle($x, $xt);
			$y = $this->quicksortObjectByTitle($y, $yt);
			return array_merge($x, array($k), $y);
		} 
		else 
		{
			return $seq;
		}
	}

	/**
	 * Gets the pending objects for the user, these are the ones that
	 * aren't published and the user can publish
	 *
	 * @return Array - An array of object objects
	 */
	function& findPending()
	{
		$query = $this->newQueryObject();
		/* @var $query Query */
		
		$criteria = new Criteria($query, "isPublished", "0");
		$query->setCriterion($criteria);
				
		$objectArray = $this->mapAll($query->execute());
		
		// Permissions filter
		$allowedObjectArray = array();
		foreach ($objectArray as $object)
		{
			if ($object->canDoAction(null, Action::PUBLISH_OBJECTS_ACTION()) == false) continue;
			array_push($allowedObjectArray, $object);
		}
		return $allowedObjectArray;
	}
	
	/**
	 * Publishes a given content
	 * @param $id int - id of the content to publish
	 */
	function publish($id)
	{		
		$object = $this->get($id);
		$object->setIsPublished(true);
		$this->update($object);
	}
	
	/**
	 * Unpublishes a given content
	 * @param id int - id of the content to unpublish
	 */
	function unpublish($id)
	{	
		$object = $this->get($id);
		$object->setIsPublished(false);
		$this->update($object);
	}	
	
	/**
	 * Maps a Object object contained in a recordset to an object representation
	 *
	 * @param $rs Recordset filled with the object data
	 * @return objeto Mapped Object object
	 */	
	function& mapOne($rs)
	{
		if ($rs->EOF)
			return null;
		
		$object = new Object();
		
		$object->setId($rs->fields["ID"]);
		$object->setClassID($rs->fields["classID"]);
		$object->setCreated($rs->fields["created"]);
		$object->setCreatedBy($rs->fields["createdBy"]);
		$object->setEndPublishing($rs->fields["endPublishing"]);
		$object->setFullTextIndex($rs->fields["fullTextIndex"]);
		$object->setHits($rs->fields["hits"]);
		$object->setIsPublished($rs->fields["isPublished"]);
		$object->setStartPublishing($rs->fields["startPublishing"]);
		$object->setUpdated($rs->fields["startPublishing"]);
		$object->setUpdated($rs->fields["updated"]);
		$object->setUpdatedBy($rs->fields["updatedBy"]);
		
		return $object;
	}
}


?>