<?
session_start();

require("gcdb.php");
require("security/secure.php");
adminify($sess_user, $sess_admin);
if($pack_number) {
	$db = getDBConnection();
	$result = DBquery("SELECT PackageID from Package ORDER BY PackageID DESC LIMIT 1", $db);
	$num_row = DBfetch_array($result);
	$pack_group_id = $num_row["PackageID"] + 1;
	beginDocument($lCreatePackage, $sess_user);
		$charged = untranslate_interval($charged);
		$result = DBquery("INSERT INTO PackageGroup (Description, Charged) VALUES ('$description', '$charged')", $db);
		$pack_group_id = DBinsert_id($result,"PackageGroup","PackageGroupID",$db);
		beginPrettyTable("2", $lCreatePackage);
		echo "<tr><td colspan=2><b>$lPackageCreated <a href='administration.php'>$lAdmin</a></b></td></tr>";
		for($x = 0; $x < $pack_number; $x++) {
			$dyvar = "resource$x";
			$res_id = $$dyvar;
			$result = DBquery("SELECT * FROM Resources where ResourceID=$res_id", $db);
			$res_row = DBfetch_array($result);
			echo "<tr><td colspan=2><li>".$res_row["Description"]."</td></tr>";
			$result = DBquery("INSERT INTO Package (PackageGroupID, ResourceID) VALUES ($pack_group_id, $res_id)", $db);
			checkDBError($db);
		}
		endPrettyTable();
		closeForm();
	endDocument();
}
?>
