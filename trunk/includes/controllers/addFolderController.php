<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

require_once "controller.php";
require_once "icfHorizontal.php";
require_once "service/folderService.php";
require_once "mappers/folderMapper.php";

/**
 * Assumes the controller role of the AddFolder features
 */
class AddFolderController extends Controller
{  
	/**
	 * Constructs  AddFolderController
	 *
	 * @param &icfTemplating Template method implementation
	 */
	function AddFolderController()
	{	
		$this->Controller();
		$this->setPageTitle($this->text["add"]);
	}
	
	/**
	 * Constructs a new ControllerData array, with the data transferred between controller and view
	 * @return Array - controller data
	 */
	function newControllerData()
	{
		$controllerData["folderIdHidden"] = null;
		$controllerData["parentIdHidden"] = null;
		$controllerData["refererHidden"] = null;
		$controllerData["titleText"] = null;
		$controllerData["shortDescriptionText"] = null;
		$controllerData["longDescriptionTextarea"] = null;
		$controllerData["parentText"] = null;
		$controllerData["parentIdHidden"] = null;
		$controllerData["positionText"] = 1;
		$controllerData["refererHidden"] = null;
		$controllerData["classesIdSelect"] = array();
		$controllerData["classes"] = null;
				
		return $controllerData;
	}
	
	/**
	 * Shows the default add view
	 */
	function showView()
	{
		$this->showAddFolderView();
	}
	
	/**
	 * Shows the addFolder view
	 */
	function showAddFolderView()	
	{		
		$controllerData =& $this->collectControlerData();
		assert($controllerData["parentIdHidden"] != null);
		
		$folderMapper = new FolderMapper();
		$parentFolder = $folderMapper->get($controllerData["parentIdHidden"]);
		/* @var $parentFolder Folder */
		$controllerData["parentText"] = $parentFolder->getTitle();
		$controllerData["positionText"] = $parentFolder->getNextFolderPosition();	
		
		$this->displayAddFolderView();
	}
	
	function displayAddFolderView()
	{
		$this->setAddFolderViewToolbar();
				
		$controllerData =& $this->getControllerData();
		
		// Classes to link
		$baseClassMapper = new BaseClassMapper();
		$classes =& $baseClassMapper->getAll();
		$controllerData["classes"] =& $classes;
		
		$this->tpl->assign("method", "addFolder");
		$this->displayView("addFolder.tpl.php");
	}
	
	/**
	 * Shows the addFolder view
	 */
	function showUpdateFolderView()
	{
		$controllerData =& $this->collectControlerData();
		assert($controllerData["folderIdHidden"] != null);
		
		$folderMapper = new FolderMapper();
		$folder = $folderMapper->get($controllerData["folderIdHidden"]);
		$parentFolder = $folder->getParent();
		
		/* @var $folder Folder */
		
		$controllerData["parentIdHidden"] = $parentFolder->getId();
		$controllerData["titleText"] = $folder->getTitle();
		$controllerData["shortDescriptionText"] = $folder->getShortDescription();
		$controllerData["longDescriptionTextarea"] = $folder->getLongDescription();
		$controllerData["parentText"] = $parentFolder->getTitle();
		$controllerData["parentIdHidden"] = $folder->getParentID();
		$controllerData["positionText"] = $folder->getPosition();
						
		// String array for selected classes
		$classesIdSelect = array();
		$folderClasses = $folder->getFolderClasses();
		
		foreach($folderClasses as $folderClass)
		{
			/* @var $folderClass FolderClass */
			array_push($classesIdSelect, $folderClass->getClassID());
		}
		$controllerData["classesIdSelect"] = $classesIdSelect;
		
		$this->displayUpdateFolderView();
	}
	
	function displayUpdateFolderView()
	{
		$controllerData =& $this->getControllerData();
		
		// Classes to link
		$baseClassMapper = new BaseClassMapper();
		$classes =& $baseClassMapper->getAll();
		$controllerData["classes"] =& $classes;
		
		$this->setUpdateFolderViewToolbar();
		$this->tpl->assign("method", "updateFolder");
		$this->displayView("addFolder.tpl.php");
	}
	
	/**
	 * Executed when the user canceled the add new / update folder
	 */
	function addCancel()
	{
		$this->redirectToReferer();
	}
		
	/**
	 * Creates a new folder
	 * @param $parentFolderId the parent folder id
	 */
	function addFolder()
	{	
		$controllerData =& $this->collectControlerData();
		
		$title = $controllerData["titleText"];
		$parentId = $controllerData["parentIdHidden"];
		$position = $controllerData["positionText"];
		$shortDescription = $controllerData["shortDescriptionText"];
		$longDescription = $controllerData["longDescriptionTextarea"];
		$classesIdArray = $controllerData["classesIdSelect"];
		
		if ($title == null || $title == "") $this->addErrorMessage("title");
		if ($shortDescription == null || $shortDescription == "") $this->addErrorMessage("shortdescription");
		if ($longDescription == null || $longDescription == "") $this->addErrorMessage("longdescription");
		if (count($classesIdArray) <= 0) $this->addErrorMessage("classes");
		
		if (count($this->controllerMessageArray) > 0)
		{		
			$this->displayAddFolderView();
			return;
		}
		
		$folderMapper = new FolderMapper();
		$parentFolder = $folderMapper->get($parentId);
		/* @var $parentFolder Folder */
		
		$folder = new Folder();
		$folder->setTitle($title);
		$folder->setParentId($parentId);
		$folder->setPosition($position);
		$folder->setShortDescription($shortDescription);
		$folder->setLongDescription($longDescription);
		
		$folderClassesArray = array();
		foreach($classesIdArray as $classId)
		{
			$folderClass = new FolderClass();
			$folderClass->setClassID($classId);
			$folderClass->setPosition(1);
			$folderClass->setIsDefault(0);
			array_push($folderClassesArray, $folderClass);
		}
		$folder->setFolderClasses($folderClassesArray);
		
		$folderService = new FolderService();
		$folderService->save($folder);
		
		$this->redirectToReferer();
	}
	
	/**
	 * Adds an error message to this controller
	 * @param $field String - codename of this field in the text array
	 */
	function addErrorMessage($textFieldName)
	{
		$field = $this->text[$textFieldName];
		$message = str_replace("field", $field, $this->text["required"]);
		$controllerMessage = new ControllerMessage($message, ControllerMessage::getErrorType());
		
		array_push($this->controllerMessageArray, $controllerMessage);
	}
	
	/**
	 * Updates a new folder
	 */
	function updateFolder()
	{	
		$controllerData =& $this->collectControlerData();
		
		$folderId = $controllerData["folderIdHidden"];
		$title = $controllerData["titleText"];
		$parentId = $controllerData["parentIdHidden"];
		$position = $controllerData["positionText"];
		$shortDescription = $controllerData["shortDescriptionText"];
		$longDescription = $controllerData["longDescriptionTextarea"];
		$classesIdArray = $controllerData["classesIdSelect"];
		
		if ($title == null || $title == "") $this->addErrorMessage("title");
		if ($shortDescription == null || $shortDescription == "") $this->addErrorMessage("shortdescription");
		if ($longDescription == null || $longDescription == "") $this->addErrorMessage("longdescription");
		if (count($classesIdArray) <= 0) $this->addErrorMessage("classes");
		
		if (count($this->controllerMessageArray) > 0)
		{	
			$this->displayUpdateFolderView("addFolder.tpl.php");
			return;
		}
		
		$folder = new Folder();
		$folder->setId($folderId);
		$folder->setTitle($title);
		$folder->setParentId($parentId);
		$folder->setPosition($position);
		$folder->setShortDescription($shortDescription);
		$folder->setLongDescription($longDescription);
		
		$folderClassesArray = array();
		foreach($classesIdArray as $classId)
		{
			$folderClass = new FolderClass();
			$folderClass->setClassID($classId);
			$folderClass->setPosition(1);
			$folderClass->setIsDefault(0);
			array_push($folderClassesArray, $folderClass);
		}
		$folder->setFolderClasses($folderClassesArray);
		
		$folderService = new FolderService();
		$folderService->update($folder);
		
		$this->redirectToReferer();
	}	
	
	/**
	 * Sets the toolbar for the view
	 */
	function setAddFolderViewToolbar()
	{
		$toolbar =& $this->getToolbar();			
				
		$toolbarItem = new icfToolbarItem();
		$toolbarItem->setName("cancel");
		$toolbarItem->setTitle($this->text["cancel"]);
		$toolbarItem->setUrl("#");
		$toolbarItem->setOnclick("cancelButton_onClick()");
		$toolbarItem->setImage("/images/cancel.png");
		$toolbarItem->setImage2("/images/cancel_f2.png");
		$toolbar->addToolbarItem($toolbarItem);
		
		$toolbarItem = new icfToolbarItem();		
		$toolbarItem->setName("save");
		$toolbarItem->setTitle($this->text["save"]);
		$toolbarItem->setUrl("#");
		$toolbarItem->setOnclick("saveButton_onClick()");
		$toolbarItem->setImage("/images/save.png");
		$toolbarItem->setImage2("/images/save_f2.png");
		$toolbar->addToolbarItem($toolbarItem);
		
		// Set toolbar
		$this->setToolbar($toolbar);		
	}
	
	/**
	 * Sets the toolbar for the view
	 */
	function setUpdateFolderViewToolbar()
	{
		$toolbar =& $this->getToolbar();			
				
		$toolbarItem = new icfToolbarItem();
		$toolbarItem->setName("cancel");
		$toolbarItem->setTitle($this->text["cancel"]);
		$toolbarItem->setUrl("#");
		$toolbarItem->setOnclick("cancelButton_onClick()");
		$toolbarItem->setImage("/images/cancel.png");
		$toolbarItem->setImage2("/images/cancel_f2.png");
		$toolbar->addToolbarItem($toolbarItem);
		
		$toolbarItem = new icfToolbarItem();		
		$toolbarItem->setName("update");
		$toolbarItem->setTitle($this->text["save"]);
		$toolbarItem->setUrl("#");
		$toolbarItem->setOnclick("updateButton_onClick()");
		$toolbarItem->setImage("/images/save.png");
		$toolbarItem->setImage2("/images/save_f2.png");
		$toolbar->addToolbarItem($toolbarItem);
		
		// Set toolbar
		$this->setToolbar($toolbar);		
	}
}
?>