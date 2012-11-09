<?
# Confirm deletion of a Something 
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lConfirmDelete, $sess_user);

beginPrettyTable("2", $lConfirm);
if($action == "sendbill") {
	echo "<table cellpadding='3' cellspacing='3'><tr><td><img src='images/logout.gif'></td><td><b>$lConfirmSendBill $CustomerID?</b><br><center><a class='light' href='sendbill.php?CustomerID=$CustomerID'>$lYes</a>&nbsp;&nbsp;<a class='light' href='showprofile.php?CustomerID=$CustomerID'>$lNo</a></center></td></tr></table>";
}
if($action == "deletecustomer") {
	echo "<table cellpadding='3' cellspacing='3'><tr><td><img src='images/logout.gif'></td><td><b>$lConfirmDeleteCustomer $CustomerID?</b><br><center><a class='light' href='delete.php?CustomerID=$CustomerID'>$lYes</a>&nbsp;&nbsp<a class='light' href='showprofile.php'>$lNo</a></center></td></tr></table>";
}
if($action == "deleteuser") {
	echo "<table cellpadding='3' cellspacing='3'><tr><td><img src='images/logout.gif'></td><td><b>$lConfirmDeleteUser $UserID?</b><br><center><a class='light' href='delete.php?UserID=$UserID'>$lYes</a>&nbsp;&nbsp<a class='light' href='usermanagement.php'>$lNo</a></center></td></tr></table>";
}
if($action == "deleteresource") {
	echo "<table cellpadding='3' cellspacing='3'><tr><td><img src='images/logout.gif'></td><td><b>$lConfirmDeleteResource $ResourceID?</b><br><center><a class='light' href='delete.php?ResourceID=$ResourceID'>$lYes</a>&nbsp;&nbsp<a class='light' href='resourcemanagement.php'>$lNo</a></center></td></tr></table>";
}
if($action == "deletepackage") {
	echo "<table cellpadding='3' cellspacing='3'><tr><td><img src='images/logout.gif'></td><td><b>$lConfirmDeletePackage $PackageGroupID?</b><br><center><a class='light' href='delete.php?PackageGroupID=$PackageGroupID'>$lYes</a>&nbsp;&nbsp<a class='light' href='packagemanagement.php'>$lNo</a></center></td></tr></table>";
}
if($action == "deletenews") {
	echo "<table cellpadding='3' cellspacing='3'><tr><td><img src='images/logout.gif'></td><td><b>$lConfirmDeleteNews $NewsID?</b><br><center><a class='light' href='delete.php?NewsID=$NewsID'>$lYes</a>&nbsp;&nbsp<a class='light' href='newsmanagement.php'>$lNo</a></center></td></tr></table>";
}
if($action == "deletecontact") {
	echo "<table cellpadding='3' cellspacing='3'><tr><td><img src='images/logout.gif'></td><td><b>$lConfirmDeleteContact $ContactID?</b><br><center><a class='light' href='delete.php?action=deletecontact&ContactID=$ContactID&CustomerID=$CustomerID'>$lYes</a>&nbsp;&nbsp<a class='light' href='showcontacts.php?CustomerID=$CustomerID'>$lNo</a></center></td></tr></table>";
}

endPrettyTable();

endDocument();
?>
