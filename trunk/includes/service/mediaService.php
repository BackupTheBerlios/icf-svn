<?php

require_once "icfDatabase.php";
require_once "icfConfig.php";
require_once "classes/file.php";
require_once "mappers/objectMapper.php";
require_once "mappers/objectAttributeMapper.php";

/**
 * Service object for media and images objects
 */
class MediaService
{	
	/**
 	 * Lists the graphic files from the images dir
 	 * @return Array - array of file objects
 	 */
	function listImages() 
	{
		$icfConfig = new IcfConfig();
		
		$objectMapper = new ObjectMapper();
		$objectArray = $objectMapper->findByClassId($icfConfig->cfg_image_class_id);	
	
		return $this->listFiles($objectArray, "IMAGE", $icfConfig->cfg_site_imagePathFs);
	}	
	
	/**
	 * List media files in the media dir
	 * @return Array - array of file objects
	 */
	function listMedia()
	{
		$icfConfig = new IcfConfig();
		
		$objectMapper = new ObjectMapper();
		$objectArray = $objectMapper->findByClassId($icfConfig->cfg_media_class_id);	
	
		return $this->listFiles($objectArray, "MEDIA", $icfConfig->cfg_site_mediaPathFs);
	}
	
	/**
	 * List files for a type of media
	 *
	 * @param $objectArray Array - array of objects belonging to a media class
	 * @param $pathAttributeName String - unique name of the attribute that holds the HTTP path to the file
	 * @param $imagePathFs String - Filesystem path stated in the configuration file
	 * @return Array - File objects array
	 */
	function listFiles($objectArray, $pathAttributeName, $imagePathFs)
	{			
		$fileArray = array();
		foreach($objectArray as $object)
		{
			$file = new File($object, $pathAttributeName, $imagePathFs);			
			array_push($fileArray, $file);
		}
		
		return $fileArray;
	}

}
?>