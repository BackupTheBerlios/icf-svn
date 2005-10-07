<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

require_once "icfHorizontal.php";
require_once "controller.php";
require_once "mappers/baseClassMapper.php";
require_once "service/objectService.php";
require_once "service/objectServiceFactory.php";

/**
 * Assumes the controller role of the home feature
 *
 * Parameters:
 * "classId": id of the class whose objects are to be listed
 * "objectId": id of the object, if applies
 * "string": string to be searched, if applies
 * "search": type of search ("simple" or "fulltext"), if applies.
 *
 * Available methods:
 * "showView": shows the default view
 * "search": searches the objects for the class. Uses the following parameters:
 * "publish": publishes an object
 * "unpublish": unpublishes an object
 * "add": adds a new object of a given class
 * "edit": edits a given object
 * "delete": deletes a given instance
 */
class ListController extends Controller
{     
	/**
	 * Constructs ListController
	 */
	function ListController()
	{	
		$this->Controller();
				
		// Title of the page		
		$this->setPageTitle($this->text["pageTitle"]);
						
		// Collect sent data
		$this->collectControlerData();
		
		$baseClassMapper = new BaseClassMapper();
		
		// Gets all classes for the class combo
		$classes = $baseClassMapper->getAll();
		$this->controllerData["classes"] =& $classes;
		
		// Set a nul object array for the list of objects
		$this->controllerData["objects"] = array();
				
		// Add items to toolbar
		$this->constructToolbar();
	}
	
	/**
	 * Constructs the ControllerData needed for this Controller
	 * @return ControllerData - controllerData object
	 */
	function& newControllerData()
	{
		$controllerData["classIdSelect"] = "";
		$controllerData["titleText"] = "";
		$controllerData["searchTypeSelect"] = "";
		
		$controllerData["objects"] = array();
		$controllerData["objectId"] = "";
		
		$controllerData["classes"] = array();
		
		return $controllerData;
	}
	
	/**
	 * Gets the class sent in controllerData
	 * @return BaseClass - baseClass object
	 */
	function getClass()
	{
		$controllerData =& $this->getControllerData();
		$classId = $controllerData["classIdSelect"];
		
		$baseClassMapper = new BaseClassMapper();
		$class = $baseClassMapper->get($classId);
		
		return $class;
	}
	
	/**
	 * Action that search the objects
	 * @param $classId id of the class whose objects are being searched
	 */
	function showView()
	{
		$controllerData =& $this->getControllerData();		
		$class = $this->getClass();
		
		// Business rules for search string
		$title = trim($controllerData["titleText"]);		
		if ($title == null || $title == "") $title = "%";
		$pos = strpos($title, "%");
		if (is_int($pos) == false) $title = "%" . $title . "%";
		$controllerData["titleText"] = $title;
		
		$fulltextSearch = false;
		if ($controllerData["searchTypeSelect"] == "fulltext") $fulltextSearch = true;
		
		$objectService = ObjectServiceFactory::newInstance($class);
		$objectArray = $objectService->findByText($controllerData["classIdSelect"], $title, $fulltextSearch);
		
		// Set result
		$this->controllerData["objects"] = $objectArray;
		
		// Show view
		$this->displayView("list.tpl.php");
	}
		
	/**
	 * Publishes a content
	 * @param $objectId int - content id
	 */
	function publish()	
	{		
		$controllerData =& $this->getControllerData();
		$objectId = $controllerData["objectId"];
		assert($objectId != null);
		
		$class = $this->getClass();
		
		$objectService = ObjectServiceFactory::newInstance($class);
		$objectService->publish($objectId);
		
		// Execute search
		$this->showView();
	}
	
	/**
	 * Unpublishes a content
	 */
	function unpublish()
	{
		$controllerData =& $this->getControllerData();
		$objectId = $controllerData["objectId"];
		assert($objectId != null);
		
		$class = $this->getClass();
		
		$objectService = ObjectServiceFactory::newInstance($class);
		$objectService->unpublish($objectId);
		
		// Execute search
		$this->showView();
	}
	
	/**
	 * Adds a new instance to a class
	 */
	function add()
	{
		$controllerData =& $this->getControllerData();
		$classId = $controllerData["classIdSelect"];
		assert($classId != null);
		
		$this->redirect("add.php?classId=" . $classId . "&refererHidden=list.php_classIdSelect-" . $classId);
	}
	
	/**
	 * Edits a new instance to a class
	 */
	function edit()
	{
		$controllerData =& $this->getControllerData();
		$objectId = $controllerData["objectId"];
		$classId = $controllerData["classIdSelect"];
		assert($objectId != null);
		assert($classId != null);
		
		$this->redirect("add.php?method=showUpdateView&objectId=" . $objectId . "&refererHidden=list.php_classIdSelect-" . $classId);
	}
	
	/**
	 * Deletes a content
	 */
	function delete()
	{
		$controllerData =& $this->getControllerData();
		$objectId = $controllerData["objectId"];
		assert($objectId != null);
		
		$class = $this->getClass();
		
		$objectService = ObjectServiceFactory::newInstance($class);
		$objectService->delete($objectId);
		
		// Execute search
		$this->showView();
	}
	
	/**
	 * Adds the necesary items to the toolbar
	 */
	function constructToolbar()
	{
		$toolbar =& $this->getToolbar();		
		
		$ti = new icfToolbarItem();
		$ti->setName("delete");
		$ti->setTitle($this->text["delete"]);
		$ti->setUrl("#");
		$ti->setOnclick("deleteButton_onClick()");
		$ti->setImage("/images/delete.png");
		$ti->setImage2("/images/delete_f2.png");
		$toolbar->addToolbarItem($ti);
		
		$ti = new icfToolbarItem();
		$ti->setName("edit");
		$ti->setTitle($this->text["edit"]);
		$ti->setUrl("#");
		$ti->setOnclick("editButton_onClick()");
		$ti->setImage("/images/edit.png");
		$ti->setImage2("/images/edit_f2.png");
		$toolbar->addToolbarItem($ti);
		
		$ti = new icfToolbarItem();
		$ti->setName("add");
		$ti->setTitle($this->text["add"]);
		$ti->setUrl("#");
		$ti->setOnclick("addButton_onClick()");
		$ti->setImage("/images/new.png");
		$ti->setImage2("/images/new_f2.png");
		$toolbar->addToolbarItem($ti);
		
		$ti = new icfToolbarItem();
		$ti->setName("publish");
		$ti->setTitle($this->text["publish"]);
		$ti->setUrl("#");
		$ti->setOnclick("publishButton_onClick()");
		$ti->setImage("/images/publish.png");
		$ti->setImage2("/images/publish_f2.png");
		$toolbar->addToolbarItem($ti);
		
		$ti = new icfToolbarItem();
		$ti->setName("unpublish");
		$ti->setTitle($this->text["unpublish"]);
		$ti->setUrl("#");
		$ti->setOnclick("unpublishButton_onClick()");
		$ti->setImage("/images/unpublish.png");
		$ti->setImage2("/images/unpublish_f2.png");
		$toolbar->addToolbarItem($ti);

		// Set toolbar
		$this->setToolbar($toolbar);		
	}
}


?>