<?
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lEdit, $sess_user);

$now = date("Y-m-d");

$db = getDBConnection(); 

if($updater == "customer") {
	$result = DBquery("UPDATE Customers SET First='$first',Mid='$mid',Last='$last',Password='$password',Company='$company',Telephone='$phone',Fax='$fax',Email='$email',Address='$address',City='$city',State='$state',Zip='$zip',Country='$country',CCNumber='$ccnum',CCExpire='$ccexp',CCName='$ccname' WHERE CustomerID='$custid'", $db);
	DBReport($result, $lCustomerUpdate, "showprofile.php?CustomerID=$custid", "$lCustomer $custid", $db);
}
if($updater == "payment") {
	$result = DBquery("UPDATE Payments SET DatePaid='$datepaid',Type='$type',Number=$number,Amount=$amount WHERE PaymentID=$payid", $db);
	DBReport($result, $lPaymentUpdate, "showprofile.php?CustomerID=$custid", "$lCustomer $custid", $db);
}
if($updater == "invoice") {
	$result = DBquery("UPDATE Invoices SET Description='$description',DateBilled='$datebilled',Amount=$amount WHERE InvoiceID=$invoiceid", $db);
	DBReport($result, $lInvoiceUpdate, "showprofile.php?CustomerID=$custid", "$lCustomer $custid", $db);
}
if($updater == "resource") {
	$result = DBquery("UPDATE Resources SET Description='$desc',Price='$price',TaxRate='$taxrate' WHERE ResourceID=$resourceid", $db);
	DBReport($result, $lResourceUpdate, "resourcemanagement.php", $lResourceManagement, $db);
}
if($updater == "account") {
	$trans_charged = untranslate_interval($charged);
	# Clean up the date closed if they didn't
	if(($dateclosed == "0000-00-00") && ($status == "Closed")) {
		$dateclosed = $now;
	}			
	if(($dateclosed != "0000-00-00") && ($status == "Open")) {
		$dateclosed = "";
	}			
	$result = DBquery("UPDATE Accounts SET Username='$username',Password='$password',Domain='$domain',DateOpened='$dateopened',DateClosed='$dateclosed',LastDateBilled='$lastbilled',Status='$status',PackageGroupID='$packid' WHERE AccountID=$accountid", $db);
	DBReport($result, $lAccountUpdate, "showprofile.php?CustomerID=$custid", "$lCustomer $custid", $db);
}
if($updater == "ticket") {
	echo "asdasd";
	if(($closedate == "0000-00-00") && ($status == "Closed")) {
		$closedate = $now;
		$closetime = strftime("%I:%M:%S");
	}			
	if(($closedate != "0000-00-00") && ($status == "Open")) {
		$closedate = "";
		$closetime = "";
	}			
	$result = DBquery("UPDATE Tickets SET Description='$desc',Status='$status',OpenDate='$opendate',OpenTime='$opentime',CloseDate='$closedate',CloseTime='$closetime',Status='$status',Opener='$opener' WHERE TicketID=$ticketid", $db);
	DBReport($result, $lTicketUpdate, "showprofile.php?CustomerID=$custid", "$lCustomer $custid", $db);
}
if($updater == "user") {
	$result = DBquery("UPDATE Users SET Username='$username',Password='$password',RealName='$realname',Language='$language',Admin='$admin' WHERE UserID=$userid", $db);
	DBReport($result, $lUserUpdate, "usermanagement.php", $lUserManagement, $db);
}
if($updater == "news") {
	$result = DBquery("UPDATE News SET Title='$title',Content='$content',Poster='$sess_user',PostedDate='$posteddate' WHERE NewsID=$newsid", $db);
	DBReport($result, $lNewsUpdate, "newsmanagement.php", $lNewsManagement, $db);
}
if($updater == "note") {
	$result = DBquery("UPDATE Notes SET Note='$note',Poster='$sess_user',PostedDate='$posteddate' WHERE NoteID=$noteid", $db);
	DBReport($result, $lNoteUpdate, "showprofile.php?CustomerID=$custid", "$lCustomer $custid", $db);
}
if($updater == "contacts") {
	$result = DBquery("UPDATE Contacts SET First='$first', Mid='$mid', Last='$last', Phone='$phone', Email='$email', Notes='$notes' WHERE ContactID=$contactid", $db);
	DBReport($result, $lContactUpdate, "showcontacts.php?CustomerID=$customerid", $lContacts, $db);
}
if($updater == "package") {
	$up_pack = DBquery("SELECT * FROM Package WHERE PackageGroupID=$pack_id", $db);
	for($x = 1; $x <= $pack_number; $x++) {
		$check_res = DBfetch_array($up_pack);
		$dyvar = "resource$x";
		$res_id = $$dyvar;
		if($res_id != $check_res["ResourceID"]) {
			$pid = $check_res["PackageID"];
			$result = DBquery("UPDATE Package SET ResourceID=$res_id WHERE PackageID=$pid", $db);
		}
	}
	$charged = untranslate_interval($charged);
	$result = DBquery("UPDATE PackageGroup SET Description='$description', Charged='$charged' WHERE PackageGroupID=$pack_id", $db);
	DBReport($result, $lPackageUpdate, "packagemanagement.php", $lPackageManagement, $db);
}

if(isset($PackageGroupID)) {
	$pack_number = 0;
	$x = 0;
	beginPrettyTable(1, "$lEdit $lPackage");
	$list_result = DBquery("SELECT * FROM Resources", $db);
	$res_array = array();
	while($list_row = DBfetch_array($list_result)) {
		array_push($res_array, $list_row["ResourceID"]);
		array_push($res_array, $list_row["Description"]);
	}
	$result = DBquery("SELECT * FROM Package WHERE PackageGroupID=$PackageGroupID", $db);
	$pack_number = DBnum_rows($result);
	openForm("Package", $PHP_SELF);
	makeHiddenField("updater", "package");
	makeHiddenField("pack_id", $PackageGroupID);
	makeHiddenField("pack_number", $pack_number);
	$pack_result = DBquery("SELECT * FROM PackageGroup WHERE PackageGroupID=$PackageGroupID", $db);
	$the_row = DBfetch_array($pack_result);
	makeTextField($lDescription, "description", $the_row["Description"]);
	makeDropBox($lCharged, "charged", translate_interval($the_row["Charged"]), $lMonthly, $lQuarterly, $lBiannually, $lAnnually);
	while($pack_row = DBfetch_array($result)) {
		$x++;
		$res_id = $pack_row["ResourceID"];
		$res_result = DBquery("SELECT * FROM Resources WHERE ResourceID=$res_id", $db);
		$res_row = DBfetch_array($res_result);
		echo "<tr><td>";
		makeArrayDropBox($lResource, "resource$x", $res_row["Description"], $res_array);
		echo "</td></tr>";
	}
	makeSubmitter();
	closeForm();
	endPrettyTable();
}
if(isset($CustomerID)) {
	$result = DBquery("SELECT * from Customers where CustomerID=$CustomerID", $db);
	$myrow = DBfetch_array($result);
	openForm("Customer", $PHP_SELF);
	 beginPrettyTable("2", $lEditCustomer);
	 beginBorderedTable("2");
	  makeHiddenField("updater", "customer");
	  makeHiddenField("custid", $myrow["CustomerID"]);
	  makeStaticField($lCustomerID, $myrow["CustomerID"]);
	  makeTextField($lFirst, "first", $myrow["First"]);
	  makeTextField($lMid, "mid", $myrow["Mid"]);
	  makeTextField($lLast, "last", $myrow["Last"]);
          makeTextField($lPassword, "password", $myrow["Password"]);
	  makeTextField($lCompany, "company", $myrow["Company"]);
	  makeTextField($lEmail, "email", $myrow["Email"]);
	  makeTextField($lPhone, "phone", $myrow["Telephone"]);
	  makeTextField($lFax, "fax", $myrow["Fax"]);
	  makeTextField($lAddress, "address", $myrow["Address"]);
	  makeTextField($lCity, "city", $myrow["City"]);
	  makeTextField($lState, "state", $myrow["State"]);
	  makeTextField($lZip, "zip", $myrow["Zip"]);
	  makeTextField($lCountry, "country", $myrow["Country"]);
          makeTextField($lCCNumber, "ccnum", $myrow["CCNumber"]);
          makeTextField($lCCExpire, "ccexp", $myrow["CCExpire"]);
          makeTextField($lCCName, "ccname", $myrow["CCName"]);
	  makeSubmitter();
	 endPrettyTable();
	 endBorderedTable();
	closeForm();
}

if(isset($PaymentID)) {
	$result = DBquery("SELECT * from Payments where PaymentID=$PaymentID", $db);
	$myrow = DBfetch_array($result);
	openForm("Payment", $PHP_SELF); 
	 beginPrettyTable("2", $lEditPayment);
	 beginBorderedTable("2");
	  makeHiddenField("updater", "payment");
	  makeHiddenField("payid", $myrow["PaymentID"]);
	  makeHiddenField("custid", $myrow["CustomerID"]);
	  makeStaticField("Payment ID", $myrow["PaymentID"]);
	  makeStaticField($lCustomerID, $myrow["CustomerID"]);
	  makeTextField($lDatePaid, "datepaid", $myrow["DatePaid"]);
	  makeTextField($lType, "type", $myrow["Type"]);
	  makeTextField($lNumber, "number", $myrow["Number"]);
	  makeTextField($lAmount, "amount", $myrow["Amount"]);
	  makeSubmitter();
	 endPrettyTable();
	 endBorderedTable();
	closeForm();
}

if(isset($InvoiceID)) {
	$result = DBquery("SELECT * from Invoices where InvoiceID=$InvoiceID", $db);
	$myrow = DBfetch_array($result);
	openForm("$lInvoice", $PHP_SELF);
	 beginPrettyTable("2", $lEditInvoice);
	 beginBorderedTable("2");
	  makeHiddenField("updater", "invoice");
	  makeHiddenField("invoiceid", $myrow["InvoiceID"]);
	  makeHiddenField("custid", $myrow["CustomerID"]);
	  makeStaticField($lInvoiceID, $myrow["InvoiceID"]);
	  makeStaticField($lCustomerID, $myrow["CustomerID"]);
	  makeTextField($lDescription, "description", $myrow["Description"]);
	  makeTextField($lAmount, "amount", $myrow["Amount"]);
	  makeTextField($lDateBilled, "datebilled", $myrow["DateBilled"]);
	  makeSubmitter();
	 endBorderedTable();
	 endPrettyTable();
	closeForm();
}

if(isset($ResourceID)) {
	$result = DBquery("SELECT * from Resources where ResourceID=$ResourceID", $db);
	$myrow = DBfetch_array($result);
	openForm("Resource", $PHP_SELF);
	 beginPrettyTable("2", $lEditResource);
	 beginBorderedTable("2");
	  makeHiddenField("updater", "resource");
	  makeHiddenField("resourceid", $myrow["ResourceID"]);
	  makeStaticField($lResourceID, $myrow["ResourceID"]);
	  makeTextField($lDescription, "desc", $myrow["Description"]);
	  makeTextField($lPrice, "price", sprintf ("%.4f",$myrow["Price"]) );
	  makeTextField($lTaxRate, "taxrate", $myrow["TaxRate"]);
	  makeSubmitter();
	 endBorderedTable();
	 endPrettyTable();
	closeForm();
}

if(isset($AccountID)) {
	$result = DBquery("SELECT * from Accounts where AccountID=$AccountID", $db);
	$myrow = DBfetch_array($result);
	$result = DBquery("SELECT * FROM PackageGroup", $db);
	$pack_array = array();
	while($pack_row = DBfetch_array($result)) {
		array_push($pack_array, $pack_row["PackageGroupID"]);
		array_push($pack_array, $pack_row["Description"]);
	}
	$this_id = $myrow["PackageGroupID"];
	$result = DBquery("SELECT * FROM PackageGroup WHERE PackageGroupID=$this_id", $db);
	$pack_row = DBfetch_array($result);
	openForm("Account", $PHP_SELF);
	 beginPrettyTable("2", $lEditAccount);
	 beginBorderedTable("2");
	  makeHiddenField("updater", "account");
	  makeHiddenField("accountid", $myrow["AccountID"]);
	  makeHiddenField("custid", $myrow["CustomerID"]);
	  makeStaticField($lAccountID, $myrow["AccountID"]);
	  makeStaticField($lCustomerID, $myrow["CustomerID"]);
	  makeArrayDropBox($lPackage, "packid", $pack_row["Description"], $pack_array);
	  makeTextField($lUsername, "username", $myrow["Username"]);
	  makeTextField($lPassword, "password", $myrow["Password"]);
	  makeTextField($lDomain, "domain", $myrow["Domain"]);
	  makeTextField($lDateOpened, "dateopened", $myrow["DateOpened"]);
	  makeTextField($lDateClosed, "dateclosed", $myrow["DateClosed"]);
	  makeTextField($lLastBilled, "lastbilled", $myrow["LastDateBilled"]);
	  makeDropBox("$lStatus", "status", $myrow["Status"], "Open", "Closed");
	  makeSubmitter();
	 endBorderedTable();
	 endPrettyTable();
	closeForm();
}

if(isset($TicketID)) {
	$result = DBquery("SELECT * from Tickets where TicketID=$TicketID", $db);
	$myrow = DBfetch_array($result);
	openForm("Ticket", $PHP_SELF); 
	 beginPrettyTable("2", $lEditTicket);
	 beginBorderedTable("2");
	  makeHiddenField("updater", "ticket");
	  makeHiddenField("ticketid", $myrow["TicketID"]);
	  makeHiddenField("custid", $myrow["CustomerID"]);
	  makeStaticField($lTicketID, $myrow["TicketID"]);
	  makeStaticField($lCustomerID, $myrow["CustomerID"]);
	  makeLargeTextField($lDescription, "desc", $myrow["Description"]);
	  makeDropBox($lStatus, "status", $myrow["Status"], "Open", "Closed");
	  makeTextField($lDateOpened, "opendate", $myrow["OpenDate"]);
	  makeTextField($lTimeOpened, "opentime", $myrow["OpenTime"]);
	  makeTextField($lDateClosed, "closedate", $myrow["CloseDate"]);
	  makeTextField($lTimeClosed, "closetime", $myrow["CloseTime"]);
	  makeTextField($lOpener, "opener", $myrow["Opener"]);
	  makeSubmitter();
	 endPrettyTable();
	 endBorderedTable();
	closeForm();
}

if(isset($UserID)) {
	$result = DBquery("SELECT * from Users where UserID=$UserID", $db);
	$myrow = DBfetch_array($result);
	openForm("User", $PHP_SELF); 
	 beginPrettyTable("2", $lEditUser);
	 beginBorderedTable("2");
	  makeHiddenField("updater", "user");
	  makeHiddenField("userid", $myrow["UserID"]);
	  makeStaticField($lID, $myrow["UserID"]);
	  makeTextField($lUsername, "username", $myrow["Username"]);
	  makeTextField($lPassword, "password", $myrow["Password"]);
	  makeTextField($lName, "realname", $myrow["RealName"]);
	  makeDropBox("$lAdmin", "admin", $myrow["Admin"], "Yes", "No");
		echo "<td><b>$lLanguage:</b></td>\n";
		echo " <td><select name='language'>\n";
		$handle = opendir('lang');
		echo "<option value='".$myrow["Language"]."'>".$myrow["Language"]."\n";
		while($file = readdir($handle)) {
			$len = strlen($file);
			if(($file != $myrow["Language"]) && ($len > 4) && (substr($file, ($len - 3), $len) == "php")) {
				echo "<option value='$file'>$file</option>\n";
			}
		}
		closedir($handle);
		echo "</select>";
		echo "</td></tr>\n";
	  makeSubmitter();
	 endPrettyTable();
	 endBorderedTable();
	closeForm();
}
if(isset($NewsID)) {
	$result = DBquery("SELECT * from News where NewsID=$NewsID", $db);
	$myrow = DBfetch_array($result);
	$now = date("Y-m-d");
	openForm("$lNews", $PHP_SELF);
	 beginPrettyTable("2", $lEditNews);
	 beginBorderedTable("2");
	  makeHiddenField("updater", "news");
	  makeHiddenField("newsid", $myrow["NewsID"], "");
	  makeTextField($lTitle, "title", $myrow["Title"]);
	  makeLargeTextField($lContent, "content", $myrow["Content"]);
	  makeTextField($lDatePosted, "posteddate", $now);
	  makeSubmitter();
	 endBorderedTable();
	 endPrettyTable();
	closeForm();
}
if(isset($NoteID)) {
	$result = DBquery("SELECT * from Notes where NoteID=$NoteID", $db);
	$myrow = DBfetch_array($result);
	$now = date("Y-m-d");
	openForm("$lNote", $PHP_SELF);
	 beginPrettyTable("2", $lEditNote);
	 beginBorderedTable("2");
	  makeHiddenField("updater", "note");
	  makeHiddenField("poster", "$sess_user");
	  makeHiddenField("custid", $myrow["CustomerID"], "");
	  makeHiddenField("noteid", $myrow["NoteID"], "");
	  makeTextField($lNote, "note", $myrow["Note"]);
	  makeTextField($lDatePosted, "posteddate", $myrow["PostedDate"]);
	  makeSubmitter();
	 endBorderedTable();
	 endPrettyTable();
	closeForm();
}
if(isset($ContactID)) {
	$result = DBquery("SELECT * from Contacts where ContactID=$ContactID", $db);
	$myrow = DBfetch_array($result);
	openForm("Contacts", $PHP_SELF); 
	 beginPrettyTable("2", $lEditContact);
	 beginBorderedTable("2");
	  makeHiddenField("updater", "contacts");
	  makeHiddenField("contactid", $myrow["ContactID"]);
	  makeHiddenField("customerid", $myrow["CustomerID"]);
	  makeStaticField($lID, $myrow["ContactID"]);
	  makeTextField($lFirst, "first", $myrow["First"]);
	  makeTextField($lMid, "mid", $myrow["Mid"]);
	  makeTextField($lLast, "last", $myrow["Last"]);
	  makeTextField($lPhone, "phone", $myrow["Phone"]);
	  makeTextField($lMobile, "mobile", $myrow["Mobile"]);
	  makeTextField($lEmail, "email", $myrow["Email"]);
	  makeTextField($lNotes, "notes", $myrow["Notes"]);
	  makeSubmitter();
	 endPrettyTable();
	 endBorderedTable();
	closeForm();
}

endDocument();
?>
