<?php

require_once "frontAttributes/frontAttribute.php";

/**
 * Implements a File attribute that allows to upload data
 *
 * @author despada
 */
class UploadFileFrontAttribute extends FrontAttribute
{
	var $folderFsPath;
	var $folderHttpPath;
	var $extensions;
	
	var $attachedData;
	var $uniqueName;
	
	
	/**
	 * Constructs a UploadFileFrontAttribute. See FrontAttribute constructor reference for details.
	 */
	function UploadFileFrontAttribute($language, $variant, $folderFsPath, $folderHttpPath, $extensions)
	{	
		// Super constructor
		$this->FrontAttribute($language, $variant);
		
		// Save paths and data given
		$this->folderFsPath = $folderFsPath;
		$this->folderHttpPath = $folderHttpPath;
		$this->extensions = $extensions;
		
		// Get unique name, if this is an update
		$value = $this->getValue();

		if (is_null($value) == false)
		{
			// Extract unique name
			$pos = strrpos($value, "/");
			
			if ($pos != false)
				$this->uniqueName = substr($value, ++$pos);
		}
	}
	
	/**
	 * Draws this datatype as a HTML widget directly into the output stream
	 */
	function drawWidget()
	{
		/* @var $attribute ClassAttribute */
		$attribute = $this->getAttribute();
		?>
		<input type="file" size="20" class="text_area" id="<?php echo $this->getName()?>" name="<?php echo $this->getName()?>" />
		<?
		
		if ($this->getValue() != null)
			?><a href="<?php echo $this->getValue()?>"><?php echo $attribute->getTitle()?></a><?php
	}

	/**
	 * Gets the unique name for the file, extracting the extension from the attached data
	 * @return String - string with the name
	 * @access private
	 */
	function getUniqueName()
	{				
		// Generate unique name ?
		if (is_null($this->uniqueName))
		{
			assert(is_null($this->attachedData) == false);
			
			$extension = $this->getExtension($this->attachedData["name"]);
			$this->uniqueName = time() . rand(1, 999999) . "." . $extension;
		}
		
		return $this->uniqueName;
	}
	
	/**
	 * Given a filename, gets the extension
	 * @param $name String - filename
	 * @return String - extension
	 * @access private
	 */
	function getExtension($name)
	{
		$extension = "";
		$pos = strrpos($name, ".");
		if ($pos != false)
			$extension = substr($name, ++$pos);
			
		return $extension;
	}
	
	/**
	 * Collects the value sent in the $_REQUEST object and saves it into the class and
	 * any attached data.
	 */
	function collectWidget()
	{
		$this->setValue(null);
		
		// Necessary paths ?
		if ($this->folderFsPath == null || $this->folderFsPath == "")
			trigger_error("Must initialize the folderFsPath attribute before collecting");		
		
		// Collect file
		$this->attachedData = null;
		if (array_key_exists($this->getName(), $_FILES)) 
		{
			$this->attachedData = $_FILES[$this->getName()];
			// Set value pointing to HTTP address of this file						
			$value = $this->folderHttpPath . "/" . $this->getUniqueName();
			// Set the value
			$this->setValue($value);
		}		
	}
	
	/**
	 * Determines if the given value is correct for a boolean textbox.
	 * @return true if it's correct, false if it is not
	 */
	function isValid()
	{
	
		if ($this->attribute->getIsRequired() == false)
		    return true;
		    
		if ($this->attribute->getIsRequired() &&  $this->attachedData == null) 
		{
			// Check if file was already uploaded
			if (file_exists($this->folderFsPath . "/" . $this->getUniqueName())) return true;
			
			// It wasn't, must be supplied
			return false;
		}
				
		// Check if the file has an allowed extension
		$extension = $this->getExtension($this->getUniqueName());
		foreach($this->extensions as $allowedExtension)
		{
			if ($extension == $allowedExtension) return true;
		}
		
		// No allowed extension...
		return false;
	}
	
	/**
	 * Saves the attached data into disk
	 */
	function saveAttachedData()
	{
		if ($this->isValid() == false)
		{
			trigger_error("Attached data should be valid");
			return;
		}
		
		// If it wasn't posted but is valid, then is an update and is already saved
		if (is_null($this->attachedData)) return;
		
		// Save file
		move_uploaded_file($this->attachedData["tmp_name"], $this->folderFsPath . "/" . $this->getUniqueName());
	}
}

?>