<?
# Search uses regexp to find things that match the supplied criteria
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lSearchResults, $sess_user);

if(!($tosearch)) {
	beginPrettyTable("1", "0 $lFound");
	echo "<tr><td>$lNoRecords</td></tr>";
	endPrettyTable();
	exit;
}

if($searcher == "last") {
	$last = $tosearch;
}
if($searcher == "address") {
	$address = $tosearch;
}
if($searcher == "email") {
	$email = $tosearch;
}
if($searcher == "domain") {
	$domain = $tosearch;
}

if (($searcher == "last") or ($searcher == "address") or ($searcher == "email") or ($searcher == "domain")) {
	$db = getDBConnection(); 
	if($searcher == "last") {
		$result = DBquery("SELECT * from Customers where Last regexp '$tosearch'", $db);
	}
	if($searcher == "address") {
		$result = DBquery("SELECT * from Customers where Address regexp '$tosearch'", $db);
	}
	if($searcher == "email") {
		$result = DBquery("SELECT * from Customers where Email regexp '$tosearch'", $db);
	}
	if($searcher == "domain") {
		$result = DBquery("SELECT * from Accounts where Domain regexp '$tosearch'", $db);
	}
	$number = DBnum_rows($result);
	if($searcher == "domain") {
		beginPrettyTable("5", "$number $lFound");
		beginBorderedTable("5");
		if ($myrow = DBfetch_array($result)) {
			$cust_id = $myrow["CustomerID"];
			$result = DBquery("SELECT * FROM Customers where CustomerID=$cust_id", $db);
			$cust_row = DBfetch_array($result);
			echo "<tr>\n  <td><b>&nbsp;$lID&nbsp;</b></td>\n  <td><b>$lName</b></td>\n <td><b>$lEmail</b></td> <td><b>$lDomain</b></td> <td><b>$lAddress</b></td></tr>\n";
			do {
				if($class == "odd") { $class = "even"; } else { $class = "odd"; }
				printf(" <tr class='$class'><td align='center'><a href='showprofile.php?CustomerID=%s'>%s</a></td> <td>%s, %s</td> <td>%s</td> <td>%s</td> <td>%s</td> </tr>\n", $cust_row["CustomerID"], $cust_row["CustomerID"], $cust_row["Last"], $cust_row["First"], $cust_row["Email"], $myrow["Domain"], $cust_row["Address"]);
			} while ($myrow = DBfetch_array($result));
		} else {
			echo "<tr><td>$lNoRecords</td></tr>";
		}
	} else {
		beginPrettyTable("5", "$number $lFound");
		beginBorderedTable("5");
		if ($myrow = DBfetch_array($result)) {
			echo "<tr>\n  <td><b>&nbsp;$lID&nbsp;</b></td>\n  <td><b>$lName</b></td>\n <td><b>$lEmail</b></td> <td><b>$lAddress</b></td> <td><b>$lBalance</b></td></tr>\n";
			do {
				if($class == "odd") { $class = "even"; } else { $class = "odd"; }
				if($myrow["Balance"] > 0) { $bal_class = "positive"; } else { $bal_class = "negative"; }
				printf(" <tr class='$class'><td align='center'><a href='showprofile.php?CustomerID=%s'>%s</a></td>\n  <td>%s, %s</td>\n  <td>%s</td>\n <td>%s</td>\n <td><div class='$bal_class'>%s</div></td>\n  </tr>\n", $myrow["CustomerID"], $myrow["CustomerID"], $myrow["Last"], $myrow["First"], $myrow["Email"], $myrow["Address"], $myrow["Balance"]);
			} while ($myrow = DBfetch_array($result));
		} else {
			echo $lNoRecords;
		}
	}
	endBorderedTable();
	endPrettyTable();
}

endDocument();
?>
