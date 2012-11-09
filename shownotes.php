<?
# Show Notes show all information about a Note
session_start();

require("gcdb.php");
require("security/hybrid.php");

beginDocument($lShowNotes, $sess_user);

$db = getDBConnection(); 

if ($NoteID) {
	$result = DBquery("SELECT * from Notes where NoteID=$NoteID", $db);
	$myrow = DBfetch_array($result);
	beginPrettyTable("4", $lNoteDetails);
	makeStaticField($lID, 	$myrow["NoteID"]);
	makeStaticField($lCustomerID, 	$myrow["CustomerID"]);
	makeStaticField($lNote,		$myrow["Note"]);
	makeStaticField($lDatePosted, 	$myrow["PostedDate"]);
	makeStaticField($lPoster, 	$myrow["Poster"]);
	endPrettyTable();
} else {
	beginPrettyTable("1", $lNoteDetails);
	echo $lNoRecords; 
	endPrettyTable();
}
endDocument();
?>
