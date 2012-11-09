<?
session_start();

require("gcdb.php");
require("security/secure.php");
adminify($sess_user, $sess_admin);

beginDocument($lPackageManagement, $sess_user);

$db = getDBConnection();
$packagelist_result = DBquery("select * from PackageGroup", $db);
if ($packagelist_row = DBfetch_array($packagelist_result)) {
	beginPrettyTable("4", "$lPackages");
	beginBorderedTable("4");
	echo ("<tr>\n");
	echo (" <td><b>$lID</b></td> <td><b>$lDescription</b></td> <td><b>$lActions</b></td>\n </tr>\n");
	do {
		printf(" <tr class=odd>\n  <td>%s</td> <td>%s</td> <td><a href='edit.php?PackageGroupID=%s'><img src='images/edit.gif' height=24 width='24' border=0 alt='$lEdit'></a><a href='confirm.php?action=deletepackage&PackageGroupID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='$lDelete'></a></td></tr>\n", $packagelist_row["PackageGroupID"], $packagelist_row["Description"], $packagelist_row["PackageGroupID"], $packagelist_row["PackageGroupID"]); 
	} while ($packagelist_row = DBfetch_array($packagelist_result));
	endPrettyTable();
	endBorderedTable();
} else {
	echo ("$lNoPackagesFound...\n"); 
}
endDocument();
?>
