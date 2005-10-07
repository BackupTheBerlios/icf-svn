<?php

// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");


// Generates an external list of images for TinyMCE

require_once "service/mediaService.php";

$mediaService = new MediaService();
$fileArray = $mediaService->listImages();
?>

var tinyMCEImageList = new Array
(
<?php
$separator = false;
foreach($fileArray as $file)
{
	/* @var $file File */
	if ($separator) echo ",";
	// Name, URL
	?>
	["<?php echo $file->getTitle()?>", "<?php echo $file->getHttpPath()?>"]
<?
	$separator = true;
}
?>
);