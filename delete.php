<?
# Delete deletes things.  Go figure.
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lDelete, $sess_user);

$db = getDBConnection();
if ($PaymentID) {
	$result = DBquery("DELETE from Payments where PaymentID=$PaymentID", $db);
	DBReport($result, $lPaymentDeletion, "showprofile.php?CustomerID=$CustomerID", "$lCustomer $CustomerID", $db);
	endDocument();
	return;
}
if ($InvoiceID) {
	$result = DBquery("DELETE from Invoices where InvoiceID=$InvoiceID", $db);
	DBReport($result, $lInvoiceDeletion, "showprofile.php?CustomerID=$CustomerID", "$lCustomer $CustomerID", $db);
	endDocument();
	return;
}
if ($AccountID) {
	$result = DBquery("DELETE from Accounts where AccountID=$AccountID", $db);
	DBReport($result, $lAccountDeletion, "showprofile.php?CustomerID=$CustomerID", "$lCustomer $CustomerID", $db);
	endDocument();
	return;
}
if ($TicketID) {
	$result = DBquery("DELETE from Tickets where TicketID=$TicketID", $db);
	DBReport($result, $lTicketDeletion, "showprofile.php?CustomerID=$CustomerID", "$lCustomer $CustomerID", $db);
	endDocument();
	return;
}
if ($UserID) {
	$result = DBquery("DELETE from Users where UserID=$UserID", $db);
	DBReport($result, $lUserDeletion, "usermanagement.php", $lUserManagement, $db);
	endDocument();
	return;
}
if ($NewsID) {
	$result = DBquery("DELETE from News where NewsID=$NewsID", $db);
	DBReport($result, $lNewsDeletion, "newsmanagement.php", $lNewsManagement, $db);
	endDocument();
	return;
}
if ($NoteID) {
	$result = DBquery("DELETE from Notes where NoteID=$NoteID", $db);
	DBReport($result, $lNoteDeletion, "showprofile.php?CustomerID=$CustomerID", "$lCustomer $CustomerID", $db);
	endDocument();
	return;
}
if ($ResourceID) {
	$result = DBquery("DELETE from Resources where ResourceID=$ResourceID", $db);
	DBReport($result, $lResourceDeletion, "resourcemanagement.php", $lResourceManagement, $db);
	endDocument();
	return;
}
if ($PackageGroupID) {
	$result = DBquery("DELETE from PackageGroup where PackageGroupID=$PackageGroupID", $db);
	$result = DBquery("DELETE from Package where PackageGroupID=$PackageGroupID", $db);
	DBReport($result, $lPackageDeletion, "packagemanagement.php", "$lPackageManagement", $db);
	endDocument();
	return;
}
if ($ContactID) {
	$result = DBquery("DELETE from Contacts where ContactID=$ContactID", $db);
	DBReport($result, $lContactDeletion, "showprofile.php?CustomerID=$CustomerID", "$lCustomer $CustomerID", $db);
	endDocument();
	return;
}
if ($CustomerID) {
	$result = DBquery("DELETE FROM Tickets WHERE CustomerID=$CustomerID", $db);
	$result = DBquery("DELETE FROM Notes WHERE CustomerID=$CustomerID", $db);
	$result = DBquery("DELETE FROM Invoices WHERE CustomerID=$CustomerID", $db);
	$result = DBquery("DELETE FROM Payments WHERE CustomerID=$CustomerID", $db);
	$result = DBquery("DELETE FROM Accounts WHERE CustomerID=$CustomerID", $db);
	$result = DBquery("DELETE FROM Contacts WHERE CustomerID=$CustomerID", $db);
	$result = DBquery("DELETE FROM Customers WHERE CustomerID=$CustomerID", $db);
	DBReport($result, $lCustomerDeletion, "showprofile.php", "$lCustomerListing", $db);
	endDocument();
	return;
}
?>
