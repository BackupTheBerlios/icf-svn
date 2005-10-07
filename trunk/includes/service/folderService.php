<?php

require_once "icfDatabase.php";
require_once "mappers/folderMapper.php";

/**
 * Service object for the Folder class, gives access to the GUI programmer to Business Logic
 * coordinating transactions.
 */
class FolderService
{	
	/**
	 * Saves the folder
	 * @param $folder Folder - folder object
	 * @return Folder - new Folder Object
	 */
	function& save($folder)
	{
		$folderMapper = new FolderMapper();
				
		// Coordinate transaction
		$icfDatabase = new IcfDatabase();		
		$conn =& $icfDatabase->dbOpen();
		$conn->StartTrans();
		
		$folderMapper->setConnection($conn);
		// Save folder
		$folder =& $folderMapper->save($folder);
				
		// Save FolderClasses
		$folderClassMapper = new FolderClassMapper();
		$folderClassMapper->setConnection($conn);
		foreach($folder->getFolderClasses() as $folderClass)
		{
			/* @var $folderClass FolderClass */
			$folderClass->setFolderID($folder->getId());			
			$folderClassMapper->save($folderClass);
		}
		
		// Close transaction
		$conn->completeTrans();
		$icfDatabase->dbClose($conn);
		
		return $folder;
	}
	
	/**
	 * Saves the folder
	 * @param $folder Folder - folder object
	 */
	function update($folder)
	{
		$folderMapper = new FolderMapper();
				
		// Coordinate transaction
		$icfDatabase = new IcfDatabase();		
		$conn =& $icfDatabase->dbOpen();
		$conn->StartTrans();	
		
		$folderMapper->setConnection($conn);
		// Update folder
		$folderMapper->update($folder);

		$folderClassMapper = new FolderClassMapper();
		$folderClassMapper->setConnection($conn);
			
		// Delete previous FolderClasses
		$folderClassMapper->deleteByFolderId($folder->getId());
		
		// Save FolderClasses	
		foreach($folder->getFolderClasses() as $folderClass)
		{
			/* @var $folderClass FolderClass */
			$folderClass->setFolderID($folder->getId());
			$folderClassMapper->save($folderClass);
		}
		
		// Close transaction
		$conn->completeTrans();
		$icfDatabase->dbClose($conn);
	}
	
	/**
	 * Deletes an array of folders
	 * @static
	 * @param $idArray array - array of folder ids
	 * @return null if the transaction completed successfully, a string if it failed because the folder has associated objects. The string contains the folder that caused the problem.
	 */	
	function delete($idArray)
	{	
		$folderMapper = new FolderMapper();
				
		// Check if folder has associated class objects						
		foreach($idArray as $id)
		{
			$folder = $folderMapper->get($id);
			
			/* @var $folder Folder */
			
			if (count($folder->getObjectFolders()) > 0)
				return $folder->getTitle();		
		}
		
		// Coordinate transaction
		$icfDatabase = new IcfDatabase();		
		$conn = $icfDatabase->dbOpen();
		$conn->StartTrans();
		
		$folderMapper->setConnection($conn);

		foreach($idArray as $id)
			$folderMapper->delete($id);
				
		// Close transaction
		$conn->completeTrans();
		$icfDatabase->dbClose($conn);
		
		return null;
	}
	
	/**
	 * Given a folder, moves it up in the position sequence
	 * @param $folderId int - folder id
	 */
	function moveFolderUp($folderId)
	{	
		$this->switchFolders($folderId, -1);	
	}
	
	/**
	 * Given a folder, moves it down in the position sequence
	 * @param $folderId int - folder id
	 */
	function moveFolderDown($folderId)
	{
		$this->switchFolders($folderId, 1);
	}

	/**
	 * Switches two folders position.
	 * @param $folderId int - the folder that is going to switch positions with one that is next to it
	 * @param $incremental int - a relative position to the first folder. The folder found in that position
	 * switches position with the first folder. It'll be tippically -1 (folderUp) or 1 (folderDown)
	 * @access private
	 */
	function switchFolders($folderId, $incremental)
	{
		// Get folder
		$folderMapper = new FolderMapper();
		$folder = $folderMapper->get($folderId);
		
		// Get its parent
		$parentFolder = $folder->getParent();
		
		// Get its children
		$folderArray = $parentFolder->getChildren();
		
		// Lookup the folder
		$position = -1000;
		for($i = 0; $i < count($folderArray); $i++)
		{
			$arrayFolder = $folderArray[$i];
			if ($folder->getId() == $arrayFolder->getId())
			{
				$position = $i;
				break;
			}
		}
		assert($position != -1000);
		$position = $position + $incremental;		
		
		$otherFolder = $folderArray[$position];
		
		// Switch positions
		$folder->switchPositionWith($otherFolder);
		
		// Coordinate transaction
		$icfDatabase = new IcfDatabase();		
		$conn =& $icfDatabase->dbOpen();
		$conn->StartTrans();
		
		$folderMapper = new FolderMapper();
		$folderMapper->setConnection($conn);
		
		// Save objects
		$folderMapper->update($folder);
		$folderMapper->update($otherFolder);
		
		// Close transaction
		$conn->completeTrans();
		$icfDatabase->dbClose($conn);
	}
	
	/**
	 * Given a ObjectFolder, moves it up in the position sequence
	 * @param $objectFolderId int - objectFolder id
	 */
	function moveObjectFolderUp($objectFolderId)
	{		
		$this->switchObjectFolders($objectFolderId, -1);
	}

	/**
	 * Given a ObjectFolder, moves it down in the position sequence
	 * @param $objectFolderId int - objectFolder id
	 */
	function moveObjectFolderDown($objectFolderId)
	{	
		$this->switchObjectFolders($objectFolderId, 1);	
	}
	
	/**
	 * Switches two folders position.
	 * @param $objectFolderId int - the ObjectFolder that is going to switch positions with one that is next to it
	 * @param $incremental int - a relative position to the first ObjectFolder. The folder found in that position
	 * switches position with the first folder. It'll be tippically -1 (folderUp) or 1 (folderDown)
	 * @access private
	 */
	function switchObjectFolders($objectFolderId, $incremental)
	{
		// Get folder
		$objectFolderMapper = new ObjectFolderMapper();
		$objectFolder = $objectFolderMapper->get($objectFolderId);
		
		/* @var $objectFolder ObjectFolder */
		/* @var $folder Folder */
		
		// Get container folder
		$folder = $objectFolder->getFolder();		
		// Get its children
		$objectFolderArray =& $folder->getObjectFolders();
		
		// Lookup the folder
		$position = -1000;
		for($i = 0; $i < count($objectFolderArray); $i++)
		{
			$arrayObjectFolder = $objectFolderArray[$i];
			if ($objectFolder->getId() == $arrayObjectFolder->getId())
			{
				$position = $i;
				break;
			}
		}
		assert($position != -1000);
		$position = $position + $incremental;
		$otherObjectFolder = $objectFolderArray[$position];
		
		// Switch positions
		$objectFolder->switchPositionWith($otherObjectFolder);
		
		// Coordinate transaction
		$icfDatabase = new IcfDatabase();		
		$conn =& $icfDatabase->dbOpen();
		$conn->StartTrans();
		
		$objectFolderMapper = new ObjectFolderMapper();
		$objectFolderMapper->setConnection($conn);
		
		// Save objects
		$objectFolderMapper->update($objectFolder);
		$objectFolderMapper->update($otherObjectFolder);
		
		// Close transaction
		$conn->completeTrans();
		$icfDatabase->dbClose($conn);
	}	
}
?>