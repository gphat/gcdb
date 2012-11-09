<?
# Enter payment displays a form for the entry of a payment, and handles the
# submission.
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lEnterPayment, $sess_user);

if(isset($Type)) {
	$db = getDBConnection();
	$result = DBquery("INSERT INTO Payments (CustomerID, DatePaid, Type, Number, Amount) VALUES ('$CustomerID', '$DatePaid', '$Type', '$Number', '$Amount')", $db);
	DBReport($result, $lPaymentAddition, "showprofile.php?CustomerID=$CustomerID", "$lCustomer $CustomerID", $db);
} else { 
	$now = date("Y-m-d");
	openForm("enterpayment", $PHP_SELF);
	 beginPrettyTable("2", $lEnterPayment);
	  makeHiddenField("CustomerID", $CustomerID);
	  makeStaticField($lCustomerID, "$CustomerID");
	  makeTextField($lDatePaid, "DatePaid", $now);
	  makeTextField($lType, "Type", "");
	  makeTextField($lNumber, "Number", "");
	  if(isset($amount)) {
		$amount = sprintf("%.4f", $amount);
	  	makeTextField($lAmount, "Amount", $amount);
	  } else {
		makeTextField($lAmount, "Amount", "");
	  }
	  makeSubmitter();
	 endPrettyTable();
	closeForm();
}
endDocument();
?>
