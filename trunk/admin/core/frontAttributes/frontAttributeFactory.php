<?php

require_once "frontAttributes/frontAttribute.php";
require_once "frontAttributes/textFrontAttribute.php";
require_once "frontAttributes/memoFrontAttribute.php";
require_once "frontAttributes/htmlFrontAttribute.php";
require_once "frontAttributes/integerFrontAttribute.php";
require_once "frontAttributes/decimalFrontAttribute.php";
require_once "frontAttributes/dateFrontAttribute.php";
require_once "frontAttributes/timeFrontAttribute.php";
require_once "frontAttributes/booleanFrontAttribute.php";
require_once "frontAttributes/emailFrontAttribute.php";
require_once "frontAttributes/urlFrontAttribute.php";
require_once "frontAttributes/userFrontAttribute.php";
require_once "frontAttributes/mediaFrontAttribute.php";
require_once "frontAttributes/imageFrontAttribute.php";
require_once "frontAttributes/uploadFileFrontAttribute.php";
require_once "frontAttributes/uploadImageFrontAttribute.php";
require_once "frontAttributes/uploadMediaFrontAttribute.php";

/**
 * Implementation of the Factory Pattern for the FrontDatatypes. It constructs
 * the correct FrontDatatype representation for a given Datatype.
 *
 * @author despada 2005-05-08
 */
class FrontAttributeFactory
{	
	/**
	 * Constructs a new FrontAttribute for a given attribute
	 * @static
	 * @param $language Language - Language object
	 * @param $variant Object - a ClassAttribute or a ObjectAttribute
	 * @return FrontAttribute - a new FrontAttribute object
	 */
	function newInstance($language, $variant)
	{		
		// Get its datatype
		$datatype = $variant->getDatatype();
		
		$frontAttributeClassName = $datatype->getDatatype() . "FrontAttribute";	
		$dinamicCode = "\$frontAttribute = new " . $frontAttributeClassName . "(\$language, \$variant);";
		
		// Dynamically invoke the constructor for a FrontAttribute
		$frontAttribute = null;
		eval($dinamicCode);
		
		if ($frontAttribute == null)
			trigger_error("Cannot instantiate front attribute dynamically: " . $dinamicCode);
			
		return $frontAttribute;
	}
}

?>