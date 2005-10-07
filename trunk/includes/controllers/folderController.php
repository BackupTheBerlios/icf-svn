<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

require_once "controller.php";
require_once "icfHorizontal.php";
require_once "service/folderService.php";
require_once "mappers/objectMapper.php";
require_once "mappers/baseClassMapper.php";
require_once "mappers/folderMapper.php";

/**
 * Assumes the controller role of the Foldering features
 */
class FolderController extends Controller
{  
	/**
	 * Constructs FolderController, executing the method given as parameter
	 *
	 * @param $method Name of the method to execute
	 * @param &tpl Template method implementation
	 */
	function FolderController()
	{	
		$this->Controller();
		$this->setPageTitle($this->text["folders"]);
	}
	
	/**
	 * Constructs a null ControllerData
	 * @return Array
	 */
	function newControllerData()
	{
		return array();
	}
	
	/**
	 * Gets the folder issued by the request
	 * @return Folder - folder issued by the request
	 */
	function& getFolder()
	{
		// Get appropiate folders
		$folderId = $_REQUEST["folderId"];
		assert($folderId != null);
		
		$folderMapper = new FolderMapper();
		return $folderMapper->get($folderId);
	}
		
	/**
	 * Deletes an existing folder
	 */
	function deleteFolders()
	{
		$folderIdString = $_REQUEST["selectedFoldersHidden"];
		assert($folderIdString != null);
		
		$folderIdArray = split(",", $folderIdString);		
		$title = FolderService::delete($folderIdArray);
		
		if ($title != null)
			array_push($this->controllerMessageArray, new ControllerMessage($this->text["cannotbedeleted"] . ": " . $title, ControllerMessage::getErrorType()));
		
		$this->showView();
	}
	
	/**
	 * Shows the view for a folder
	 */
	function showFolder()
	{
		$folderId = $_REQUEST["childFolderIdHidden"];
		assert($folderId != null);
		
		$folderMapper = new FolderMapper();
		$folder = $folderMapper->get($folderId);
				
		$this->showView($folder);
	}

	/**
	 * Moves a given folder Up
	 */
	function moveFolderUp()
	{
		$folderId = $_REQUEST["childFolderIdHidden"];
		assert($folderId != null);
		
		$folderService = new FolderService();
		$folderService->moveFolderUp($folderId);
		
		$this->showView();
	}
	
	/**
	 * Moves a given folder down
	 */
	function moveFolderDown()
	{
		$folderId = $_REQUEST["childFolderIdHidden"];
		
		$folderService = new FolderService();
		$folderService->moveFolderDown($folderId);
		
		$this->showView();
	}

	function moveObjectFolderUp()
	{
		$objectFolderId = $_REQUEST["childObjectFolderIdHidden"];
		assert($objectFolderId != null);
		
		$folderService = new FolderService();
		$folderService->moveObjectFolderUp($objectFolderId);
		
		$this->showView();
	}
	
	function moveObjectFolderDown()
	{
		$objectFolderId = $_REQUEST["childObjectFolderIdHidden"];
		assert($objectFolderId != null);
		
		$folderService = new FolderService();
		$folderService->moveObjectFolderDown($objectFolderId);
		
		$this->showView();
	}
	
	/**
	 * Shows the folder view
	 * @param $folder Folder - optional, if the folder is received, it is displayed. If not, the request parameter is used
	 */
	function showView($folder = null)
	{
		if ($folder == null) $folder = $this->getFolder();		
		// Set folder
		$this->tpl->assign("folder", $folder);
		
		$this->setFolderViewToolbar();
				
		// Create pathview		
		$pathView = $folder->getTitle();
		$parentFolder = $folder->getParent();
		while ($parentFolder != null)
		{
			$pathView = "<a href='#' onclick='showFolder(" . $parentFolder->getId() . ")'>" . $parentFolder->getTitle() . "</a> &gt; " . $pathView;
			$parentFolder = $parentFolder->getParent();
		}
		$this->tpl->assign("pathView", $pathView);
		
		// Special objects that can be added to this folder
		$specialArray = array();
		// TODO: FOLDERS_ACTION: if ($folder->canDoAction(null, Action::ADD_FOLDERS_ACTION()))
			$specialArray["folder"] = $this->text["folder"];		
		$this->tpl->assign("specialArray", $specialArray);
			
		// Classes to add
		$classesArray = array();
		foreach($folder->getFolderClasses() as $folderClass)
		{
			if ($folderClass->canDoAction(null, Action::ADD_OBJECTS_ACTION()) == false) continue;
			$class = $folderClass->getClass();
			$classesArray[$class->getId()] = $class->getTitle();
		}
		$this->tpl->assign("classesArray", $classesArray);
		
		$this->displayView("folder.tpl.php");
	}
	
	/**
	 * Shows the "not enough permissions" view
	 */
	function showNotEnoughPermissionsView()
	{
		$controllerMessage = new ControllerMessage($this->text["notenoughpermissions"], ControllerMessage::getErrorType());
		array_push($this->controllerMessageArray, $controllerMessage);
		
		$this->displayView("folder.tpl.php");
	}
	
	/**
	 * Sets the toolbar for the view
	 */
	function setFolderViewToolbar()
	{
		$toolbar =& $this->getToolbar();
				
		$toolbarItem = new icfToolbarItem();		
		$toolbarItem->setName("delete");
		$toolbarItem->setTitle($this->text["delete"]);
		$toolbarItem->setUrl("#");
		$toolbarItem->setOnclick("deleteButton_onClick()");
		$toolbarItem->setImage("/images/delete.png");
		$toolbarItem->setImage2("/images/delete_f2.png");
		$toolbar->addToolbarItem($toolbarItem);
				
		$toolbarItem = new icfToolbarItem();
		$toolbarItem->setName("edit");
		$toolbarItem->setTitle($this->text["edit"]);
		$toolbarItem->setUrl("#");
		$toolbarItem->setOnclick("editButton_onClick()");
		$toolbarItem->setImage("/images/edit.png");
		$toolbarItem->setImage2("/images/edit_f2.png");
		$toolbar->addToolbarItem($toolbarItem);
		
		// Set toolbar
		$this->setToolbar($toolbar);		
	}
	
}
?>