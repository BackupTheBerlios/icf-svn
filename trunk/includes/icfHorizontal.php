<?php
// Contains all the ICF general code that must be included in all pages of the system.
// Edit this file to alter the horizontal behavior of the system.

// Report every error
// error_reporting(E_ALL);
// System configuration
require_once "icfConfig.php";
// Session features
require_once "icfSession.php";
// Filters
require_once "filters/sessionFilter.php";
// Templating framework abstractio
require_once "icfTemplating.php";
// Database access
require_once "icfDatabase.php";

?>