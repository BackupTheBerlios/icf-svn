<?php
/**
* @copyright (C) 2005 Carlos Rubén Jacobs
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* IDEA Content Framework is Free Software
*/

require_once "controllers/loginController.php";
require_once "icfHorizontal.php";

$icfTemplating = new IcfTemplating();
new LoginController($_REQUEST["method"], $icfTemplating);
?>