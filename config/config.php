<?php
set_time_limit(0);
error_reporting(-1);
ini_set("display_errors", "1");
ini_set("log_errors", "1");
ini_set("memory_limit", "-1");
mb_detect_order("ASCII,UTF-8,ISO-8859-1");
date_default_timezone_set("America/Bogota");
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Cache-control: private', false); // IE 6 FIX
header('Pragma: no-cache');
ini_set("include_path", ini_get("include_path").PATH_SEPARATOR.realpath(dirname(__file__).'/..').PATH_SEPARATOR.realpath(dirname(__file__).'/../lib'));
require_once "pdo_database.php";
//CONSTANTS

define("DATABASE", "trocenapp"); //Nombre de la Base de datos

$con = new wArLeY_DBMS("mysql", "localhost", DATABASE, "root", "123456", ""); 
$dbCN = $con->Cnxn();//This step is really neccesary for create connection to database, and getting the errors in methods.
if($dbCN==false) die("Error: Cant connect to database.");
echo $con->getError();//Show error description if exist, else is empty.

foreach(glob("lib/*.php") as $fileName)
   require_once $fileName;
?>