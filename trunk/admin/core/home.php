<?php
/**
* @copyright (C) 2005 Carlos Rub�n Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

require_once "controllers/homeController.php";
require_once "icfHorizontal.php";

$icfTemplating = new IcfTemplating();
new HomeController($_POST["method"], $icfTemplating);
?>