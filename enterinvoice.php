<?
# Enter invoice displays a form that allows the entry of an invoice, and handles
# the submission
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lEnterInvoice, $sess_user);

if(isset($Description)) {
	$db = getDBConnection();
	$result = DBquery("INSERT INTO Invoices (CustomerID, Description, DateBilled, Amount) VALUES ('$CustomerID', '$Description', '$DateBilled', '$Amount')", $db);
	DBReport($result, $lInvoiceAddition, "showprofile.php?CustomerID=$CustomerID", "$lCustomer $CustomerID", $db);
} else { 
	$Now = date("Y-m-d");
	beginPrettyTable("2", $lEnterInvoice);
	openForm("enterinvoice", $PHP_SELF);
 	  makeHiddenField("CustomerID", $CustomerID);
	  makeStaticField($lCustomerID, $CustomerID);
	  makeTextField($lDescription, "Description", "");
	  makeTextField($lDateBilled, "DateBilled", $Now);
	  makeTextField($lAmount, "Amount", "");
	  makeSubmitter();
	closeForm();
	endPrettyTable();

}
endDocument();
?>
