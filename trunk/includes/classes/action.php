<?php

/**
 * An action that a user can do on an object
 *
 * @author despada 2005-04-06 07:07
 */
class Action
{
	var $id;
	var $action;
	
	/**
	 * Creates a new action, setting its code name
	 * @param $action string - code name of the action, e.g.: ADD_OBJECTS
	 */
	function Action($action = null)
	{
		$this->action = $action;
	}
	
	function setId($id)
	{
		$this->id = $id;
	}
	
	function getId()
	{
		return $this->id;
	}
	
	function setAction($action)
	{
		$this->action = $action;
	}
	
	function getAction()
	{
		return $this->action;
	}
	
	/**
	 * Returns the action string representing ADD_OBJECTS_ACTION
	 * @static
	 * @return Action - ADD_OBJECTS string
	 */
	function ADD_OBJECTS_ACTION()
	{
		return new Action("ADD_OBJECTS");
	}

	/**
	 * Returns the action string representing LIST_OBJECTS_ACTION
	 * @static
	 * @return Action - LIST_OBJECTS string
	 */
	function LIST_OBJECTS_ACTION()
	{
		return new Action("LIST_OBJECTS");
	}
	
	/**
	 * Returns the action string representing VIEW_OBJECTS_ACTION
	 * @static
	 * @return Action - VIEW_OBJECTS string
	 */
	function VIEW_OBJECTS_ACTION()
	{
		return new Action("VIEW_OBJECTS");
	}

	/**
	 * Returns the action string representing EDIT_OBJECTS_ACTION
	 * @static
	 * @return Action - EDIT_OBJECTS string
	 */
	function EDIT_OBJECTS_ACTION()
	{
		return new Action("EDIT_OBJECTS");
	}
	
	/**
	 * Returns the action string representing PUBLISH_OBJECTS_ACTION
	 * @static
	 * @return Action - PUBLISH_OBJECTS_ACTION string
	 */
	function PUBLISH_OBJECTS_ACTION()
	{
		return new Action("PUBLISH_OBJECTS");
	}
	
	/**
	 * Returns the action string representing REMOVE_OBJECTS_ACTION
	 * @static
	 * @return Action - REMOVE_OBJECTS string
	 */
	function REMOVE_OBJECTS_ACTION()
	{
		return new Action("REMOVE_OBJECTS");
	}
	
	/**
	 * Returns the action string representing ADD_FOLDERS_ACTION
	 * @static
	 * @return Action - ADD_FOLDERS string
	 */
	function ADD_FOLDERS_ACTION()
	{
		return new Action("ADD_FOLDERS");
	}
	
	/**
	 * Returns the action string representing LIST_FOLDERS_ACTION
	 * @static
	 * @return Action - LIST_FOLDERS string
	 */
	function LIST_FOLDERS_ACTION()
	{
		return new Action("LIST_FOLDERS");
	}
	
	/**
	 * Returns the action string representing VIEW_FOLDERS_ACTION
	 * @static
	 * @return Action - VIEW_FOLDERS string
	 */
	function VIEW_FOLDERS_ACTION()
	{
		return new Action("VIEW_FOLDERS");
	}
	
	/**
	 * Returns the action string representing EDIT_FOLDERS_ACTION
	 * @static
	 * @return Action - EDIT_FOLDERS string
	 */
	function EDIT_FOLDERS_ACTION()
	{
		return new Action("EDIT_FOLDERS");
	}
	
	/**
	 * Returns the action string representing REMOVE_FOLDERS_ACTION
	 * @static
	 * @return Action - REMOVE_FOLDERS string
	 */
	function REMOVE_FOLDERS_ACTION()
	{
		return new Action("REMOVE_FOLDERS");
	}
	
}

?>