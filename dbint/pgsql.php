<?
# Need some tools for pgsql 
require("db-tools.php");

###############################################################################
# pgsql : DB INTERFACE 
###############################################################################

# name: rdbms_pconnect
#   
# arguments: hostname, username, password, database name
# returns: database handle
#   
# desc: creates a persistent connection to the database backend
#
# notes: currently malfunctioning for pgsql
function rdbms_pconnect ($DBHOST, $DBUSER, $DBPASSWORD, $DBNAME) {
	$db = pg_pconnect("host=$DBHOST dbname=$DBNAME user=$DBUSER password=$DBPASSWORD");
	return $db;
}

# name: rdbms_connect
#   
# arguments: hostname, username, password, database name
# returns: database handle
#   
# desc: creates a connection to the database backend
#
# notes: Password may be empty.  If a hostname is not supplied, the
# notes: localhost is accessed via file sockets instead of network sockets.
# notes: Argument order is important, if changed, the default database that
# notes: is used for the connection may default to the username as well.
function rdbms_connect ($DBHOST, $DBUSER, $DBPASSWORD, $DBNAME) {
	if ($DBHOST) {
		$db = pg_connect("host=$DBHOST dbname=$DBNAME user=$DBUSER password=$DBPASSWORD");
	} else {
		$db = pg_connect("dbname=$DBNAME user=$DBUSER password=$DBPASSWORD");
	}
	return $db;
}

# name: rdbms_close
#   
# arguments: database handle
#   
# desc: closes the open database handle
function rdbms_close($db) {
	pg_close($db);
}

# name: rdbms_query
#   
# arguments: sql query, database handle
# returns: result handle
#   
# desc: sends a sql query to the database backend
#
# notes: if the result fails, the result handle returns
# notes: NULL which needs to be fed back to gcdb as 0.
# notes: @ added to suppress any error messages.
# notes: '' needs to be converted to NULL
function rdbms_query($query, $db) {
        $query=ereg_replace("''", "NULL", $query);
	$result = @pg_exec($db, $query);
        pdebug("PG: R[$result] Q[$query] D[$db]",0);
	if (!$result) {
		# if no result is returned, then there was a failure!
		$result = 0;
	}
        # new result, reset result pointer to first row
	$nrow = next_pg_row(-1,$result);
	return $result;
}

# name: rdbms_fetch_array
#
# arguments: result handle
# results: hash or NULL if now more result rows are remaining
#
# desc: takes the next result from the result handle and
# desc: returns a hach for the row
#
# notes: pgsql accesses all the rows directly by an integer.
# notes: this function increments a static integer pointing
# notes: into the result handle.
function rdbms_fetch_array($result) {
	# increment row counter for result
	$nrow = next_pg_row(0,$result);
	$row = "";
	if (pg_numrows($result)>$nrow) {
        	$row = pg_fetch_array($result,$nrow);
		# translate lowercase field names to case-sensitive names
		$row = db_tools_fixfields($row);
	}
	return $row;
}

# name: rdbms_num_rows
#
# arguments: result handle
# returns: number of rows in result
#
# desc: returns the number of rows in a result handle
function rdbms_num_rows($result) {
	$count = pg_numrows($result);
	return $count;
}

# name: rdbms_db_error
#
# arguments: database handle 
# returns: error string
#
# desc: returns the error string if there is an error, else
# desc: returns an empty string
function rdbms_db_error($db) {
        return pg_errormessage($db);
}

# name: rdbms_insert_id
#   
# arguments: result handle, table name, field name, database handle
# returns: the incremented id created for the last insert performed
#   
# desc: returns the last id created for an insert call
#
# notes: this is a kludge to match the mysql counterpart.
function rdbms_insert_id($result, $table, $field, $db) {
	$oid = pg_getlastoid($result);
	$res = rdbms_query("SELECT $field FROM $table WHERE OID=$oid",$db);
	$row = rdbms_fetch_array($res,0);
	$row = db_tools_fixfields($row);
	$num = $row[$field];
	return $num;
}

# name: next_pg_row 
#
# arguments: integer flag; 0(increment), -1(reset), result handle
# returns: integer row pointer to result handle
#
# desc: this is a row pointer into the result handle
# desc: used to mimic the operation of mysql_fetch_array.
# desc: result handle is used to make a hash out of the pointer
function next_pg_row($iflag, $result) {
	static $pg_row;
	if ($iflag==-1) {  # reset counter
		$pg_row[$result]=-2;
	}
	$pg_row[$result]++;
	return $pg_row[$result];
}
?>
