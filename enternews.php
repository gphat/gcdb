<?
# Enter news displays a form for the entry of news, and handles the
# submission.
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lEnterNews, $sess_user);

if(isset($Content)) {
	$db = getDBConnection();
	$result = DBquery("INSERT INTO News (Title, Content, PostedDate, Poster) VALUES ('$Title', '$Content', '$PostedDate', '$Poster')", $db);
	DBReport($result, $lNewsAddition, "administration.php", "$lAdmin", $db);
} else { 
	$now = date("Y-m-d");
	openForm("enternews", $PHP_SELF);
	 beginPrettyTable("2", $lEnterNews);
	  makeHiddenField("Poster", $sess_user);
          makeTextField($lTitle, "Title", "");
          makeLargeTextField($lContent, "Content", "");
	  makeTextField($lDatePosted, "PostedDate", $now);
	  makeSubmitter();
	 endPrettyTable();
	closeForm();
}
endDocument();
?>
