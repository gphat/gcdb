<?
session_start();

require("gcdb.php");
require("security/secure.php");
adminify($sess_user, $sess_admin);
if($pack_number) {
	$db = getDBConnection();
	$result = DBquery("SELECT * FROM Resources", $db);
	# count the rows -- if zero, issue warning, give link
	$nrow = DBnum_rows($result);
	if ($nrow == 0 ) {
                beginPrettyTable("2", $lCreatePackage);
                echo ("$lNoResourcesFound\n<br>");
		echo ("<a href='enterresource.php'>$lEnterResource.</a>\n");
		endDocument();
		endPrettyTable();
		die();
	}
	$res_array = array();
	while($res_row = DBfetch_array($result)) {
		array_push($res_array, $res_row["ResourceID"]);
		array_push($res_array, $res_row["Description"]);
	}
	beginDocument($lCreatePackage, $sess_user);
		beginPrettyTable("2", $lCreatePackage);
		openForm("createpackage", "pack_final.php");
		makeHiddenField("pack_number", $pack_number);
		makeTextField($lDescription, "description", "");
		makeDropBox($lCharged, "charged", $lMonthly, $lMonthly, $lQuarterly, $lBiannually, $lAnnually);
		for($x = 0; $x < $pack_number; $x++) {
			makeArrayDropBox($lResource, "resource$x", "", $res_array);
		}
		makeSubmitter();
		endPrettyTable();
		closeForm();
	endDocument();
}
?>
