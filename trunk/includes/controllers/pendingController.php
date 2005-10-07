<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

require_once "icfHorizontal.php";
require_once "service/objectService.php";
require_once "mappers/objectMapper.php";

/**
 * Assumes the controller role of the home feature
 */
class PendingController
{
	var $controllerData;
  var $tpl;
  var $icfTemplating;
  var $text;
    
	/**
	 * Constructs PendingController, executing the method given as parameter
	 *
	 * @param $method string - name of the method to execute
	 * @param &tpl IcfTemplating - template method implementation
	 */
	function PendingController($method = null, $icfTemplating)
	{		
		$this->icfTemplating =& $icfTemplating;
		$this->tpl =& $icfTemplating->getTpl();
		$this->text =& $icfTemplating->getText();
		
		switch ($method)
		{			
			case "publish":
			{
				$selectedContents = $_REQUEST["selectedContents"];				
				$this->publish($selectedContents);
				break;
			}
			
			default:
				$this->show_view();
		}
	}
	
	/**
	 * Shows the home view
	 */
	function show_view()
	{	
		// Sets the title
		$this->tpl->assign('pageTitle', $this->text['pending']);

		// Add items to toolbar
		$toolbar =& $this->icfTemplating->getToolbar();		
		
		$ti = new icfToolbarItem();
		$ti->setName("publishButton");
		$ti->setTitle($this->text["publish"]);
		$ti->setUrl("#");
		$ti->setImage("/images/publish.png");
		$ti->setImage2("/images/publish_f2.png");
		$ti->setOnclick("publishButton_onClick()");
		$toolbar->addToolbarItem($ti);
		
		// Set toolbar
		$this->icfTemplating->setToolbar($toolbar);
		
		// Get the pending objects
		$objectMapper = new ObjectMapper();
		$objects = $objectMapper->findPending();
				
		$this->controllerData["objects"] = $objects;		
		$this->tpl->assign("controllerData", $this->controllerData);
		$this->tpl->display("pending.tpl.php");
	}
	
	/**
	 * Publishes the selected Contents
	 * @param $selectedContentsArray array - array with the contents' IDs to publish 
	 */
	function publish($selectedContents)
	{
		$selectedContentsArray = split(",", $selectedContents);
				
		$objectService = new ObjectService();
		$objectService->publishArray($selectedContentsArray);
		
		$this->show_view();
	}
}


?>