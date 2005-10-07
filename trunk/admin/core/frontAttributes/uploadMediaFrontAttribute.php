<?php

/**
 * Implements a File attribute that allows to upload media data
 *
 * @author despada
 */
class UploadMediaFrontAttribute extends UploadFileFrontAttribute
{
	/**
	 * Constructs a UploadImageFrontAttribute. See FrontAttribute constructor reference for details.
	 */
	function UploadMediaFrontAttribute($language, $variant)
	{
		$icfConfig = new IcfConfig();		
		
		echo "cfg_site_mediaPathFs: " . $icfConfig->cfg_site_mediaPathFs . "-";
		
		// Super constructor
		$this->UploadFileFrontAttribute($language, $variant, $icfConfig->cfg_site_mediaPathFs, $icfConfig->cfg_site_mediaPathUrl, $icfConfig->cfg_media_extensions);
	}
}

?>