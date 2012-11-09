<?
# Show Invoice displays all the info about an invoice, or displays all the
# invoices
session_start();

require("gcdb.php");
require("security/hybrid.php");

beginDocument($lShowInvoices, $sess_user);

$db = getDBConnection(); 

if ($InvoiceID) {
	$result = DBquery("SELECT * from Invoices where InvoiceID=$InvoiceID", $db);
	$myrow = DBfetch_array($result);
	beginPrettyTable("2", $lInvoiceDetails);
	makeStaticField($lInvoiceID, 	$myrow["InvoiceID"]);
	makeStaticField($lCustomerID, 	$myrow["CustomerID"]);
	makeStaticField($lDescription, 	$myrow["Description"]);
	makeStaticField($lDateBilled, 	$myrow["DateBilled"]);
	makeStaticField($lAmount, 	sprintf("%.4f",$myrow["Amount"]) );
	endPrettyTable();
} else {
	beginPrettyTable("2", "$lInvoiceDetails");
	echo $lNoRecords; 
	endPrettyTable();
}
endDocument();
?>
