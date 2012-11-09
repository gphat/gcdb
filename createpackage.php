<?
session_start();

require("gcdb.php");
require("security/secure.php");
adminify($sess_user, $sess_admin);

beginDocument($lCreatePackage, $sess_user);
	beginPrettyTable("2", $lCreatePackage);
	openForm("createpackage", "pack_resources.php");
	echo "<tr><td colspan=2>How many resources will this Package use?</td></tr>";
	makeTextField("$lNumber", "pack_number", "");
	makeSubmitter();
	endPrettyTable();
	closeForm();
endDocument();
?>
