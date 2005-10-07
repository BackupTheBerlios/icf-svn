<?php

require_once 'icfConfig.php';
require_once 'adodb/adodb.inc.php';

/**
 * Database abstraction class. It allows to execute queries to a database in a vendor
 * independent manner.
 */
class IcfDatabase
{
	// Configuration parameters
	var $cfg_db_connectionString;
	var $cfg_db_debug;
	var $cfg_db_tableprefix;
	
	/**
	 * Constructs a IcfDatabase object
	 */
	function IcfDatabase()
	{
		// Get config parameters
		$icfConfig = new IcfConfig();
		
		$this->cfg_db_debug = $icfConfig->cfg_db_debug;
		$this->cfg_db_connectionString = $icfConfig->cfg_db_connectionString;
		$this->cfg_db_tableprefix = $icfConfig->cfg_db_tableprefix;
	}
	
	/**
	 * Opens a database connection and returns it
	 * @access private
	 * @return a database connection
	 */
	function& dbOpen()
	{
		$connection = ADONewConnection($this->cfg_db_connectionString);		
		$connection->debug = $this->cfg_db_debug; 
		return $connection;		
	}

	/**
	 * Closes a given database connection
	 * @access private
	 * @param $connection Database connection to close
	 */
	function dbClose(&$connection)
	{
		$connection->Close();
	}

	/**
	 * Executes a given query
	 * @param $query given query, in a string
	 * @param $numrows maximum number of rows
	 * @param $offset lower limit that is used to start fetching rows, useful for pagination.
	 * @return a recordset object
	 */
	function& dbQuery($query, $numrows=-1, $offset=-1)
	{
		$connection = $this->dbOpen();
		$connection->SetFetchMode(ADODB_FETCH_ASSOC);
		$query = str_replace("##", $this->cfg_db_tableprefix, $query);

		if ($numrows==-1)
			$rs = $connection->Execute($query);
		else	
			$rs = $connection->SelectLimit($query, $numrows, $offset);
		
		$this->dbClose($connection);
		return $rs;
	}
	
		/**
	 * Executes a given query
	 * @param $query given query, in a string
	 * @param $connection connection  - To include the query in a transaction context
	 * @param $numrows maximum number of rows
	 * @param $offset lower limit that is used to start fetching rows, useful for pagination.
	 * @return a recordset object
	 */
	function& dbQueryInTx($query, &$connection, $numrows=-1, $offset=-1)
	{
		$connection->SetFetchMode(ADODB_FETCH_ASSOC);
		$query = str_replace("##", $this->cfg_db_tableprefix, $query);

		if ($numrows==-1)
			$rs = $connection->Execute($query);
		else	
			$rs = $connection->SelectLimit($query, $numrows, $offset);
		
		return $rs;
	}
		
	/**
	 * Executes an insert / update / delete query
	 *
	 * @param $query string with the DML
	 * @return if the $query is an insert into and identity table, returns the new id
	 */
	function& dbExecute($query)
	{
		$insertID = -999999;

		$connection = $this->dbOpen();
		$query = str_replace("##", $this->cfg_db_tableprefix, $query);
		$rs = $connection->Execute($query);
	
		$ini = strtolower(substr($query, 0, 6));
		if ($ini == "insert")
			$insertID = $connection->Insert_ID();
		
		$this->dbClose($connection);

		if (!($insertID == -999999))
			return $insertID;
	}

	/**
	 * It executes an insert / update / delete inside a transaction, using a given connection
	 *
	 * @param $query string containing the query
	 * @param $connection Connection containing the transaction
	 * @return new id, following the same practice that dbExecute.
	 */
	function& dbExecuteInTx($query, &$connection)
	{
		$insertID = -999999;

		$query = str_replace("##", $this->cfg_db_tableprefix, $query);
		$rs = $connection->Execute($query);
	
		$ini = strtolower(substr($query, 0, 6));
		if ($ini == "insert")
			$insertID = $connection->Insert_ID();
		
		if (!($insertID == -999999))
			return $insertID;
	}	
}

?>