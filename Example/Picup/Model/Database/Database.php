<?php
namespace Model;
class Database {
	private $mysqli = NULL;
	private $charset = "utf8";
	private $errorPrint = NULL;

	public function __construct(\View\PrintErrorMessage $PEM) {
		$this->errorPrint = $PEM;
	}

	
	/**
	 * Connects to the database using the information from DBConfig.
	 * @param DBConfig $config
	 * @return bool - true if no error occured.
	 */
	public function Connect (DBConfig $config) {
		$this->mysqli = new \mysqli($config->m_host,
									$config->m_user,
									$config->m_pass,
									$config->m_db);
									
		if ($this->mysqli->connect_error) {
			$this->errorPrint->PrintMessage("Connect failed: $this->mysqli->connect_error");
			return FALSE;
		}
		
		$this->mysqli->set_charset($this->charset);
		return TRUE;
	}
	
    /** Runs a SQL query 
 	 * 
     * Note this one is sensitive to SQL-Injections and should only be used with care
     * (no input from user or "real_escape_string")
     * 
     * @param string $sql a string containing an SQL-query
     * @return bool if successful
     */
    public function RunQuery($sql) {
		if ($this->mysqli == NULL) {
			throw new Exception("DBConnection, must call Connect before calling Prepare");
			return FALSE;;
		}
		    
		if ($this->mysqli->query($sql) === FALSE) {
			$this->errorPrint->PrintMessage("'$sql' failed " . $this->mysqli->error);	
			return FALSE;
		}
		return TRUE;
    }
	
	
    /** Returns all objects from a table
     * @param string $className Name of the class contained in table
     * @param string $tableName Name of table 
     * @return array of objects 
     */
    public function GetAllInstances($className, $sql) {
        if ($this->mysqli == NULL) {
            throw new Exception("DBConnection, must call Connect before calling Prepare");
			return FALSE;
        }
        $sql = $sql;
        $stmt = $this->Prepare($sql);

        if ($stmt === FALSE) {
			$this->errorPrint->PrintMessage("'$sql' failed " . $this->mysqli->error);	
            return FALSE;
        }
        
        return $this->RunAndFetchObjects($className, $stmt);
    }

	 /**
	 * Runs a SQL query as a prepared statement and returns objects 
	 * 
	 * @param string $className Name of Class in table 
	 * @param string $sqlQueryToPrepare a string containing an SQL-query with ? for the parameters to bind
	 * @param string $bindParamTypeString String containing type-letters for the prepared statement parameters like "ss" for double string
	 * @param array $parameterArray array of parameter references to be binded to the values
	 * @return array of objects 
	 */
    public function RunPreparedSelectQuery($className, $sqlQueryToPrepare, $bindParamTypeString, $parameterArray) {
        $stmt = $this->PrepareWithParams($sqlQueryToPrepare, $bindParamTypeString, $parameterArray);
        return $this->RunAndFetchObjects($className, $stmt);
    }
	
    /**
     * Runs a SQL query as a prepared statement and returns true or false 
     * 
     * @param string $sqlQueryToPrepare a string containing an SQL-query with ? for the parameters to bind
     * @param string $bindParamTypeString String containing type-letters for the prepared statement parameters like "ss" for double string
     * @param array $parameterArray array of parameter REFERENCES to be binded to the values ex. array(&$a_instance->First)
     * @return bool 
     */
    public function RunPreparedQuery($sqlQueryToPrepare, $bindParamTypeString, $parameterArray) {
        $stmt = $this->PrepareWithParams($sqlQueryToPrepare, $bindParamTypeString, $parameterArray);
    	$ret = $stmt->execute();
        $stmt->close();
        return $ret;
    }
	
    /** Takes a prepared statement and fetches all objects from it
     * @param string $className Name of the class contained in table
     * @return array of objects 
     */
    private function RunAndFetchObjects($className, $stmt) {
            
        $result = $stmt->execute();
        $ret = array();
        $result = $stmt->get_result();
        while ($object = $result->fetch_object($className))
		{
		    //NOTE! requires that we have a pk in the object not that obvious
		    $ret[$object->m_id] = $object;
		}
        $stmt->close();
        return $ret;
    }
	
    /**
     * @param string $sqlQueryToPrepare a string containing an SQL-query with ? for the parameters to bind
     * @param array $parameterArray array of parameter REFERENCES to be binded to the values
     * @param string $bindParamTypeString String containing type-letters for the prepared statement parameters like "ss" for double string
     * @return mysqli_stmt
     */
    private function PrepareWithParams($sqlQueryToPrepare, $bindParamTypeString, $parameterArray) {
        $stmt = $this->Prepare($sqlQueryToPrepare);
        
        if ($stmt === FALSE) {
			$this->errorPrint->PrintMessage("Prepare of '$sqlQueryToPrepare' failed " . $this->mysqli->error);	
			return FALSE;
        }
        
        $parameters = array_merge(array($bindParamTypeString), $this->makeValuesReferenced($parameterArray));
        if (call_user_func_array(array($stmt,"bind_param"), $parameters) === FALSE) {
			$this->errorPrint->PrintMessage("Bind_param failed " . $this->mysqli->error);	
			return FALSE;
        }
        
        return $stmt;
    }
    
    function makeValuesReferenced(&$arr) { 
		$refs = array(); 
		foreach($arr as $key => $value) 
		    $refs[$key] = &$arr[$key]; 
		return $refs; 
	}
	
	/**
	 * Prepares the sqlQuery
	 * @param $sql String sqlQuery
	 * @return mysqli_stmt
	 */
	 public function Prepare($sql) {
        if ($this->mysqli == NULL) {
            throw new Exception("DBConnection, must call Connect before calling Prepare");
			return FALSE;
        }
	 	$ret = $this->mysqli->prepare($sql);
		
		if ($ret == FALSE) {
			throw new \Exception($this->mysqli->error);			
		}
		return $ret;
	 }
	 
	 public function Close() {
	 	return $this->mysqli->close();
	 }
	 
	 public static function Test (DBConfig $dbConfig) {
	 	$db = new Database($dbConfig);
		
		if ($db->Connect($dbConfig) == FALSE) {
			echo "Database connect failed";
			return FALSE;
		}
		return TRUE;
	 }
}
?>