<?
# Show Accounts displays all the info about an Account
session_start();

require("gcdb.php");
require("security/hybrid.php");

beginDocument($lPackage, $sess_user);

$db = getDBConnection(); 

if (isset($PackageGroupID)) {

	$result = DBquery("SELECT * from PackageGroup where PackageGroupID=$PackageGroupID", $db);
	$pack_row = DBfetch_array($result);

	$result = DBquery("SELECT * from Package where PackageGroupID=$PackageGroupID", $db);

	beginPrettyTable("2", $lPackage);
	makeStaticField($lDescription,		$pack_row["Description"]);
	while($group_row = DBfetch_array($result)) {
		$res_id = $group_row["ResourceID"];
		$res_result = DBquery("SELECT * from Resources where ResourceID=$res_id", $db);
		$res_row = DBfetch_array($res_result);
		$res = $res_row["Description"]." [".sprintf ("%.4f",$res_row["Price"])."]";
		makeStaticField($lResource, $res);
	}
	
	endPrettyTable();
} else {
	beginPrettyTable("2", "$lPackageDetails");
	echo $lNoRecords; 
	endPrettyTable();
}
endDocument();
?>
