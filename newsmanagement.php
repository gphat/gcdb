<?
session_start();

require("gcdb.php");
require("security/secure.php");
adminify($sess_user, $sess_admin);

beginDocument($lNewsManagement, $sess_user);

$db = getDBConnection();
$newslist_result = DBquery("select * from News order by NewsID", $db);
if ($newslist_row = DBfetch_array($newslist_result)) {
	beginPrettyTable("4", "$lNews");
	beginBorderedTable("4");
	echo ("<tr>\n");
	echo (" <td><b>&nbsp;</b></td> <td><b>$lTitle</b></td> <td><b>$lDatePosted</b></td>\n <td><b>$lActions</b></td>\n </tr>\n");
	do {
		printf(" <tr class=odd>\n  <td>%s</td> <td>%s</td>\n <td>%s</td> <td><a href='edit.php?NewsID=%s'><img src='images/edit.gif' width=24 height=24 border=0></a><a href='confirm.php?action=deletenews&NewsID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete'></a></td></tr>\n", $newslist_row["NewsID"], $newslist_row["Title"], $newslist_row["PostedDate"], $newslist_row["NewsID"], $newslist_row["NewsID"]); 
	} while ($newslist_row = DBfetch_array($newslist_result));
	endPrettyTable();
	endBorderedTable();
} else {
	echo ("$lNoNewsFound...\n"); 
}
endDocument();
?>
