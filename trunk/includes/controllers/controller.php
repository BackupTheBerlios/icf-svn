<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

require_once "icfHorizontal.php";
require_once "mappers/baseClassMapper.php";
require_once "mappers/languageMapper.php";
require_once "service/objectService.php";
require_once "frontAttributes/frontAttributeFactory.php";
require_once "classes/controllerMessage.php";

/**
 * Base class for every ICF Controller Class
 */
class Controller
{
	/* Templating engine
	 * @var $icfTemplating IcfTemplating
	*/
	var $icfTemplating;
	
	/**
	 * Templating engine implementation
	 * @var $tpl Savant
	 */
  var $tpl;
  
  /*
   * Text array
   * @var $text array
   */
  var $text;
    
  /**
   * The array of messages from this controller
   * @var $controllerMessageArray array
   */
  var $controllerMessageArray;
  
  /**
   * Title page
   * @var String
   */
  var $pageTitle;
  
  /**
   * DateFormat to use in the page
   * @var DateFormat
   */
  var $dateFormat;
  
  /**
   * The referer of this controller instructs it to return to this view when all tasks are completed
   */
  var $returnToView;
  
  /**
   * Method called in this controller
   */
  var $method;
  
  /**
   * Data that is issued from the controller to the view and viceversa
   */
  var $controllerData;
  
  /**
   * Controller constructor, must be called by the controller subclass
   * @param $pageTitleTextKey Key of the title in the text array
   */
	function Controller()
	{		
		$this->method = "showView";
		if (array_key_exists("method", $_REQUEST))
			$this->method = $_REQUEST["method"];
				
		$this->icfTemplating = new IcfTemplating();
		$this->tpl =& $this->icfTemplating->getTpl();
		$this->text =& $this->icfTemplating->getText();		
		$this->controllerMessageArray = array();
		$this->pageTitle = "";
		$this->dateFormat = DateFormatFactory::getDateFormat();
		$this->controllerData =& $this->newControllerData();
	}
	
	/**
	 * Executes the controller method received in "method"
	 */
	function execute()
	{
		// Call controller method
		// echo "METHOD: " . $this->method;
		// return;
		eval("\$this->" . $this->method . "();");
	}
	
	/**
	 * Constructs a new ControllerData array, with the data transferred between controller and view
	 * @return Array - controller data
	 * @abstract
	 */
	function newControllerData()
	{
		trigger_error("newControllerData() not implemented");
	}
	
	/**
	 * Default show view method, must be implemented by children classes
	 * @abstract 
	 */
	function showView()
	{
		trigger_error("showView() is not implemented");
	}
	
	/**
	 * Set the controllerData structure. Sets an array with string keys, each one representing
	 * a field with a value for the GUI
	 * @param $controllerData Array - controllerData array
	 */
	function setControllerData(&$controllerData)
	{
		$this->controllerData =& $controllerData;
	}
	
	/**
	 * Gets the saved controllerData object
	 * @return Array - controllerData object
	 */
	function& getControllerData()
	{
		return $this->controllerData;
	}
	
	/**
	 * Sets the title for this page
	 * @param $pageTitleKey string - page title
	 */
	function setPageTitle($pageTitle)
	{
		$this->pageTitle = $pageTitle;
	}
	
	/**
	 * Collects all specified keys in controllerData from the request and
	 * returns the controllerData
	 * @return Array - controllerData object
	 */
	function& collectControlerData()
	{
		$keys = array_keys($this->controllerData);
		
		foreach($keys as $key)
		{
			$value = null;
			if (key_exists($key, $_REQUEST))
			{
				$value = $_REQUEST[$key];			
				$this->controllerData[$key] = $value;
			}			
		}
		
		return $this->controllerData;
	}
	
	/**
	 * Redirects to referer page, or the home page, if the former was not specified.
	 * The referer page is specified by the calling page, and is in the following format:
	 * refererHidden=page.php_param-paramvalue
	 * @param $controllerDataMember Name of the controllerData member variable that holds the referer. If none is specified, "refererHidden" is used.
	 */
	function redirectToReferer($controllerDataMember = "refererHidden")
	{
		$controllerData = $this->collectControlerData();
		$referer = $controllerData[$controllerDataMember];
		
		// Rules for defining referers
		if ($referer != null)
		{
			$referer = str_replace("-", "=", $referer);
			$referer = str_replace("_", "?", $referer);
		}
		
		if ($referer == null || $referer == "")
			$referer = "home.php";		
			
		$this->redirect($referer);
	}

	/**
	 * Display the view, taking care of all the details
	 */
	function displayView($scriptFile)
	{					
		// Date format to use in the page
		$this->tpl->assign("dateFormat", $this->dateFormat);
		
		// echo "PageTitle: " . $this->pageTitle;
		
		// Title of the page
		$this->tpl->assign("pageTitle", $this->pageTitle);
			
		// Messages
		$this->tpl->assign("controllerMessageArray", $this->controllerMessageArray);
				
		// Data
		$this->tpl->assign("controllerData", $this->controllerData);
		
		// Display
		$this->tpl->display($scriptFile);
	}
		
	/**
	 * Redirects the browser to another controller
	 * @param $scriptFile String - name of the file that creates the controller, e.g. add.php
	 */
	function redirect($scriptFile)
	{
		echo "<script language=\"javascript\">window.location.href='" . $scriptFile . "'</script>";
	}
	
	/**
	 * Sets the toolbar
	 * @param $toolbar IcfToolbar - toolbar
	 */
	function setToolbar(&$toolbar)
	{
		// Set toolbar
		$this->icfTemplating->setToolbar($toolbar);
	}
	
	/**
	 * Gets the toolbar
	 * @return IcfToolbar - toolbar
	 */
	function& getToolbar()
	{
		// Set toolbar
		return $this->icfTemplating->getToolbar();
	}

}
?>