<?php 
/**
 * 
 */
class Database 
{
	   private $db_user = 'root';			//DB USERNAME
       private $db_password = '';			//DB PASSWORD 
       private $db_name = 'employee';		//DB NAME
       private $db_host = 'localhost';		//DB SERVER
	function __construct()
	{
     // Include the class:
     include_once "ezsql/shared/ez_sql_core.php";
     include_once "ezsql/mysqli/ez_sql_mysqli.php";
     $this->db = new ezSQL_mysqli($this->db_user,$this->db_password,$this->db_name,$this->db_host);
    }

	function Insert($table,$fields,$values){
		
		$statement='';
	    $statement="INSERT INTO ".$table."(".$fields.") VALUES (".$values.");";
	    $res=$this->db->query($statement);
	    return $res;

	}
     function login($email,$password){
     	
     	$hashPassword=hashPassword($password);
     	$row = $this->db->get_row("SELECT * FROM tbluser   WHERE email='".$email."' AND password='".$hashPassword."' AND status=1 ");

            if($row) {
             return $row;   
            }else{
            	return FALSE;
            }    
     	
     }
     function SelectAll($table){
     	$statement="SELECT * FROM ".$table;
     	$data=$this->db->get_results($statement);
     	if($data){
     		return $data;
     	}else{
     		return FALSE;
     	}
     }
}
?>