<?
# Enter customer displays a form that allows the entry of a customer, and 
# handles the submission
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lEnterCustomer, $sess_user);

if(isset($first)) {
	$db = getDBConnection();
	$result = DBquery("INSERT INTO Customers (First, Mid, Last, Company, Address, City, State, Zip, Country, Telephone, Fax, Email, Password, CCNumber, CCExpire, CCName) VALUES ('$first','$mid','$last','$company','$address','$city','$state','$zip','$country','$phone','$fax','$email', '$password', '$ccnum', '$ccexp', '$ccname')", $db); 
	$cust = DBinsert_id($result,"Customers","CustomerID",$db);
	DBReport($result, $lCustomerAddition, "showprofile.php?CustomerID=$cust", "$lCustomer $cust", $db);
} else { 
	beginPrettyTable("2", $lEnterCustomer);
	openForm("entercustomer", $PHP_SELF);
	  makeTextField($lFirst, "first", "");
	  makeTextField($lMid, "mid", "");
	  makeTextField($lLast, "last", "");
	  makeTextField($lCompany, "company", "");
	  makeTextField($lAddress, "address", "");
	  makeTextField($lCity, "city", "");
	  makeTextField($lState, "state", "");
	  makeTextField($lZip, "zip", "");
	  makeTextField($lCountry, "country", "");
	  makeTextField($lPhone, "phone", "");
	  makeTextField($lFax, "fax", "");
	  makeTextField($lEmail, "email", "");
	  makeTextField($lPassword, "password", "");
	  makeTextField($lCCNumber, "ccnum", "");
	  makeTextField($lCCExpire, "ccexp", "");
	  makeTextField($lCCName, "ccname", "");
	makeSubmitter();
	endPrettyTable();
	closeForm();
}
endDocument();
?>
