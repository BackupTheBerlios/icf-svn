<?php

require_once "service/mediaService.php";

$mediaService = new MediaService();
$fileArray = $mediaService->listImages();

echo "Files in images dir: ";
print_r($fileArray);
?>