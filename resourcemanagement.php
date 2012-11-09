<?
session_start();

require("gcdb.php");
require("security/secure.php");
adminify($sess_user, $sess_admin);

beginDocument($lResourceManagement, $sess_user);

$db = getDBConnection();
$resourcelist_result = DBquery("select * from Resources", $db);
if ($resourcelist_row = DBfetch_array($resourcelist_result)) {
	beginPrettyTable("4", "$lResources");
	beginBorderedTable("4");
	echo ("<tr>\n");
	echo (" <td><b>&nbsp;</b></td> <td><b>$lDescription</b></td> <td><b>$lPrice</b></td>\n <td><b>$lActions</b></td>\n </tr>\n");
	do {
		printf(" <tr class=odd>\n  <td>%s</td> <td>%s</td>\n <td>%.4f</td> <td><a href='edit.php?ResourceID=%s'><img src='images/edit.gif' width=24 height=24 border=0></a><a href='confirm.php?action=deleteresource&ResourceID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete'></a></td></tr>\n", $resourcelist_row["ResourceID"], $resourcelist_row["Description"], $resourcelist_row["Price"], $resourcelist_row["ResourceID"], $resourcelist_row["ResourceID"]); 
	} while ($resourcelist_row = DBfetch_array($resourcelist_result));
	endPrettyTable();
	endBorderedTable();
} else {
	echo ("$lNoResourcesFound...\n"); 
}
endDocument();
?>
