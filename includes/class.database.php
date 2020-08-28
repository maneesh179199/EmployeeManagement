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
     function exist($table,$field,$value){
     	$statement="SELECT * FROM ".$table." WHERE ".$field."='".$value."' ;";
     	$data=$this->db->get_row($statement);
     	if($data){
     		return $data;
     	}else{
     		return FALSE;
     	}
     }
     function selectFieldsWithId($table,$field){
     	$statement="SELECT id,".$field." FROM ".$table." ;";
     	$data=$this->db->get_results($statement);
     	if($data){
     		return $data;
     	}else{
     		return FALSE;
     	}
     }
     function selectOneJoin($table1,$table2,$joinfield,$selectcoloumn){
     	$statement="SELECT t1.*,t2.".$selectcoloumn." FROM ".$table1." t1 JOIN ".$table2." t2 ON t1.".$joinfield."= t2.id ;";
     	$data=$this->db->get_results($statement);
     	if($data){
     		return $data;
     	}else{
     		return FALSE;
     	}$id=$db->insert_id;
     }
     function insertId($table,$fields,$values){
		
		$statement='';
	    $statement="INSERT INTO ".$table."(".$fields.") VALUES (".$values.");";
	    $res=$this->db->query($statement);
	    $id=$this->db->insert_id;
	    return $id;

	}
    function updateFilePath($table,$field,$value,$id){
        $statement="UPDATE ".$table."SET ".$field."= '".$value."'WHERE id='".$id."'";
        $update=$this->db->query($statement);
        if($update){
            return TRUE;
        }else{
            return FAlse;
        }
    }
}
?>