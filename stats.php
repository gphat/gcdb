<?
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lStatistics, $sess_user);

$db = getDBConnection(); 

$result = DBquery("select * from Configuration", $db);
$configuration = DBfetch_array($result);
$result = DBquery("select * from Customers", $db);
$customer_total = DBnum_rows($result);
while ($customer_row = DBfetch_array($result)) {
	$global_balance += $customer_row["Balance"];
}
$global_balance = sprintf("%.2f", $global_balance);
$result = DBquery("SELECT * FROM Resources", $db);
$res_total = DBnum_rows($result);
$result = DBquery("SELECT * FROM PackageGroup", $db);
$pack_total = DBnum_rows($result);
$result = DBquery("SELECT * FROM Contacts", $db);
$contacts_total = DBnum_rows($result);
$result = DBquery("select * from Tickets", $db);
$tickets_total = DBnum_rows($result);
$result = DBquery("select * from Accounts", $db);
$accounts_total = DBnum_rows($result);
$result = DBquery("SELECT * FROM Notes", $db);
$notes_total = DBnum_rows($result);
$result = DBquery("select * from Payments", $db);
$payments_total = DBnum_rows($result);
while ($payment_row = DBfetch_array($result)) {
	$payment_dollars += $payment_row["Amount"];
}
$payment_dollars = sprintf("%.2f", $payment_dollars);
$result = DBquery("select * from Invoices", $db);
$invoices_total = DBnum_rows($result);
while ($invoice_row = DBfetch_array($result)) {
	$invoice_dollars += $invoice_row["Amount"];
}
$invoice_dollars = sprintf("%.2f", $invoice_dollars);

/* Ok, actually display something now */
echo "<table cellpadding=2 cellspacing=0 border=0>\n";
echo " <tr>\n";
echo "  <td valign=top>\n";

beginPrettyTable("2", $lConfiguration);
 makeStaticField($lVersion, $configuration["Version"]);
 makeStaticField($lSearchBar, $configuration["SearchBar"]);
 makeStaticField($lHotTicket, $configuration["HotTicket"]);
 makeStaticField($lLanguage, $configuration["Language"]);
endPrettyTable();

echo "  </td>\n";
echo " </tr>\n";
echo " <tr>\n";
echo "  <td valign=top>\n";

beginPrettyTable("2", $lNumbers);
 makeStaticField($lTotalCustomers, $customer_total);
 makeStaticField($lTotalContacts, $contacts_total);
 makeStaticField($lTotalResources, $res_total);
 makeStaticField($lTotalPackages, $pack_total);
 makeStaticField($lTotalAccounts, $accounts_total);
 makeStaticField($lTotalPayments, $payments_total);
 makeStaticField($lTotalInvoices, $invoices_total);
 makeStaticField($lTotalTickets, $tickets_total);
 makeStaticField($lTotalNotes, $notes_total);
endPrettyTable();

echo "</td>\n";
echo "<td valign=top>\n";

beginPrettyTable("2", $lMoney);
 makeStaticField($lTotalPayments, $payment_dollars);
 makeStaticField($lTotalInvoices, $invoice_dollars);
 makeStaticField($lGlobalBalance, $global_balance);
endPrettyTable();

echo "  </td>\n";
echo " </tr>\n";
echo "</table>\n";

endDocument();
?>
