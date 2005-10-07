<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

require_once "Savant2.php";
require_once "icfToolbar.php";
require_once "icfToolbarItem.php";
require_once "icfMenu.php";
require_once "mappers/baseClassMapper.php";
require_once "mappers/folderMapper.php";
require_once "classes/action.php";

/**
 * Prepares the templating object implementation for the ICF
 */
class IcfTemplating
{	
	var $tpl;
	
	// Toolbar and text assigned for the current locale
	var $text;
	var $toolbar;
	var $menu;
	
	/**
	 * Constructs the IcfTemplating object
	 */ 
	function IcfTemplating($page = "")
	{
		// Load configuration
		$icfConfig = new IcfConfig();
		
		$this->tpl =& new Savant2();
				
		// add a template path
		$this->tpl->addPath("template", $icfConfig->cfg_site_beTemplatePath);
		$this->tpl->assign("templatePath", $icfConfig->cfg_site_beTemplateUrl);
		$this->tpl->assign("basePath", $icfConfig->cfg_site_feBaseUrl);
		
		// multilingual support
		require_once $this->getStringsFile($icfConfig);
		
		// Session support
		$session = new Session();
		$this->tpl->assign("user", $session->getSessionUser());	
		
		// basic toolbar support
		$toolbarItem = new icfToolbarItem();
		$toolbarItem->setName("exit");
		$toolbarItem->setTitle($text["exit"]);
		$toolbarItem->setUrl("login.php");
		$toolbarItem->setImage("/images/exit.png");
		$toolbarItem->setImage2("/images/exit_f2.png");
		
		$toolbar = new IcfToolbar();
		$toolbar->addToolbarItem($toolbarItem);
				
		// Menu support
		$menu = new IcfMenu();		
		
		// Only work it if the session is valid
		$session = new Session();
		if ($session->isValid() == true)
		{
			$user = $session->getSessionUser();
			
			// Classes whose objects the user can create
			$baseClassMapper = new BaseClassMapper();
			$classes = $baseClassMapper->findByPermission(Action::ADD_OBJECTS_ACTION(), $user);
			$menu->setContents($classes);
			// The folders
			$folderMapper = new FolderMapper();
			$rootFolder = $folderMapper->getRoot();
			$folderArray = array(0 => $rootFolder);
			$menu->setFolders($folderArray);
		}
		
		// Set the generated content in the context of this request (available for client pages to change it)
		$this->setText($text);
		$this->setToolbar($toolbar);
		$this->setMenu($menu);
	}
	
	/**
	 * Obtains the locale strings file
	 *
	 * @access private
	 * @param $icfConfig Configuration parameters
	 * @param @page optional, Page whose strings file are going to be obtained. If not specified, PHP_SELF is used
	 * @return String - path to the file
	 */
	function getStringsFile($icfConfig, $page = "")
	{
		if ($page == "")
			$page = $_SERVER['PHP_SELF'];
			
		$sn = $page;
		$pos = strrpos($sn, "/");
		$path = substr($sn,0,$pos);
		$script = substr($sn, $pos+1);	
		
		return "locales/" . $icfConfig->cfg_locale_active . "/" . $script;
	}
	
	/**
	 * Returns the templating object implementation
	 *
	 * @return IcfTemplating - templating object implementation
	 */
	function& getTpl()
	{
		return $this->tpl;
	}

	/**
	 * Gets the text array assigned for the current locale
	 *
	 * @return String - array with the assigned values
	 */
	function& getText()
	{
		return $this->text;
	}
	
	/**
	 * Sets the text object in the context of this request
	 *
	 * @param $text String - the text object to be set
	 */
	function setText(&$text)
	{
		$this->text = $text;
		$this->tpl->assign("text", $text);
	}
	
	/**
	 * Gets the toolbar for the system
	 *
	 * @return IcfToolbar - Toolbar that contains toolbar items
	 */
	function& getToolbar()
	{
		return $this->toolbar;
	}
	
	/**
	 * Sets the toolbar in the context of this page
	 * @param $toolbar IcfToolbar - the toolbar to be set
	 */
	function setToolbar(&$toolbar)
	{		
		$this->tpl->assign("toolbar", $toolbar);
		$this->toolbar = $toolbar;
	}
	
	/**
	 * Gets the menu for the system
	 *
	 * @return IcfMenu - the menu object
	 */
	function& getMenu()
	{
		return $this->menu;
	}
	
	/**
	 * Sets the menu object
	 *
	 * @param $menu IcfMenu - the menu to be set
	 */
	function setMenu(&$menu)
	{
		$this->tpl->assign("menu", $menu);
		$this->menu = $menu;
	}
	
}

?>