<?

###############################################################################
# mysql : DB INTERFACE
###############################################################################

# name: rdbms_pconnect
#
# arguments: hostname, username, password, database name
# returns: database handle 
#
# desc: establishes a connection, chooses the db, returns the handle
# desc: uses persistent connections
function rdbms_pconnect ($DBHOST, $DBUSER, $DBPASSWORD, $DBNAME) {
	$db = mysql_pconnect($DBHOST, $DBUSER, $DBPASSWORD);
	mysql_select_db($DBNAME, $db);
	return $db;
}

# name: rdbms_connect
#
# arguments: hostname, username, password, database name
# returns: database handle 
#
# desc: establishes a connection, chooses the db, returns the handle
function rdbms_connect ($DBHOST, $DBUSER, $DBPASSWORD, $DBNAME) {
	$db = mysql_connect($DBHOST, $DBUSER, $DBPASSWORD);
	mysql_select_db($DBNAME, $db);
	return $db;
}

# name: rdbms_close
#
# arguments: database handle
# returns: nothing 
#
# desc: closes the open database handle 
function rdbms_close($db) {
	mysql_close($db);
}

# name: rdbms_close
#
# arguments: sql query, database handle
# returns: result handle
#
# desc: ends an sql query to the database
function rdbms_query($query,$db) {
	$result = mysql_query($query);
	return $result;
}

# name: rdbms_fetch_array
#
# arguments: result handle 
# returns: hash
#
# desc: takes the next result from the set and returns a hash
# desc: for that row
function rdbms_fetch_array($result) {
	$row = mysql_fetch_array($result);
	return $row;
}

# name: rdbms_num_rows
#
# arguments: result handle 
# returns: number of rows in result
#
# desc: returns the number of rows in a result 
function rdbms_num_rows($result) {
	$count = mysql_num_rows($result);
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
	$test = mysql_errno();
        $estr = "";
        if($test != 0) {
                $estr=mysql_errno().":".mysql_error();
        }
	return $estr;
}

# name: rdbms_insert_id
#
# arguments: result handle, table name, field name, database handle 
# returns: the incremented id created for the last insert performed 
#
# desc: returns the last id created for an insert call 
function rdbms_insert_id($result, $table, $field, $db) {
	$num = mysql_insert_id($db);
	return $num;
}

?>
