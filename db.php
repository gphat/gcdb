<?
# Obtain global settings for gcdb
require("gcdb-settings.php");

# This program calls abstract DB interfaces

if(!(extension_loaded($RDBM))) {
	echo "<pre>PHP has these loaded extensions:\n";
	print_r (get_loaded_extensions());
        echo "</pre>";
	die ("Support not enabled for $RDBM, <b>gcdb</b> cannot run.\n");
}

# Install the proper DB hooks 
require("dbint/$RDBM.php");

###############################################################################
# DB ABSTRACT INTERFACE TO GCDB API
###############################################################################

# name: getDBConnection
#
# arguments: none
# returns: database handle
#
# desc: I did this so the DB could easily be changed
function getDBConnection () {
	global $PERSISTENT, $DBHOST, $DBUSER, $DBPASSWORD, $DBNAME;

	if($PERSISTENT == "On") {
		$db = rdbms_pconnect($DBHOST, $DBUSER, $DBPASSWORD, $DBNAME);
	} else {
		$db = rdbms_connect($DBHOST, $DBUSER, $DBPASSWORD, $DBNAME);
	}
	checkDBError($db);
	return $db;
}

# name: DBquery
#
# arguments: connection, SQL query
# returns: Result set for select, or 0/1 for everything else
#
# desc: Sends a query to the database
function DBquery($query,$db) {
	$result = rdbms_query($query,$db);
	return $result;
}

# name: DBfetch_array
#
# arguments: Result set
# returns: associative array
#
# desc: Creates an associative array from a result
function DBfetch_array($result) {
	$row = rdbms_fetch_array($result);
	return $row;
}

# name: DBnum_rows
#
# arguments: Result handle
# returns: Number of rows in the result
#
# Returns the number of rows in a result
function DBnum_rows($result) {
	$count = rdbms_num_rows($result);
	return $count;
}

# name: checkDBError
#
# arguments: database handle
# returns: String containing DB Error number and Error String
#
# desc: Checks for an error with the DB and prints the error if it exists
function checkDBError($db) {
	if (!$db) {
		die ("Need a valid database connection!<br>\n");
        }
	$test = rdbms_db_error($db);
	if($test != "") {
		echo "$test<br>";
	}
}

# name: DBinsert_id
#
# arguments: result handle, table name, field name, database handle
# returns: The ID that was assigned by the last insert statement
#
# desc: Returns the ID given to the last insert
function DBinsert_id($result, $table, $field, $db) {
	$num = rdbms_insert_id($result, $table, $field, $db);
	return $num;
}

# name: DBReport
#
# arguments: Result set, Title of action, URL to return to, Text for return link,
#             database handle
# returns: HTML table for showing the result of an update
#
# desc: Uses the PrettyTable functions to generate a table detailing what happened
function DBReport ($result, $title, $return_url, $return_link, $db) {
	global $lUpdateSuccess, $lUpdateFailed;
	if(!(is_integer($result)) or ($result == 1)) {
		beginPrettyTable("1", $title);
		echo "<tr>\n";
		echo " <td><div class=data>$lUpdateSuccess <a href='$return_url'>$return_link</a></div></td>\n";
		echo "</tr>\n";
		endPrettyTable();
	} else {
		beginPrettyTable("1", $title);
		echo "<tr> <td><div class=data>$lUpdateFailed: ";
		echo checkDBError($db);
		echo "</div></td></tr>\n";
		endPrettyTable();
	}
}

?>
