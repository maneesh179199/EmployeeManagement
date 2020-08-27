<?php
error_reporting(E_ALL);
date_default_timezone_set('Asia/Calcutta');
define("DIR",__DIR__);
define("HTTP",'http://localhost/EmployeeManagement');
define("HTTPS",'https://localhost/EmployeeManagement');
define('UPLOADS', DIR.'/upload/');

// define constant of the projects
$db_user = 'root';			//DB USERNAME
$db_password = '';			//DB PASSWORD 
$db_name = 'employee';		//DB NAME
$db_host = 'localhost';		//DB SERVER
// Include the class:
include_once "includes/ezsql/shared/ez_sql_core.php";
include_once "includes/ezsql/mysqli/ez_sql_mysqli.php";
$db = new ezSQL_mysqli($db_user,$db_password,$db_name,$db_host);

include(DIR."/includes/class.common.php");
include(DIR."/includes/class.mail.php");

define('SITENAME','Vidhipatra');
$http = HTTP; //HOST NAME
$meta['title'] = '';
$meta['description'] = '';
$meta['keywords'] = '';
$site['name'] = SITENAME;
//include('includes/class.common.php');
//$setting = $db->queryUniqueObject("SELECT * FROM tblsetting ORDER BY id ASC");
//$website=$db->get_row("SELECT * FROM tblsetting WHERE id='1' ");
?>