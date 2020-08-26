<?php
  /** A PHP class to access MySQL database with convenient methods
    * in an object oriented way, and with a powerful debug system.\n
    * Licence:  LGPL \n
    * Web site: http://slaout.linux62.org/
    * @version  1.0
    * @author   S&eacute;bastien Lao&ucirc;t (slaout@linux62.org)
    */
	
  class DB {
    /** Put this variable to true if you want ALL queries to be debugged by default:
      */
    var $defaultDebug = false;

    /** INTERNAL: The start time, in miliseconds.
      */
    var $mtStart;
    /** INTERNAL: The number of executed queries.
      */
    var $nbQueries;
    /** INTERNAL: The last result ressource of a query().
      */
    var $lastResult;

    /** 
			* Connect to a MySQL database to be able to use the methods below.
      */
    public function __construct($database, $hostname, $username, $password, $port = '3306') {
      $this->mtStart    = $this->getMicroTime();
      $this->nbQueries  = 0;
      $this->lastResult = NULL;
			$this->connection = new mysqli($hostname, $username, $password, $database, $port);
      //$this->con = mysqli_connect($server, $user, $pass, $base) or die('Server connexion not possible.');
      //mysqli_select_db($base)               or die('Database connexion not possible.');
    }
		
		public function __destruct() {
			$this->close();
	 	}
		
    /**
		 	* Escape Values
		 	*/
		function escape($string) {
			if($string!='' || !empty($string)) {
				return $this->connection->escape_string($string);
			}
		}
		
    /** Query the database.
      * @param $query The query.
      * @param $debug If true, it output the query and the resulting table.
      * @return The result of the query, to use with fetchNextObject().
      */
		function queryxxx($sql, $debug = -1) {
      //$this->nbQueries++;
      //$this->lastResult = $this->con->query($query) or $this->debugAndDie($query);
      //$this->debug($debug, $query, $this->lastResult);
			$query = $this->connection->query($sql);
			if (!$this->connection->errno) {
				if ($query instanceof \mysqli_result) {
					return $query;
				} else {
					return true;
				}
			} else {
				throw new \Exception('Error: ' . $this->connection->error  . '<br />Error No: ' . $this->connection->errno . '<br />' . $sql);
			}
    }
		
    function query($sql, $debug = -1) {
      $this->nbQueries++;
      $this->lastResult = $this->connection->query($sql) or $this->debugAndDie($sql);
      $this->debug($debug, $sql, $this->lastResult);
      return $this->lastResult;
    }
    /** Do the same as query() but do not return nor store result.\n
      * Should be used for INSERT, UPDATE, DELETE...
      * @param $sql The sql statement.
      * @param $debug If true, it output the query and the resulting table.
      */
    function execute($sql, $debug = -1) {
      $this->nbQueries++;
      $this->connection->query($sql) or $this->debugAndDie($sql);
      $this->debug($debug, $sql);
    }
    /** Convenient method for mysql_fetch_object().
      * @param $result The ressource returned by query(). If NULL, the last result returned by query() will be used.
      * @return An object representing a data row.
      */
    function fetchNextObject($result = NULL) {
      if ($result == NULL)
        $result = $this->lastResult;

      if ($result == NULL || $result->num_rows < 1)
        return NULL;
      else
        return $result->fetch_object();
    }
    /** Get the number of rows of a query.
      * @param $result The ressource returned by query(). If NULL, the last result returned by query() will be used.
      * @return The number of rows of the query (0 or more).
      */
    function numRows($result = NULL) {
      if ($result == NULL)
        return mysqli_num_rows($this->lastResult);
      else
        return $result->num_rows;
    }
    /** Get the result of the query as an object. The query should return a unique row.\n
      * Note: no need to add "LIMIT 1" at the end of your query because
      * the method will add that (for optimisation purpose).
      * @param $query The query.
      * @param $debug If true, it output the query and the resulting row.
      * @return An object representing a data row (or NULL if result is empty).
      */
    function queryUniqueObject($sql, $debug = -1) {
      $sql = "$sql LIMIT 1";
      $this->nbQueries++;
      $result = $this->connection->query($sql) or $this->debugAndDie($sql);
      $this->debug($debug, $sql, $result);
      return $result->fetch_object();
    }
    /** Get the result of the query as value. The query should return a unique cell.\n
      * Note: no need to add "LIMIT 1" at the end of your query because
      * the method will add that (for optimisation purpose).
      * @param $query The query.
      * @param $debug If true, it output the query and the resulting value.
      * @return A value representing a data cell (or NULL if result is empty).
      */
    function queryUniqueValue($sql, $debug = -1) {
      $sql = "$sql LIMIT 1";
      $this->nbQueries++;
      $result = $this->connection->query($sql) or $this->debugAndDie($sql);
      $line = $result->fetch_row();
      $this->debug($debug, $sql, $result);
      return $line[0];
    }
    /** Get the maximum value of a column in a table, with a condition.
      * @param $column The column where to compute the maximum.
      * @param $table The table where to compute the maximum.
      * @param $where The condition before to compute the maximum.
      * @return The maximum value (or NULL if result is empty).
      */
    function maxOf($column, $table, $where) {
      return $this->queryUniqueValue("SELECT MAX(`$column`) FROM `$table` WHERE $where");
    }
    /** Get the maximum value of a column in a table.
      * @param $column The column where to compute the maximum.
      * @param $table The table where to compute the maximum.
      * @return The maximum value (or NULL if result is empty).
      */
    function maxOfAll($column, $table) {
      return $this->queryUniqueValue("SELECT MAX(`$column`) FROM `$table`");
    }
    /** Get the count of rows in a table, with a condition.
      * @param $table The table where to compute the number of rows.
      * @param $where The condition before to compute the number or rows.
      * @return The number of rows (0 or more).
      */
    function countOf($table, $where) {
      return $this->queryUniqueValue("SELECT COUNT(*) FROM `$table` WHERE $where");
    }
    /** Get the count of rows in a table.
      * @param $table The table where to compute the number of rows.
      * @return The number of rows (0 or more).
      */
    function countOfAll($table) {
      return $this->queryUniqueValue("SELECT COUNT(*) FROM `$table`");
    }
    /** Internal function to debug when MySQL encountered an error,
      * even if debug is set to Off.
      * @param $query The SQL query to echo before diying.
      */
    function debugAndDie($sql) {
      $this->debugQuery($sql, "Error");
      die("<p style=\"margin: 2px;\">".$this->connection->error."</p></div>");
    }
    /** Internal function to debug a MySQL query.\n
      * Show the query and output the resulting table if not NULL.
      * @param $debug The parameter passed to query() functions. Can be boolean or -1 (default).
      * @param $query The SQL query to debug.
      * @param $result The resulting table of the query, if available.
      */
    function debug($debug, $sql, $result = NULL) {
      if ($debug === -1 && $this->defaultDebug === false)
        return;
      if ($debug === false)
        return;

      $reason = ($debug === -1 ? "Default Debug" : "Debug");
      $this->debugQuery($sql, $reason);
      if ($result == NULL)
        echo "<p style=\"margin: 2px;\">Number of affected rows: ".$this->connection->affected_rows."</p></div>";
      else
        $this->debugResult($result);
    }
    /** Internal function to output a query for debug purpose.\n
      * Should be followed by a call to debugResult() or an echo of "</div>".
      * @param $query The SQL query to debug.
      * @param $reason The reason why this function is called: "Default Debug", "Debug" or "Error".
      */
    function debugQuery($sql, $reason = "Debug")
    {
      $color = ($reason == "Error" ? "red" : "orange");
      echo "<div style=\"border: solid $color 1px; margin: 2px;\">".
           "<p style=\"margin: 0 0 2px 0; padding: 0; background-color: #DDF;\">".
           "<strong style=\"padding: 0 3px; background-color: $color; color: white;\">$reason:</strong> ".
           "<span style=\"font-family: monospace;\">".htmlentities($sql)."</span></p>";
    }
    /** Internal function to output a table representing the result of a query, for debug purpose.\n
      * Should be preceded by a call to debugQuery().
      * @param $result The resulting table of the query.
      */
    function debugResult($result)
    {
      echo "<table border=\"1\" style=\"margin: 2px;\">".
           "<thead style=\"font-size: 80%\">";
      $numFields = $result->field_count;
      // BEGIN HEADER
      $tables    = array();
      $nbTables  = -1;
      $lastTable = "";
      $fields    = array();
      $nbFields  = -1;
      while ($column = $result->fetch_field()) {
        if ($column->table != $lastTable) {
          $nbTables++;
          $tables[$nbTables] = array("name" => $column->table, "count" => 1);
        } else {
          $tables[$nbTables]["count"]++;
				}
        $lastTable = $column->table;
        $nbFields++;
        $fields[$nbFields] = $column->name;
      }
      for ($i = 0; $i <= $nbTables; $i++)
        echo "<th colspan=".$tables[$i]["count"].">".$tables[$i]["name"]."</th>";
      echo "</thead>";
      echo "<thead style=\"font-size: 80%\">";
      for ($i = 0; $i <= $nbFields; $i++)
        echo "<th>".$fields[$i]."</th>";
      echo "</thead>";
      // END HEADER
      while ($row = $result->fetch_array()) {
        echo "<tr>";
        for ($i = 0; $i < $numFields; $i++)
          echo "<td>".htmlentities($row[$i])."</td>";
        echo "</tr>";
      }
      echo "</table></div>";
      $this->resetFetch($result);
    }
    /** Get how many time the script took from the begin of this object.
      * @return The script execution time in seconds since the
      * creation of this object.
      */
    function getExecTime()
    {
      return round(($this->getMicroTime() - $this->mtStart) * 1000) / 1000;
    }
    /** Get the number of queries executed from the begin of this object.
      * @return The number of queries executed on the database server since the
      * creation of this object.
      */
    function getQueriesCount()
    {
      return $this->nbQueries;
    }
    /** Go back to the first element of the result line.
      * @param $result The resssource returned by a query() function.
      */
    function resetFetch($result)
    {
      if ($result->num_rows > 0)
        $result->data_seek();
    }
    /** Get the id of the very last inserted row.
      * @return The id of the very last inserted row (in any table).
      */
    function lastInsertedId()
    {
      return $this->connection->insert_id;
    }
	/** Get the row was affected of the last inserted, update, delete row.
      * @return The no of the last inserted row (in any table).
      */
    function lastAffectedRows()
    {
      	return $this->connection->affected_rows;
    }
    /** Close the connexion with the database server.\n
      * It's usually unneeded since PHP do it automatically at script end.
      */
    function close()
    {
      $this->connection->close();
    }

    /** Internal method to get the current time.
      * @return The current time in seconds with microseconds (in float format).
      */
    function getMicroTime()
    {
      list($msec, $sec) = explode(' ', microtime());
      return floor($sec / 1000) + $msec;
    }
   function insertExist($table,$field,$value, $debug = -1) {

      $this->nbQueries++;
       $row = $this->connection->query("SELECT * FROM $table WHERE $field='$value';"); 
       $result=$row->fetch_object();
       if($result){
       return $result->id;     
       }else{
       
        

     $id= $this->connection->query("INSERT INTO $table ($field,created_at) VALUES ('$value',NOW());") or $this->debugAndDie("INSERT INTO $table ($field,created_at) VALUES ('$value',NOW());");
      $this->debug($debug,"INSERT INTO $table ($field,created_at) VALUES ('$value',NOW());");
    return $this->connection->insert_id;
    
    }
  
    }
    /*
      The primary obective of the above function is insert a row if a perticular value does not appear in the table or if it exist then return the ID of the row that matches the perticular value this method is only effective to thoes tables which has only one relevent field    

    */

    function bothInsertExist($table,$field1,$field2,$value1,$value2, $debug = -1){
       $sql="SELECT * FROM $table  where $field1 ='$value1' AND $field2 ='$value2';";
       $row = $this->connection->query($sql); 
       $result=$row->fetch_object();
       if($result){
       return $result->id;     
       }else{
         $qry = "INSERT INTO $table ($field1,$field2,created_at) VALUES ('$value1','$value2',NOW())";
        $id= $this->connection->query($qry) or $this->debugAndDie($qry);
      $this->debug($debug,$qry);
       }
    }
    /*
    the primary objective of the above function is to establish one to one reletion between two tables it checkes wether a row exist with both the primary key of respected tables are present or else the value would be inserted  
    */     


  } // class DB
?>
