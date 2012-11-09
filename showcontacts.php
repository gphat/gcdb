<?
# Show Accounts displays all the info about an Account
session_start();

require("gcdb.php");
require("security/hybrid.php");

beginDocument($lShowAccounts, $sess_user);

$db = getDBConnection(); 

if ($CustomerID) {
	$result = DBquery("SELECT * FROM Contacts WHERE CustomerID=$CustomerID", $db);
	$myrow = DBfetch_array($result);
	if ($myrow) {
		beginPrettyTable("8", $lContactDetails);
		beginBorderedTable(8);
		print "<tr> <td><b>$lFirst</b></td> <td><b>$lMid</b></td> <td><b>$lLast</b></td> <td><b>$lPhone</b></td> <td><b>$lMobile</b></td>  <td><b>$lEmail</b></td> <td><b>$lNotes</b></td> <td><b>$lActions</b></td></tr>";
		do {
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			printf ("<tr class='$class'> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td><a href='edit.php?ContactID=%s'><img src='images/edit.gif' border=0 alt='$lEditContact'></a><a href='confirm.php?action=deletecontact&ContactID=%s&CustomerID=%s'><img src='images/delete.gif' border=0 alt='$lDeleteContact'></a></td> </tr>", $myrow["First"],$myrow["Mid"],$myrow["Last"],$myrow["Phone"],$myrow["Mobile"],$myrow["Email"],$myrow["Notes"],$myrow["ContactID"],$myrow["ContactID"],$CustomerID);
		} while ($myrow = DBfetch_array($result));
		endBorderedTable();
		endPrettyTable();
	} else {
		beginPrettyTable("2", "$lContactDetails");
		echo $lNoRecords; 
		endPrettyTable();
	}
	printf ("<a href='showprofile.php?CustomerID=%s'>%s</a>", $CustomerID,$lCustomerDetails);
} else {
		beginPrettyTable("2", "$lContactDetails");
		echo $lNoRecords; 
		endPrettyTable();
}
endDocument();
?>
