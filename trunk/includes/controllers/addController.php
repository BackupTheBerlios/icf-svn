<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

require_once "icfHorizontal.php";
require_once "controller.php";
require_once "mappers/baseClassMapper.php";
require_once "mappers/languageMapper.php";
require_once "mappers/objectMapper.php";
require_once "service/objectService.php";
require_once "frontAttributes/frontAttributeFactory.php";
require_once "classes/controllerMessage.php";
require_once "service/objectServiceFactory.php";

/**
 * Assumes the controller role of the add feature
 *
 * Parameters:
 * "classId": an object with this class is going to be created
 * "objectId": only if you are going to update the object. If you supply it, you may not supply classId then.
 *
 * Available methods:
 * "showView": shows the default view
 * "save": creates the object
 * "update": updates an object
 */
class AddController extends Controller
{    
  /*
   * Class id whose object is to be created, expected parameter
   * @var $classId int
   */
  var $classId;
  
  /**
   * Determined by classId
   * @var $class BaseClass
   */
  var $class;
  
  /**
   * Object to update, if applies
   * @var $objectId int
   */
  var $objectId;
  
  /**
   * Object to update, if applies
   * @var $object Object
   */
  var $object;
  
	/**
	 * Constructs AddController
	 */
	function AddController()
	{				
		// Construct...
		$this->Controller();
		
		// Get parameters
		$this->classId = $_REQUEST["classId"];
		$this->objectId = $_REQUEST["objectId"];
			
		// Set the object in context
		if ($this->objectId != null && $this->objectId != "")
		{
			$objectMapper = new ObjectMapper();
			$this->object = $objectMapper->get($this->objectId);
			$this->classId = $this->object->getClassID();
		}

		assert($this->classId != null && $this->classId != "");
		
		// Set the class in context
		$baseClassMapper = new BaseClassMapper();					
		$this->class =& $baseClassMapper->get($this->classId);
		
		// Set dateFormat
		$dateFormat = DateFormatFactory::getDateFormat();
		$this->tpl->assign("dateFormat", $dateFormat);
	}
	
	/**
	 * Collects the Object sent by HTTP. This method also fills the controller messages with the appropiate errors.
	 * @param $object Object - object to update, if not supplied a new one is created
	 * @return Object - Object collected, it there was problems collecting, it will return null
	 */
	function collectObject($object = null)
	{				
		// Signal -> if true, there was an error, so do not save
		$save = true;
		
		// Get languages
		$languageMapper = new LanguageMapper();
		$languageArray = $languageMapper->getAll();
					
		// Collect data from attributes for each language
		$frontAttributeArray = array();
		$objectAttributeArray = array();
		foreach($languageArray as $language)
		{
			$attributeArray = $this->class->getAttributesForLanguage($language);
						
			foreach($attributeArray as $attribute)
			{
				// Get the ObjectAttribute if it was already saved (it could be new...)
				if (is_null($object) == false)
				{
					$objectAttribute = $object->getAttributeForLanguage($attribute->getId(), $language->getId());
					if ($objectAttribute != null) $attribute = $objectAttribute;
				}
							
				// Create his GUI representation
				$frontAttribute = FrontAttributeFactory::newInstance($language, $attribute);
				
				// Collect it
				$frontAttribute->collectWidget();
				
				if ($frontAttribute->isValid() == false)	
				{ 
					$this->frontAttributeNotValidMessage($frontAttribute); 
					$save = false;
				}
								
				array_push($frontAttributeArray, $frontAttribute);
				array_push($objectAttributeArray, $frontAttribute->toObjectAttribute());
			}
		}
		
		// Collect object relations
		$classRelationArray = $this->class->getClassRelations();
		$objectRelationArray = array();
		foreach($classRelationArray as $classRelation)
		{
			/* @var $classRelation ClassRelation */
			$name = "classRelation" . $classRelation->getId() . "Select";
			$valueArray = $_POST[$name];
			
			if (is_null($valueArray) == false && $valueArray != "")
			{										
				foreach($valueArray as $value)
				{
					$objectRelation = new ObjectRelation();
					$objectRelation->setChildId($value);
					$objectRelation->setPosition($classRelation->getPosition());
					
					array_push($objectRelationArray, $objectRelation);
				}
			}
			else
			{
				if ($classRelation->getIsRequired())
				{
					// It was required, notify the user
					$this->dataNotValidMessage($classRelation->getTitle());
					$save = false;
				}
			}
		}		
		
		// Collect publishing info
		// Get date format
		$dateFormat = DateFormatFactory::getDateFormat();
		
		$publishCheckbox = $_REQUEST["publishCheckbox"];
		
		$publish = 0;
		if ($publishCheckbox == "-1") $publish = true;
		
		// Only get publishing dates if the user issued publish in true
		$publishFromDateString = null;
		$publishToDateString = null;
		if ($publish == true)
		{
			// Get ISO dateFormat for the database
			$isoDateFormat = new IsoDateFormat();
		
			// Get publishFrom/to checking if they are available first
			$publishFrom = $_REQUEST["publishFromText"];
			if ($publishFrom != null && $publishFrom != "")
			{
				$publishFromDate = $dateFormat->parseDate($publishFrom);
				if ($publishFromDate == null) 				
				{ 
					$this->dataNotValidMessage($this->text["from"]); 
					$save = false; 
				}
				else
					$publishFromDateString = $isoDateFormat->toDatetimeString($publishFromDate);
			}
			
			$publishTo = $_REQUEST["publishToText"];
			if ($publishTo != null && $publishTo != "")
			{
				$publishToDate = $dateFormat->parseDate($publishTo);
				if ($publishToDate == null) 
				{ 
					$this->dataNotValidMessage($this->text["to"]); 
					$save = false; 
				}
				else
					$publishToDateString = $isoDateFormat->toDatetimeString($publishToDate);
			}
		}
		
		$hits = $_REQUEST["hitsText"];
		
		// Get tree references
		$folders = $_REQUEST["folders"];	
		$objectFolderArray = array();
		$folderMapper = new FolderMapper();
		if ($folders != null && $folders != "")
		{
			$folderIdArray = split(",", $folders);
			
			foreach($folderIdArray as $folderId)
			{
				$folder = $folderMapper->get($folderId);
				/* @var $folder Folder */
				$objectFolderPosition = $folder->getNextObjectFolderPosition();
				
				$objectFolder = new ObjectFolder(null, $folderId);
				$objectFolder->setPosition($objectFolderPosition);
				
				array_push($objectFolderArray, $objectFolder);
			}
		}
		
		if ($save == false) return null;
		
		// My new object
		if ($object == null) $object = new Object();
		$object->setId($this->objectId);
		$object->setClassID($this->classId);
		$object->setIsPublished($publish);		
		$object->setStartPublishing($publishFromDateString);
		$object->setEndPublishing($publishToDateString);
		$object->setHits($hits);
		// Attributes
		$object->setAttributes($objectAttributeArray);		
		// Folders
		$object->setObjectFolders($objectFolderArray);
		// Relationships
		$object->setObjectRelations($objectRelationArray);
		
		// Save attached data, if any
		/* @var $frontAttribute FrontAttribute */
		foreach($frontAttributeArray as $frontAttribute)
			$frontAttribute->saveAttachedData();
			
		// Return the object
		return $object;
	}
	
	/**
	 * Saves the object sent by HTTP
	 */
	function save()
	{
		/* @var $object Object */
		
		$save = false;
		$object = $this->collectObject();
				
		// If there was an error, do not try to save
		if ($object == null)
		{
			$this->showView(true);
			return;
		}

		$saveIt = true;

		if ( $object->getIsPublished() )
		{
			//check publishing permissions
			$canPublish = $object->canDoAction(null, Action::PUBLISH_OBJECTS_ACTION()); 

			if (! $canPublish)
				$saveIt = false;
		}
			
		if ($saveIt)
		{
			// Save !!
			$objectService = ObjectServiceFactory::newInstance($object->getClass());
			$objectService->save($object);
	
			// Everything went all right, display view
			$this->redirectToReferer();
		}
		else
		{
			//send error message
			$controllerMessage = new ControllerMessage($this->text["notenoughpermissionstopublish"], ControllerMessage::getErrorType());
			array_push($this->controllerMessageArray, $controllerMessage);
			$this->showView(true);
			return;
		}
	
	}
	
	/**
	 * Appends a "data not valid" message to the controller messages for a FrontAttribute
	 * @param $frontAttribute attribute whose data is not valid
	 */
	function frontAttributeNotValidMessage($frontAttribute)
	{
		$attribute = $frontAttribute->getAttribute();
		$this->dataNotValidMessage($attribute->getTitle());
	}
	
	/**
	 * Appends a "data not valid" message to the controller messages
	 * @param $field String - Field name whose data is not valid
	 */
	function dataNotValidMessage($field)
	{		
		$text = str_replace("field", $field, $this->text["dataNotValid"]); 
		$controllerMessage = new ControllerMessage($text, ControllerMessage::getErrorType());
		array_push($this->controllerMessageArray, $controllerMessage);
	}
	
	/**
	 * Updates the object sent by HTTP
	 */
	function update()
	{
		$save = false;
		$object = $this->collectObject($this->object);
		
		// If there was an error, do not try to save
		if ($object == null)
		{
			$this->showUpdateView(true);
			return;
		}
		
		$saveIt = true;

		$om = new ObjectMapper();
		$prevObject = $om->get($object->getId());

		if ( $object->getIsPublished() != $prevObject->getIsPublished())
		{
			//check publishing permissions
			$canPublish = $object->canDoAction(null, Action::PUBLISH_OBJECTS_ACTION()); 

			if (! $canPublish)
				$saveIt = false;
		}
			
		if ($saveIt)
		{
			// Update !!
			$objectService = ObjectServiceFactory::newInstance($object->getClass());
			$objectService->update($object);
			
			
			// Everything went all right, display view
			$this->redirectToReferer();
		}
		else
		{
			//send error message
			$controllerMessage = new ControllerMessage($this->text["notenoughpermissionstopublish"], ControllerMessage::getErrorType());
			array_push($this->controllerMessageArray, $controllerMessage);
			$this->showView(true);
			return;
		}


	}
	
	/**
	 * Constructs an "frontLanguage" array, a mere structure for the Add / Update View that is just an array
	 * of another array. The structure is as follows:
	 *
	 * frontLanguageArray[X] =
	 *   [0] = Lanugage Object
	 *   [1] = FrontAttribute Objects array
	 *
	 * So, you get all attributes for each language object to display
	 *
	 * @param $object Object - If specified, attribute values assigned to the object are extracted and filled in the FrontAttribute
	 * @return array - the "FrontLanguage" array
	 */
	function constructFrontLanguageArray($object = null)
	{	
		// Set all data needed
		// Takes all attributes for the class and for each language
		$languageMapper = new LanguageMapper();
		$languageArray = $languageMapper->getAll();		
		
		// Generate FrontAttributes
		$frontLanguageArray = array();
		foreach($languageArray as $language)
		{			
			$attributeArray = $this->class->getAttributesForLanguage($language);
							
			$frontAttributeArray = array();
			foreach($attributeArray as $classAttribute)
			{
				$variant = null;
				
				// Crossroad -> if the object was given, try to extract the ObjectAttribute
				if ($object != null) $variant = $object->getAttributeForLanguage($classAttribute->getId(), $language->getId());
				
				// If the object was not received, or the object doesn't have this attribute assigned, use the basic attribute
				if ($variant == null) $variant = $classAttribute;
				
				$frontAttribute = FrontAttributeFactory::newInstance($language, $variant);
				array_push($frontAttributeArray, $frontAttribute);
			}
			
			$languageObject = array(0 => $language, 1 => $frontAttributeArray);
			array_push($frontLanguageArray, $languageObject);
		}
		
		return $frontLanguageArray;
	}
	
	/**
	 * Displays the add view
	 * @param $setPostInContext boolean - if true, the method will collect the posted data and 
	 * display it in the widgets. This is tippically used for asking the user to reenter some 
	 * data after a validation failed.
	 */
	function showView($setPostInContext = false)
	{								
		// Title of the page		
		$pageTitle = str_replace("className", $this->class->getTitle(), $this->text["addobject"]);
		
		// Add items to toolbar
		$this->setAddToolbar();
		
		// Attributes
		$frontLanguageArray = $this->constructFrontLanguageArray();										
		// Folders
		$allowedFolderArray = $this->getFolderArray($this->class);
		
		if ($setPostInContext)
			$this->setPostInContext($frontLanguageArray);
		else
		{
			$this->tpl->assign("hits", "0");
						
			$this->tpl->assign("createdBy", "-");
			$this->tpl->assign("createdOn", "-");
			
			$this->tpl->assign("updatedBy", "-");
			$this->tpl->assign("updatedOn", "-");
		}
		
		$this->displayAdd($pageTitle, $frontLanguageArray, $allowedFolderArray);
	}

	/**
	 * Shows a view that allows the user to update an object
	 * @param $object Object - object to update
	 * @param $setPostInContext boolean - if true, $object won't be used to fill the fields. Instead, data received by post will be used for that purpose.
	 */
	function showUpdateView($setPostInContext = false)
	{				
		$object = $this->object;
		// Has the required permissions ?
		if ($object->canDoAction(null, Action::EDIT_OBJECTS_ACTION()) == false)
		{
			$controllerMessage = new ControllerMessage($this->text["notenoughpermissions"], ControllerMessage::getErrorType());
			array_push($this->controllerMessageArray, $controllerMessage);
			$this->showView(false);
			return;
		}
				
		// Title of the page		
		$pageTitle = str_replace("className", $this->class->getTitle(), $this->text["updateobject"]);
		
		// Attributes
		$frontLanguageArray = $this->constructFrontLanguageArray($object);
		// Folders
		$allowedFolderArray = $this->getFolderArray($this->class, $object);

		$this->tpl->assign("objectId", $object->getId());
		
		// Add items to toolbar
		$this->setUpdateToolbar();
		
		if ($setPostInContext)
			$this->setPostInContext($frontLanguageArray);
		else
		{
			$dateFormat = DateFormatFactory::getDateFormat();
			$isoDateFormat = new IsoDateFormat();
		
			$isPublished = $object->getIsPublished();
			if ($isPublished) $this->tpl->assign("publishCheckbox", "-1");
		
			$publishFromDate = $isoDateFormat->parseDatetime($object->getStartPublishing());
			if ($publishFromDate != null) $this->tpl->assign("publishFromText", $dateFormat->toDateString($publishFromDate));
		
			$publishToDate = $isoDateFormat->parseDatetime($object->getEndPublishing());
			if ($publishToDate != null) $this->tpl->assign("publishToText", $dateFormat->toDateString($publishToDate));
		
			$this->tpl->assign("hits", $object->getHits());
			
			$createdOnDate = $isoDateFormat->parseDatetime($object->getCreated());
			$user = $object->getCreatedByUser();
			
			$this->tpl->assign("createdOn", $dateFormat->toDatetimeString($createdOnDate));
			$this->tpl->assign("createdBy", $user->getName());
		
			$updatedOnDate = $isoDateFormat->parseDatetime($object->getUpdated());
			$user = $object->getUpdatedByUser();			
			
			$updatedBy = "-";
			$updatedOn = "-";
			
			if ($updatedOnDate != null) $updatedOn = $dateFormat->toDatetimeString($updatedOnDate);
			if ($user != null) $updatedBy = $user->getName();
			
			$this->tpl->assign("updatedOn", $updatedOn);
			$this->tpl->assign("updatedBy", $updatedBy);

		}		
		
		$this->displayAdd($pageTitle, $frontLanguageArray, $allowedFolderArray);
	}
	
	/**
	 * Display the "Add" view. The parameters explicit the needed data for it
	 * @param $pageTitle String - Title of the page
	 * @param $frontLanguageArray array - Constructed frontLanguageArray
	 * @param $allowedFolderArray array - array of tree items
	 */
	function displayAdd($pageTitle, $frontLanguageArray, $allowedFolderArray)
	{	
		$this->setPageTitle($pageTitle);
		
		// Attributes		
		$this->tpl->assign("frontLanguageArray", $frontLanguageArray);
		
		// Folders		
		$this->tpl->assign("allowedFolderArray", $allowedFolderArray);
		
		// Class
		$this->tpl->assign("class", $this->class);
		
		// Object (if exists)
		$this->tpl->assign("object", $this->object);
		
		// Messages
		$this->tpl->assign("controllerMessageArray", $this->controllerMessageArray);
		
		$this->collectControlerData();
		
		// Display
		$this->displayView("add.tpl.php");
		// $this->tpl->display("add.tpl.bak.php");
	}
		
	/**
	 * Takes the post and reasigns it to the screen, so the user can modify the data
	 * @param $frontLanguageArray array - passed by reference, its widget are collected so it can be redisplayed to the user
	 */
	function setPostInContext(&$frontLanguageArray)
	{		
		// Collect existing data
		for ($i = 0; $i < count($frontLanguageArray); $i++)
		{
			$frontLanguage =& $frontLanguageArray[$i];
			$frontAttributeArray =& $frontLanguage[1];
			
			for ($j = 0; $j < count($frontAttributeArray); $j++)
			{
				$frontAttribute =& $frontAttributeArray[$j];
				$frontAttribute->collectWidget();
			}
		}
				
		// Reassign values
		$keys = array_keys($_REQUEST);
		foreach($keys as $key)
			$this->tpl->assign($key, $_REQUEST[$key]);
	}
	
	/**
	 * Adds the necesary items to the toolbar
	 */
	function setAddToolbar()
	{
		$toolbar =& $this->icfTemplating->getToolbar();
		
		$ti = new icfToolbarItem();
		$ti->setName("cancel");
		$ti->setTitle($this->text["cancel"]);
		$ti->setUrl("#");
		$ti->setImage("/images/cancel.png");
		$ti->setImage2("/images/cancel_f2.png");
		$ti->setOnclick("cancel_onClick()");
		
		$toolbar->addToolbarItem($ti);
		
		$ti = new icfToolbarItem();
		$ti->setName("save");
		$ti->setTitle($this->text["save"]);
		$ti->setUrl("#");
		$ti->setImage("/images/save.png");
		$ti->setImage2("/images/save_f2.png");
		$ti->setOnclick("save_onClick()");
		
		$toolbar->addToolbarItem($ti);

		// Set toolbar
		$this->icfTemplating->setToolbar($toolbar);
	}

		/**
	 * Adds the necesary items to the toolbar
	 */
	function setUpdateToolbar()
	{
		$toolbar =& $this->icfTemplating->getToolbar();		
		
		$ti = new icfToolbarItem();
		$ti->setName("cancel");
		$ti->setTitle($this->text["cancel"]);
		$ti->setUrl("#");
		$ti->setImage("/images/cancel.png");
		$ti->setImage2("/images/cancel_f2.png");
		$ti->setOnclick("cancel_onClick()");
		
		$toolbar->addToolbarItem($ti);
		
		$ti = new icfToolbarItem();
		$ti->setName("update");
		$ti->setTitle($this->text["save"]);
		$ti->setUrl("#");
		$ti->setImage("/images/save.png");
		$ti->setImage2("/images/save_f2.png");
		$ti->setOnclick("update_onClick()");
		
		$toolbar->addToolbarItem($ti);

		// Set toolbar
		$this->icfTemplating->setToolbar($toolbar);		
	}
	
	/**
	 * Looks up the folders allowed for the user in the session that can be used to add a new class object
	 * @param $class Class whose instance are to be added
	 * @return Array - An array that includes for each position other array with: id, title, parentId, closed, open, mode
	 */
	function getFolderArray($class, $object = null)
	{
		// Get root folders
		$folderMapper = new FolderMapper();
		$folder = $folderMapper->getRoot();
		
		$folderArray = array(0 => $folder);
		$allowedFolderArray = array();
		
		return $this->getFolderArrayRecursive($folderArray, $class, $allowedFolderArray, $object);
	}
	
	/**
	 * Only to be called from getFolderArray, provides the recursive feature
	 * @param $childrenArray array of children folders
	 * @param $class The class being displayed
	 * @param $allowedFolderArray Array being constructed with folders to display in a tree
	 * @return allowedFolderArray for method chaining
	 */
	function getFolderArrayRecursive($childrenArray, $class, $allowedFolderArray, $object = null)
	{
		// Get the session
		$session = new Session();
		
		// For each root folder, inspect it
		foreach ($childrenArray as $folder)
		{
			/* @var $folder Folder */
			
			// Has this folder allowed children?
			if ($folder->hasAllowedLeaf($class, Action::ADD_OBJECTS_ACTION()))
			{
				// echo "Evaluando folder: " . $folder->getTitle() . "<br/>";
				
				/* @var $folderClass FolderClass */
				// Can the user add objects to this folder ?	
				$folderClass = $folder->getFolderClass($class);
				if (is_null($folderClass))
				{
					$folderClass = new FolderClass($class->getId(), $folder->getId());
					$folderClass->setIsDefault(false);
				}
				
				// Must be displayed...
				$id = $folder->getId();
				$parentId = 0;
				$closed = "leaf.gif";
				$open = "leaf.gif";
				$mode = "";
				$checked = false;
				
				// If we are not rendering an update, we must check items that are marked as default
				if (is_null($object)) $checked = $folderClass->getIsDefault();

				// Has him a parent ?
				if (is_null($folder->getParentId()) == false) $parentId = $folder->getParentId();
				
				// If we are rendering an update, we must check items that are marked as it in the Persistence layer
				if ($object != null) 
					$checked = $object->isObjectInFolder($folder->getId());
					
				// Determine images
				if (count($folder->getChildren()) > 0)
				{
					$closed = "folderClosed.gif";
					$open = "folderOpen.gif";
				}
				
				// Create and add the new allowed item
				$arrayItem = array("id" => $id, "title" => $folder->getTitle(), "parentId" => $parentId, "closed" => $closed, "open" => $open, "mode" => $mode, "checked" => $checked);
				array_push($allowedFolderArray, $arrayItem);
			}
			
			// Display its children
			$allowedFolderArray = $this->getFolderArrayRecursive($folder->getChildren(), $class, $allowedFolderArray, $object);
		}

		return $allowedFolderArray;	
	}
	
	/**
	 * Holds only the referer
	 */ 
	function newControllerData()
	{
		return array("refererHidden" => "");
	}

}
?>