<?
# Enter accounts displays a form that allows the entry of an account, and
# handles the submission
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lEnterAccount, $sess_user);

if(isset($package)) {
	$db = getDBConnection();
	$result = DBquery("INSERT INTO Accounts (CustomerID, Username, Password, Domain, DateOpened, DateClosed, LastDateBilled, Status, PackageGroupID) VALUES ('$CustomerID','$Username','$Password','$Domain','$DateOpened','$DateClosed','$LastBilled','$Status', '$package')", $db);
	DBReport($result, $lAccountAddition, "showprofile.php?CustomerID=$CustomerID", "$lCustomer $CustomerID", $db);
} else { 
	$db = getDBConnection();
	$result = DBquery("SELECT * FROM Configuration", $db);
	$myrow = DBfetch_array($result);	
	$pack_array = array();
	$result = DBquery("SELECT * FROM PackageGroup", $db);
        $nrow = DBnum_rows($result);
	# Check to see if we have any packages to see, if not, you have to create at
	# least one!
	if ($nrow == 0 ) {
		beginPrettyTable("2", $lEnterAccount);
		echo ("$lNoPackagesFound<br>");
		echo ("<a href='createpackage.php'>$lCreatePackage.</a>\n");
		endPrettyTable();
		endDocument();
		die();
        }
	while($pack_row = DBfetch_array($result)) {
		array_push($pack_array, $pack_row["PackageGroupID"]);
		array_push($pack_array, $pack_row["Description"]);
	}
	$Now = date("Y-m-d");
	 beginPrettyTable("2", $lEnterAccount);
	  openForm("enteraccount", $PHP_SELF);
	  makeHiddenField("CustomerID", $CustomerID);
	  makeStaticField($lCustomerID, $CustomerID);
	  makeArrayDropBox($lPackage, "package", "", $pack_array);
	  makeTextField($lUsername, "Username", "");
	  makeTextField($lPassword, "Password", "");
	  makeTextField($lDomain, "Domain", "");
	  makeTextField($lDateOpened, "DateOpened", $Now);
	  makeTextField($lDateClosed, "DateClosed", "");
	  makeTextField($lLastBilled, "LastBilled", "");
	  makeDropBox($lStatus, "Status", "Open", "Open", "Closed");
	  makeSubmitter();
	 endPrettyTable();
	closeForm();
}
endDocument();
?>
