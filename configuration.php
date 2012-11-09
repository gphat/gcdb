<?
# Configuration Page
session_start();

require("gcdb.php");
require("security/secure.php");
adminify($sess_user, $sess_admin);

beginDocument($lConfiguration, $sess_user);

$db = getDBConnection(); 

if ($updater == "config") {
	$result = DBquery("UPDATE Configuration SET Language='$language',SearchBar='$searchbar',CurrencyAfter='$currafter',TaxRate='$taxrate',BillFromAddress='$billfromaddress',BillReplyAddress='$billreplyaddress',BillBcc='$billbcc',BillSubject='$billsubject',BillHeader='$billheader',BillFooter='$billfooter',TicketNotifier='$ticketnotifier',HotTicket='$hotticket'", $db);
	if(!(is_integer($result)) or ($result == 1)) {
		beginPrettyTable("1", $lConfigurationUpdate);
		echo "<tr>\n";
		echo " <td><div class=data>$lConfigUpdateSuccess <a href='main.php'>$lMainPage</a></div></td>\n";
		echo "</tr>\n";
		endPrettyTable();
	} else {
		beginPrettyTable("1", $lConfigurationUpdate);
		echo "<tr> <td><div class=data>$lConfigUpdateFail: ";
		echo mysql_error();
		echo "</div></td></tr>\n";
		endPrettyTable();
	}
} else {

	$result = DBquery("SELECT * from Configuration", $db);
	$myrow = DBfetch_array($result);
	$datSearchBar = $myrow["SearchBar"];
	$datCurrAfter = $myrow["CurrencyAfter"];
	$datTaxRate = $myrow["TaxRate"];
	$datBillFromAddress = $myrow["BillFromAddress"];
	$datBillReplyAddress = $myrow["BillReplyAddress"];
	$datBillBcc = $myrow["BillBcc"];
	$datBillSubject = $myrow["BillSubject"];
	$datBillHeader = $myrow["BillHeader"];
	$datBillFooter = $myrow["BillFooter"];
	$datTicketNotifier = $myrow["TicketNotifier"];
	$datHotTicket = $myrow["HotTicket"];
	openForm("config", $PHP_SELF);
	 makeHiddenField("updater", "config");
	 beginPrettyTable("2", $lConfiguration);
	 beginBorderedTable("2");
	  makeStaticField($lVersion, $myrow["Version"]);
	  makeStaticField($lPersistentConnections, $PERSISTENT);
	  makeDropBox($lSearchBar, "searchbar", $datSearchBar, "On", "Off"); 
	  makeDropBox($lCurrencyAfterAmount, "currafter", $datCurrAfter, "On", "Off");
	  makeDropBox($lHotTicket, "hotticket", $datHotTicket, "On", "Off");
	  makeTextField($lTaxRate, "taxrate", $datTaxRate);
	  makeTextField($lTicketNotifier, "ticketnotifier", $datTicketNotifier);
	  echo "<tr><td><br></td></tr>";
	  makeTextField($lBillFromAddress, "billfromaddress", $datBillFromAddress);
	  makeTextField($lBillReplyAddress, "billreplyaddress", $datBillReplyAddress);
	  makeTextField($lBillBcc, "billbcc", $datBillBcc);
	  makeTextField($lBillSubject, "billsubject", $datBillSubject);
	  makeLargeTextField($lBillHeader, "billheader", $datBillHeader);
	  makeLargeTextField($lBillFooter, "billfooter", $datBillFooter);
	  echo "<tr><td><br></td></tr>";
	  echo "<tr><td><b>$lLanguage:</b></td>\n";
	  echo "  <td><select name='language'>\n";
	  $handle = opendir('lang');
	  echo "<option value='".$myrow["Language"]."'>".$myrow["Language"]."\n";
	  while($file = readdir($handle)) {
			$len = strlen($file);
			if(($file != $myrow["Language"]) && ($len > 4) && (substr($file, ($len - 3), $len) == "php")) {
		 	 echo "<option value='$file'>$file</option>\n";
			}
	  }
	  closedir($handle);
	  echo "</select>\n";
	  echo "</td></tr>\n";
	  makeSubmitter();
	 endPrettyTable();
	 endBorderedTable();
	closeForm();
}
endDocument();
?>
