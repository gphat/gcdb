<?
# Show Payments show all information about a payment
session_start();

require("gcdb.php");
require("security/hybrid.php");

beginDocument($lShowPayments, $sess_user);

$db = getDBConnection(); 

if ($PaymentID) {
	$result = DBquery("SELECT * from Payments where PaymentID=$PaymentID", $db);
	$myrow = DBfetch_array($result);
	beginPrettyTable("4", $lPaymentDetails);
	makeStaticField($lPaymentID, 	$myrow["PaymentID"]);
	makeStaticField($lCustomerID, 	$myrow["CustomerID"]);
	makeStaticField($lDatePaid, 	$myrow["DatePaid"]);
	makeStaticField($lType, 	$myrow["Type"]);
	makeStaticField($lNumber, 	$myrow["Number"]);
	makeStaticField($lAmount, 	sprintf("%.4f",$myrow["Amount"]) );
	endPrettyTable();
} else {
	beginPrettyTable("1", $lPaymentDetails);
	echo $lNoRecords; 
	endPrettyTable();
}
endDocument();
?>
