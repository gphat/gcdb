<?
# Enter note displays a form for the entry of notes, and handles the
# submission.
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lEnterNote, $sess_user);

if(isset($Note)) {
	$db = getDBConnection();
	$result = DBquery("INSERT INTO Notes (CustomerID, Note, PostedDate, Poster) VALUES ('$CustomerID', '$Note', '$PostedDate', '$Poster')", $db);
	DBReport($result, $lNoteAddition, "showprofile.php?CustomerID=$CustomerID", "$lCustomer $CustomerID", $db);
} else { 
	$now = date("Y-m-d");
	openForm("enternote", $PHP_SELF);
	 beginPrettyTable("2", $lEnterNote);
	  makeHiddenField("CustomerID", $CustomerID);
	  makeHiddenField("Poster", $sess_user);
          makeTextField($lNote, "Note", "");
	  makeTextField($lDatePosted, "PostedDate", $now);
	  makeSubmitter();
	 endPrettyTable();
	closeForm();
}
endDocument();
?>
