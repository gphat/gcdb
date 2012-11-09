<?
# Enter user displays a form for the entry of a user, and handles the
# submission.
session_start();

require("gcdb.php");
require("security/secure.php");
adminify($sess_user, $sess_admin);

beginDocument($lEnterUser, $sess_user);

if(isset($Password)) {
	$db = getDBConnection();
	$result = DBquery("INSERT INTO Users (Username, Password, RealName, Admin, Language) VALUES ('$Username', '$Password', '$RealName', '$Admin', '$Language')", $db);
	DBReport($result, $lUserAddition, "administration.php", $lAdmin, $db);
} else { 
	beginPrettyTable("2", $lEnterUser);
		openForm("enteruser", $PHP_SELF);
		makeTextField($lUsername, "Username", "");
		makeTextField($lPassword, "Password", "");
		makeTextField($lName, "RealName", "");
		makeDropBox($lAdmin, "Admin", "No", "No", "Yes");
	  	echo "<td><b>$lLanguage:</b></td>\n";
	  	echo "  <td><select name='Language'>\n";
	  	$handle = opendir('lang');
	  	while($file = readdir($handle)) {
				$len = strlen($file);
				if((strlen($file) > 4) && (substr($file, ($len - 3), $len) == "php")) {
					echo "<option value='$file'>$file</option>\n";
				}
		  }
		closedir($handle);
		echo "<option selected>$sess_lang</option>\n";
		echo "</select>\n";
		echo "</td></tr>\n";
		makeSubmitter();
		endPrettyTable();
	closeForm();
}
endDocument();
?>
