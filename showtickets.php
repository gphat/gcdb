<?
session_start();

require("gcdb.php");
require("security/hybrid.php");

beginDocument($lShowTicket, $sess_user);

$db = getDBConnection(); 

if ($TicketID) {
	$result = DBquery("SELECT * from Tickets where TicketID=$TicketID", $db);
	$ticketrow = DBfetch_array($result);
	beginPrettyTable("2", "$lTicketDetails");
	makeStaticField($lTicketID, 	$ticketrow["TicketID"]);
	makeStaticField($lCustomerID, 	$ticketrow["CustomerID"]);
	makeStaticField($lStatus, 	$ticketrow["Status"]);
	makeStaticField($lDescription, 	$ticketrow["Description"]);
	makeStaticField($lDateOpened, 	$ticketrow["OpenDate"]);
	makeStaticField($lTimeOpened, 	$ticketrow["OpenTime"]);
	makeStaticField($lDateClosed, 	$ticketrow["CloseDate"]);
	makeStaticField($lTimeClosed, 	$ticketrow["CloseTime"]);
	makeStaticField($lOpener, 	$ticketrow["Opener"]);
	endPrettyTable();
} else {
	beginPrettyTable("1", $lTicketDetails);
	echo $lNoTicketsFound;
	endPrettyTable();
}
endDocument();
?>
