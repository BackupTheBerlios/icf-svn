<?php

require_once "icfHorizontal.php";
require_once "mappers/folderClassMapper.php";
require_once "mappers/objectMapper.php";
require_once "classes/object.php";
require_once "JSON.php";

// Bust cache in the head
header('Content-type: plain/text');
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// always modified
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0


if($_GET['action'] == 'get_folders') // Gets all folders for this class ID
{
	$id = $_GET['id'];

	if (is_numeric($id))
	{
		$folderClassMapper = new FolderClassMapper();
		$objectMapper = new ObjectMapper();

		// Gets all folders for selected class for the folder combo
		$foldersClasses = $folderClassMapper->findByClassId($id);

		$folders = array();
		$titles = array();
		foreach ($foldersClasses as $folderClass)
		{
			$folder = $folderClass->getFolder();
			$text = $folder->getId() . "|" . $folder->getPathway();
			array_push($folders, $text); 
			array_push($titles, $folder->getPathway()); 
		}
		
		$folders = $objectMapper->quicksortObjectByTitle($folders, $titles);
		$json = new JSON();

		echo $json->encode($folders);
	}
}

if($_GET['action'] == 'hasPublishingPermissions') //returns true if current user has permission to publish/unpublish for this object ID
{
	$id = $_GET['id'];

	if (is_numeric($id))
	{
		$objectMapper = new ObjectMapper();
		$object = $objectMapper->get($id);
		$rv = $object->canDoAction(null, Action::PUBLISH_OBJECTS_ACTION());

		$json = new JSON();
		echo $json->encode($rv);
	}
}

?>


