<?
# Enter ticket displays a form for the entry of a ticket, and handles the
# submission.
session_start();

require("gcdb.php");
require("security/hybrid.php");

beginDocument($lEnterTicket, $sess_user);

if(isset($Desc)) {
	$db = getDBConnection();
	if(session_is_registered("sess_user")) {
		$result = DBquery("INSERT INTO Tickets (CustomerID, Description, Status, OpenDate, OpenTime, CloseDate, CloseTime, Opener) VALUES ('$CustID', '$Desc', '$Status', '$OpenDate', '$OpenTime', '$CloseDate', '$CloseTime', '$Opener')", $db);
		DBReport($result, $lTicketAddition, "showprofile.php?CustomerID=$CustID", "$lCustomer $CustID", $db);
	} else {
		$result = DBquery("INSERT INTO Tickets (CustomerID, Description, Status, OpenDate, OpenTime, CloseDate, CloseTime, Opener) VALUES ('$CustID', '$Desc', 'Open', '$OpenDate', '$OpenTime', '$CloseDate', '$CloseTime', 'Customer')", $db);
		$message = "Customer $CustID just added a new ticket:\n\n";
		$message .= "$OpenDate @ $OpenTime\n\n";
		$message .= "Description was:\n";
		$message .= $Desc;
		mail($notifier, "Customer Ticket Entry", $message); 
		DBReport($result, $lTicketAddition, "publicprofile.php?CustomerID=$CustID", "$lCustomer $CustID", $db);
	}	
} else { 
	$now = date("Y-m-d");
	$nowtime = strftime("%I:%M:%S");
	$db = getDBConnection();
	$result = DBquery("SELECT * FROM Configuration", $db);
	$config_row = DBfetch_array($result);
	beginPrettyTable("2", $lEnterTicket);
		openForm("enterticket", $PHP_SELF);
			makeHiddenField("CustID", $CustomerID);
			makeHiddenField("notifier", $config_row["TicketNotifier"]);
			makeStaticField($lCustomerID, "$CustomerID");
			makeLargeTextField($lDescription, "Desc", "");
			if(session_is_registered("sess_user")) {
				makeDropBox($lStatus, "Status", "Open", "Open", "Closed");
			} else {
				makeStaticField($lStatus, "Open");
			}
			makeTextField($lDateOpened, "OpenDate", "$now");
			makeTextField($lTimeOpened, "OpenTime", "$nowtime");
			makeTextField($lDateClosed, "CloseDate", "");
			makeTextField($lTimeClosed, "CloseTime", "");
			if(session_is_registered("sess_user")) {
				makeTextField($lOpener, "Opener", $sess_name);
			} else {
				makeStaticField($lOpener, "Customer");
			}
	 		makeSubmitter();
		endPrettyTable();
	closeForm();
}
endDocument();
?>
