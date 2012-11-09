<?
session_start();

require("gcdb.php");
require("security/secure.php");
adminify($sess_user, $sess_admin);

beginDocument($lUserManagement, $sess_user);

$db = getDBConnection();
$userlist_result = DBquery("select * from Users order by UserID", $db);
if ($userlist_row = DBfetch_array($userlist_result)) {
	beginPrettyTable("4", "$lUsers");
	beginBorderedTable("4");
	echo ("<tr>\n");
	echo (" <td><b>&nbsp;</b></td> <td><b>$lName</b></td> <td><b>$lLogin</b></td>\n <td><b>$lActions</b></td>\n </tr>\n");
	do {
		printf(" <tr class=odd>\n  <td>%s</td> <td>%s</td>\n <td>%s</td> <td><a href='edit.php?UserID=%s'><img src='images/edit.gif' width=24 height=24 border=0></a><a href='confirm.php?action=deleteuser&UserID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete this User'></a></td></tr>\n", $userlist_row["UserID"], $userlist_row["RealName"], $userlist_row["Username"], $userlist_row["UserID"], $userlist_row["UserID"]); 
	} while ($userlist_row = DBfetch_array($userlist_result));
	endPrettyTable();
	endBorderedTable();
} else {
	echo ("$lNoUsersFound...\n"); 
}
endDocument();
?>
