<?
# Show Accounts displays all the info about an Account
session_start();

require("gcdb.php");
require("security/hybrid.php");

beginDocument($lShowAccounts, $sess_user);

$db = getDBConnection(); 

if ($AccountID) {
	$result = DBquery("SELECT * from Accounts where AccountID=$AccountID", $db);
	$myrow = DBfetch_array($result);

	$pack_id = $myrow["PackageGroupID"];
	$result = DBquery("SELECT * from PackageGroup where PackageGroupID=$pack_id", $db);
	$pack_row = DBfetch_array($result);

	beginPrettyTable("2", $lAccountDetails);
	makeStaticField($lAccountID, 		$myrow["AccountID"]);
	makeStaticField($lCustomerID,		$myrow["CustomerID"]);
	makeStaticField($lUsername,		$myrow["Username"]);
	makeStaticField($lPassword,		$myrow["Password"]);
	makeStaticField($lDomain, 		$myrow["Domain"]);
	makeStaticField($lDescription,		$pack_row["Description"]);
	makeStaticField($lDateOpened,		$myrow["DateOpened"]);
	makeStaticField($lDateClosed,		$myrow["DateClosed"]);
	
	# Gracefully handle the different billing types.
	#makeStaticField(

	makeStaticField("$lStatus",		$myrow["Status"]);
	makeStaticField("$lLastBilled",		$myrow["LastDateBilled"]);
	endPrettyTable();
} else {
	beginPrettyTable("2", "$lAccountDetails");
	echo $lNoRecords; 
	endPrettyTable();
}
endDocument();
?>
