<?php 
/**
 * 
 */
class Database 
{
	
	function __construct()
	{
		$db_user = 'root';			//DB USERNAME
$db_password = '';			//DB PASSWORD 
$db_name = 'employee';		//DB NAME
$db_host = 'localhost';		//DB SERVER
// Include the class:
include_once "ezsql/shared/ez_sql_core.php";
include_once "ezsql/mysqli/ez_sql_mysqli.php";
$db = new ezSQL_mysqli($db_user,$db_password,$db_name,$db_host);
	}

	function Insert($table,$fields,$values){
		$db_user = 'root';			//DB USERNAME
$db_password = '';			//DB PASSWORD 
$db_name = 'employee';		//DB NAME
$db_host = 'localhost';		//DB SERVER
// Include the class:
include_once "ezsql/shared/ez_sql_core.php";
include_once "ezsql/mysqli/ez_sql_mysqli.php";
$db = new ezSQL_mysqli($db_user,$db_password,$db_name,$db_host);
		$statement='';
	    $statement="INSERT INTO ".$table."(".$fields.") VALUES (".$values.");";
	    $res=$db->query($statement);
	    return $res;

	}
     function login($email,$password){
     	$db_user = 'root';			//DB USERNAME
$db_password = '';			//DB PASSWORD 
$db_name = 'employee';		//DB NAME
$db_host = 'localhost';		//DB SERVER
// Include the class:
include_once "ezsql/shared/ez_sql_core.php";
include_once "ezsql/mysqli/ez_sql_mysqli.php";
$db = new ezSQL_mysqli($db_user,$db_password,$db_name,$db_host);
     	$hashPassword=hashPassword($password);
     	$row = $db->get_row("SELECT * FROM tbluser   WHERE email='".$email."' AND password='".$hashPassword."' AND status=1 ");

            if($row) {
             return $row;   
            }else{
            	return FALSE;
            }    
     	
     }
}
?>