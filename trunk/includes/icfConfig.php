<?php

/**
 * Holds configuration parameters.
 * The present implementation obligues the user to edit the strings in the class.
 */
class IcfConfig
{
	//-------------------------------------------------------------------------
	// Database configuration section
	//-------------------------------------------------------------------------
	var $cfg_db_connectionString;	// This is ADODB connection string 
	var $cfg_db_debug; 						// if true, it shows all sql queries executed
	var $cfg_db_tableprefix;			// Do not change unless you need to!
	
	//-------------------------------------------------------------------------
	// Paths configuration section
	//-------------------------------------------------------------------------
	
	var $cfg_site_feBaseUrl; 					// Front end base URL
	var $cfg_site_beBaseUrl;	 				// Back end base URL
	var $cfg_site_beTemplateUrl; 			// Back end base URL
	
	var $cfg_site_feBasePath; 				// Front end base Path
	var $cfg_site_beBasePath; 				// Back end base Path
	var $cfg_site_beTemplatePath; 		// Backend template path

	var $cfg_site_imagePathFs;				// Filesystem image path
	var $cfg_site_imagePathUrl;				// URL image path
	var $cfg_image_folder_id;					// Image folder id
	var $cfg_image_class_id;					// Image class id
	var $cfg_image_extensions;				// Allowed Image extensions
	
	var $cfg_site_mediaPathFs;				// Filesystem media path
	var $cfg_site_mediaPathUrl;				// URL media path
	var $cfg_media_folder_id;					// Media folder id
	var $cfg_media_class_id;					// Media class id
	var $cfg_media_extensions;				// Allowed Media extensions

	//-------------------------------------------------------------------------
	// Locale configuration section
	//-------------------------------------------------------------------------
	var $cfg_locale_active;			// Active locale
	var $cfg_date_format_class;	// Options: "StandardDateFormat", "UsaDateFormat", "IsoDateFormat"
	
	//-------------------------------------------------------------------------
	// Special pages
	//-------------------------------------------------------------------------
	var $cfg_page_login;				// Login page
	
	//-------------------------------------------------------------------------
	// IDEA Content Frameword data section
	//-------------------------------------------------------------------------
	var $cfg_icf_version;

	/**
	 * Configuration constructor
	 */
	function IcfConfig()
	{			
		$this->cfg_db_connectionString = 'mysqlt://root:@localhost/icfKairos';
		$this->cfg_db_debug = false;
		$this->cfg_db_tableprefix = 'icf';
				
		$this->cfg_site_feBaseUrl = 'http://localhost/icf';
		$this->cfg_site_beBaseUrl = 'http://localhost/icf/admin';
		$this->cfg_site_beTemplateUrl = 'http://localhost/icf/admin/core/templates/default';

		$this->cfg_site_feBasePath = 'C:\AppServ\www\icf';
		$this->cfg_site_beBasePath = 'C:\AppServ\www\icf\admin';

		$this->cfg_site_imagePathFs = $this->cfg_site_feBasePath . '\images';
		$this->cfg_site_imagePathUrl = $this->cfg_site_feBaseUrl . '\images';
		$this->cfg_image_folder_id = "26";
		$this->cfg_image_class_id = "11";
		$this->cfg_image_extensions = array("ico", "gif", "jpg", "jpeg");
		
		$this->cfg_site_mediaPathFs = $this->cfg_site_feBasePath . '\media';
		$this->cfg_site_mediaPathUrl = $this->cfg_site_feBaseUrl . '\media';
		$this->cfg_media_folder_id = "27";
		$this->cfg_media_class_id = "12";
		$this->cfg_media_extensions = array("mp3", "mp2", "mid", "doc");
		
		$this->cfg_site_beTemplatePath = $this->cfg_site_beBasePath . '\core\templates\default';
		
		$this->cfg_locale_active = 'es-AR';
		$this->cfg_date_format_class = "StandardDateFormat";
		
		$this->cfg_page_login = "/icf/admin/core/login.php";
		
		$this->cfg_icf_version = "0.5"; 
	}
}

?>