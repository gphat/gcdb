<?
# Enter accounts displays a form that allows the entry of an account, and
# handles the submission
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lEnterContact, $sess_user);

if(isset($Last)) {
	$db = getDBConnection();
        $result = DBquery("INSERT INTO Contacts (CustomerID,First,Mid,Last,Phone,Mobile,Email,Notes) VALUES ('$CustomerID','$First','$Mid','$Last','$Phone','$Mobile','$Email','$Notes')", $db);

	DBReport($result, $lContactAddition, "showprofile.php?CustomerID=$CustomerID", "$lCustomer $CustomerID", $db);
} else { 
	$db = getDBConnection();
	openForm("enteraccount", $PHP_SELF);
	 beginPrettyTable("2", $lEnterAccount);
	  makeHiddenField("CustomerID", $CustomerID);
	  makeStaticField($lCustomerID, $CustomerID);
	  makeTextField($lFirst, "First", "");
	  makeTextField($lMid, "Mid", "");
	  makeTextField($lLast, "Last", "");
	  makeTextField($lPhone, "Phone", "");
	  makeTextField($lMobile, "Mobile", "");
	  makeTextField($lEmail, "Email", "");
	  makeTextField($lNotes, "Notes", "");
	  makeSubmitter();
	 endPrettyTable();
	closeForm();
}
endDocument();
?>
