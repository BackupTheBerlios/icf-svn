<?php
/**
* @copyright (C) 2005 Carlos Rub�n Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

require_once "controllers/addFolderController.php";
require_once "icfHorizontal.php";

$addFolderController = new AddFolderController();
$addFolderController->execute();
?>