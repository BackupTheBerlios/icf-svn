<?php

require_once "icfDatabase.php";
require_once "mappers/objectMapper.php";
require_once "service/dto/objectCriteria.php";
require_once "mappers/objectAttributeMapper.php";
require_once "mappers/objectFolderMapper.php";
require_once "mappers/objectRelationMapper.php";

/**
 * Service object for the Object class, gives access to the GUI programmer to Business Logic
 * coordinating transactions.
 *
 * @author despada 2005-04-XX
 */
class ObjectService
{
	/**
	 * Publishes an object
	 * @param $id int - object id
	 */
	function publish($id)
	{
		$objectMapper = new ObjectMapper();
		$objectMapper->publish($id);
	}
	
	/**
	 * Unpublishes an object
	 * @param $id int - object id
	 */
	function unpublish($id)
	{
		$objectMapper = new ObjectMapper();
		$objectMapper->unpublish($id);
	}

	/**
	 * Deletes an object
	 * @param $id int - object id
	 */
	function delete($id)
	{
		$objectMapper = new ObjectMapper();
		$objectMapper->delete($id);
	}
	
	/**
	 * Publishes an array of objects
	 * @static
	 * @param $idArray array - array of id objects
	 */
	function publishArray($idArray)
	{
		// Coordinate transaction
		$icfDatabase = new IcfDatabase();		
		$conn = $icfDatabase->dbOpen();
		$conn->StartTrans();
		
		$objectMapper = new ObjectMapper();
		$objectMapper->setConnection($conn);
		
		// Call business logic
		foreach($idArray as $id)
		{
			$objectMapper->publish($id);
		}
		
		// Close transaction
		$conn->completeTrans();
		$icfDatabase->dbClose($conn);
	}

	/**
	 * Unpublishes an array of objects
	 * @static
	 * @param $idArray array - array of id objects
	 */	
	function unpublishArray($idArray)
	{
		// Coordinate transaction
		$icfDatabase = new IcfDatabase();		
		$conn = $icfDatabase->dbOpen();
		$conn->StartTrans();
		
		$objectMapper = new ObjectMapper();
		$objectMapper->setConnection($conn);
		
		// Call business logic
		foreach($idArray as $id)
		{
			$objectMapper->unpublish($id);
		}
		
		// Close transaction
		$conn->completeTrans();
		$icfDatabase->dbClose($conn);
	}

		/**
	 * Deletes an array of objects
	 * @static
	 * @param $idArray array - array of id objects
	 */	
	function deleteArray($idArray)
	{
		// Coordinate transaction
		$icfDatabase = new IcfDatabase();		
		$conn = $icfDatabase->dbOpen();
		$conn->StartTrans();
		
		$objectMapper = new ObjectMapper();
		$objectMapper->setConnection($conn);
		
		// Call business logic
		foreach($idArray as $id)
		{
			$objectMapper->delete($id);
		}
		
		// Close transaction
		$conn->completeTrans();
		$icfDatabase->dbClose($conn);
	}
	
	/**
	 * Searches objects by text parameters
	 * @param $classId Id of the class
	 * @param $text Text to lookup in the objects
	 * @param $fullTextSearch if true, the text is searched in all text fields and every coincidence is returned. 
	 *				If false, it is only looked up in the title.
	 */
	function findByText($classId, $text, $fullTextSearch, $folderId = -1)
	{
		$objectQuery = new Query("Object");
		$criteriaGroup = new CriteriaGroup(CriteriaGroup::getAndSeparator());
		
		// Lookup by class...
		$criteria = new Criteria($objectQuery, "classID", $classId);
		$criteriaGroup->addCriterion($criteria);

		if ($folderId != -1 && $folderId != null)
		{
			// Lookup by folder...
			$objectFolderQuery =& $objectQuery->queryRelationedClass("ObjectFolder");

			$folderCriteria = new Criteria($objectFolderQuery, "folderID", $folderId);
			$criteriaGroup->addCriterion($folderCriteria);
		}

		// ... and criterias sent
		if ($fullTextSearch)
		{			
			// by fullTextIndex
			$criteria = new Criteria($objectQuery, "fullTextIndex", $text, Criteria::likeType());
			$criteriaGroup->addCriterion($criteria);
		}
		else
		{
			// Issue a query to ObjectAttribute entity
			$objectAttributeQuery =& $objectQuery->queryRelationedClass("ObjectAttribute");
			
			// Get the class
			$baseClassMapper = new BaseClassMapper();
			$class = $baseClassMapper->get($classId);
			
			/* @var $class BaseClass */
			// Get the fields that compose the title
			$titleClassAttributesArray = $class->getTitleClassAttributes();					
			
			// We need to query all title fields using the given value with OR (anyone can match)
			$criteriaGroupTitle = new CriteriaGroup(CriteriaGroup::getOrSeparator());
			foreach($titleClassAttributesArray as $titleClassAttribute)
			{	
				// Must belong to the given class and LIKE the given title
				$criteriaGroupItem = new CriteriaGroup();				
				/* @var $titleClassAttribute ClassAttribute */
				$criteriaGroupItem->addCriterion($criteria);
				$criteria = new Criteria($objectAttributeQuery, "classAttributeId", $titleClassAttribute->getId());
				$criteriaGroupItem->addCriterion($criteria);
				$criteria = new Criteria($objectAttributeQuery, "value", $text, Criteria::likeType());
				$criteriaGroupItem->addCriterion($criteria);
				
				$criteriaGroupTitle->addCriterion($criteriaGroupItem);
			}
			
			// Set the title criteria					
			$criteriaGroup->addCriterion($criteriaGroupTitle);
		}
		
		$objectQuery->setCriterion($criteriaGroup);
		
		// Execute the query and map the results
		$objectMapper = new ObjectMapper();
		$rv = $objectMapper->mapAll($objectQuery->execute());

		return $rv;
	}
	
	/**
	 * Saves a new object
	 * @param $object Object - a new object
	 * @return Newly generated object with its id set
	 */
	function save(&$object)
	{	
		// Fill object Fulltext index
		$fullTextIndex = $this->generateFullTextIndex($object);
		$object->setFullTextIndex($fullTextIndex);
		
		// Coordinate transaction
		$icfDatabase = new IcfDatabase();		
		$conn = $icfDatabase->dbOpen();
		$conn->StartTrans();
		
		// Save object
		$objectMapper = new ObjectMapper();
		$objectMapper->setConnection($conn);
		$object =& $objectMapper->save($object);
		
		// Save attributes
		$objectAttributeMapper = new ObjectAttributeMapper();
		$objectAttributeMapper->setConnection($conn);
		$objectAttributeArray =& $object->getAttributes();
		foreach($objectAttributeArray as $objectAttribute)
		{
			$objectAttribute->setObjectID($object->getId());
			$objectAttributeMapper->save($objectAttribute);
		}
		
		// Save folders
		$objectFolderMapper = new ObjectFolderMapper();
		$objectFolderMapper->setConnection($conn);
		$objectFolderArray =& $object->getObjectFolders();
		foreach ($objectFolderArray as $objectFolder)
		{
			$objectFolder->setObjectID($object->getId());
			$objectFolderMapper->save($objectFolder);
		}
		
		// Save relationships
		$objectRelationMapper = new ObjectRelationMapper();
		$objectRelationMapper->setConnection($conn);
		$objectRelationArray =& $object->getObjectRelations();
		foreach ($objectRelationArray as $objectRelation)
		{
			/* @var $objectRelation ObjectRelation */
			$objectRelation->setParentID($object->getId());
			$objectRelationMapper->save($objectRelation);
		}
		
		// Close transaction
		$conn->completeTrans();
		$icfDatabase->dbClose($conn);
		
		return $object;
	}

	/**
	 * Generates the full Text index for an object
	 * @param $object Object - Object instance
	 * @return String - string containing full text index
	 * @access private
	 */
	function generateFullTextIndex($object)
	{
		// Take this object attributes
		foreach($object->getAttributes() as $objectAttribute)
		{
			$value = $objectAttribute->getValue();
			// Filter HTML quotes issued by TinyMCE
			$value = str_replace("&ntilde;", "ñ", $value);
			$value = str_replace("&Ntilde;", "Ñ", $value);
			$fullTextIndex .= $value . "|";
		}

		// Take relationed objects attributes
		if (is_null($object->getObjectRelations()) == false)
		{
			foreach($object->getObjectRelations() as $objectRelation)
			{
				/* @var $objectRelation ObjectRelation */
				$childObject = $objectRelation->getChild();
				/* @var $childObject Object */
				// Erase relations of this object (only one level must be serialized)
				$objectRelationArray = array();
				$childObject->setObjectRelations($objectRelationArray);
				// Get serialization
				$fullTextIndex .= $this->generateFullTextIndex($childObject);
			}
		}
						
		return $fullTextIndex;
	}
	
	/**
	 * Updates an object
	 * @param $object Object to update, must have its ID set
	 */
	function update(&$object)
	{
		// Fill object Fulltext index
		$fullTextIndex = $this->generateFullTextIndex($object);
		$object->setFullTextIndex($fullTextIndex);				

		// Coordinate transaction
		$icfDatabase = new IcfDatabase();		
		$conn = $icfDatabase->dbOpen();
		$conn->StartTrans();
			
		// Update object
		$objectMapper = new ObjectMapper();
		$objectMapper->setConnection($conn);
		$objectMapper->update($object);

		// Recreate attributes
		$objectAttributeMapper = new ObjectAttributeMapper();
		$objectAttributeMapper->setConnection($conn);
		
		$objectAttributeMapper->deleteByObjectId($object->getId());
		$objectAttributeArray =& $object->getAttributes();
		foreach($objectAttributeArray as $objectAttribute)
		{
			$objectAttribute->setObjectID($object->getId());
			$objectAttributeMapper->save($objectAttribute);
		}
		
		// Recreate folders
		$objectFolderMapper = new ObjectFolderMapper();
		$objectFolderMapper->setConnection($conn);
		
		$objectFolderMapper->deleteByObjectId($object->getId());
		$objectFolderArray =& $object->getObjectFolders();
		foreach ($objectFolderArray as $objectFolder)
		{
			$objectFolder->setObjectID($object->getId());
			$objectFolderMapper->save($objectFolder);
		}

		// Recreate relationships
		$objectRelationMapper = new ObjectRelationMapper();
		$objectRelationMapper->setConnection($conn);
		
		$objectRelationMapper->deleteByParentId($object->getId());
		$objectRelationArray =& $object->getObjectRelations();
		foreach ($objectRelationArray as $objectRelation)
		{
			/* @var $objectRelation ObjectRelation */
			$objectRelation->setParentID($object->getId());
			$objectRelationMapper->save($objectRelation);
		}
		
		// Close transaction
		$conn->completeTrans();
		$icfDatabase->dbClose($conn);
	}	
}

?>