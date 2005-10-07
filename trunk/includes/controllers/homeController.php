<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

require_once "icfHorizontal.php";
require_once "mappers/objectMapper.php";
require_once "mappers/baseClassMapper.php";
require_once "mappers/folderMapper.php";

/**
 * Assumes the controller role of the home feature
 */
class HomeController
{
	var $controllerData;
  var $tpl;
  var $text;
    
	/**
	 * Constructs HomeController, executing the method given as parameter
	 *
	 * @param $method Name of the method to execute
	 * @param &tpl Template method implementation
	 */
	function HomeController($method = null, $icfTemplating)
	{		
		$this->tpl = $icfTemplating->getTpl();
		$this->text = $icfTemplating->getText();
		
		// Title of the page
		$this->tpl->assign('pageTitle', $this->text['home']);
		
		// Pending contents
		$objectMapper = new ObjectMapper();
		$objects = $objectMapper->findPending();
		$objectsCount = count($objects);
		$this->controllerData["pending"] = $objectsCount;
		
		// Allowed classes to add
		$session = new Session();
		
		$baseClassMapper = new BaseClassMapper();
		$classArray = $baseClassMapper->findByPermission(Action::ADD_OBJECTS_ACTION(), $session->getSessionUser());
		$this->tpl->assign("classArray", $classArray);
			
		switch ($method)
		{			
			default:
				$this->show_view();
		}
	}
	
	/**
	 * Shows the home view
	 */
	function show_view()
	{
		$this->tpl->assign("controllerData", $this->controllerData);
		$this->tpl->display("home.tpl.php");
	}
}


?>