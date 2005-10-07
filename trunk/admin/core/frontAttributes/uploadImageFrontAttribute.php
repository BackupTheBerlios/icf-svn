<?php

/**
 * Implements a File attribute that allows to upload image data
 *
 * @author despada
 */
class UploadImageFrontAttribute extends UploadFileFrontAttribute
{
	/**
	 * Constructs a UploadImageFrontAttribute. See FrontAttribute constructor reference for details.
	 */
	function UploadImageFrontAttribute($language, $variant)
	{
		$icfConfig = new IcfConfig();		
		
		// Super constructor
		$this->UploadFileFrontAttribute($language, $variant, $icfConfig->cfg_site_imagePathFs, $icfConfig->cfg_site_imagePathUrl, $icfConfig->cfg_image_extensions);
	}
}

?>