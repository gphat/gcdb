<?
session_start();

require("gcdb.php");
require("security/secure.php");
adminify($sess_user, $sess_admin);

beginDocument($lEnterResource, $sess_user);

if(isset($Description)) {
	$db = getDBConnection();
	$result = DBquery("INSERT INTO Resources (Description, Price, TaxRate) VALUES ('$Description','$Price','$TaxRate')", $db);
	DBReport($result, $lResourceAddition, "administration.php", "$lAdmin", $db);
} else { 
	$db = getDBConnection();
	$result = DBquery("SELECT * FROM Configuration", $db);
	$config = DBfetch_array($result);
	beginPrettyTable("2", $lEnterResource);
	 openForm("enterresource", $PHP_SELF);
	  makeTextField($lDescription, "Description", "");
	  makeTextField($lPrice, "Price", "");
	  makeTextField($lTaxRate, "TaxRate", $config["TaxRate"]);
	 makeSubmitter();
	endPrettyTable();
	closeForm();
}
endDocument();
?>
