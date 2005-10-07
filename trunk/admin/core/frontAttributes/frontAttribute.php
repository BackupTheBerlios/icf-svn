<?php

require_once "classes/objectAttribute.php";

/**
 * Base class of the GUI representation of an ICF Attribute. Each of these
 * attributes know how to draw themselves on the screen and collect their own data
 * from a POST. It is a GUI Class, not a business logic class, and is built on
 * top of the business logic attribute class.
 *
 * @author despada 2005-05-08
 */
class FrontAttribute
{
	var $language;
	var $attribute;
	var $value;
	var $attachedData;
	
	/**
	 * Constructs a FrontAttribute
	 * @param $language Language - The language of this attribute
	 * @param $variant Object - it can be an instance of ClassAttribute or an instance of ObjectAttribute only. If its an instance of ObjectAttribute, the value is initialized
	 */
	function FrontAttribute($language, $variant)
	{
		if (is_a($variant, "ClassAttribute"))
		{
			$this->language = $language;
			$this->attribute = $variant;
			$this->value = null;
			return;
		}
		
		if (is_a($variant, "ObjectAttribute"))
		{
			$this->language = $variant->getLanguage();
			$this->attribute = $variant->getClassAttribute();
			$this->value = $variant->getValue();
					
			return;
		}
		
		trigger_error("\$variant is not an instance from ClassAttribute or ObjectAttribute");		
	}
		
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 * @abstract
	 */
	function drawWidget()
	{
		trigger_error("Not implemented");
	}
	
	/**
	 * Collects the value sent in the $_REQUEST object and saves it into the class and
	 * any attached data.
	 */
	function collectWidget()
	{
		$value = $_REQUEST[$this->getName()];
		
		//echo "Collecting " . $this->getName() . ": " . $value . "<br/>";
		
		// Set the value
		$this->setValue($value);
	}
	
	/**
	 * Gets the value of this instance, collected from a post
	 * @return object - the value assigned
	 */
	function getValue()
	{
		return $this->value;
	}
	
	/**
	 * Sets the value, if this frontAttribute should be displayed with it
	 * @param $value Object - The value to be set
	 */
	function setValue($value)
	{		
		$this->value = $value;
	}
	
	/**
	 * The name assigned to this widget. The name is a unique key that can be
	 * composed of multiple other keys
	 * @return string - the name of the widget
	 */
	function getName()
	{
		return $this->attribute->getName() . "_" . $this->language->getCode();
	}
	
	/**
	 * Validates if the set value is appropiated for the FrontAttribute
	 * @return true if is valid, false if not
	 */
	function isValid()
	{
		if ($this->attribute->getIsRequired() && $this->value == null) return false;
		
		return true;
	}
	
	/**
	 * Gets the attribute on which this FrontAttribute finds its foundation
	 * @return ClassAttribute - the attribute
	 */
	function getAttribute()
	{
		return $this->attribute;
	}
	
	/**
	 * Constructs an ObjectAttribute object with the info contained in FrontAttribute
	 * @return ObjectAttribute - an ObjectAttribute object
	 */
	function toObjectAttribute()
	{
		$objectAttribute = new ObjectAttribute();
		$objectAttribute->setClassAttributeID($this->attribute->getId());
		$objectAttribute->setLanguageID($this->language->getId());
		$objectAttribute->setValue($this->getValue());
		
		return $objectAttribute;
	}
	
	/**
	 * An attribute can have additional attached data to it. For example, a File
	 * can have file data attached to the value. This method must save the attached data in disk.
	 * The default implementation of this method does nothing.
	 */
	function saveAttachedData()
	{
	}
}

?>