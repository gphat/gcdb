<?
# Money displays negative or positive accounts
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lMoney, $sess_user);

$db = getDBConnection(); 

if ($negmoney) {
	$WhatKind = $lOwing;
	$tot_class = "negative";
}
if ($posmoney) {
	$WhatKind = $lCredited;
	$tot_class = "positive";
}
$config_result = DBquery("SELECT * from Configuration", $db);
$config_row = DBfetch_array($config_result);

$customer_result = DBquery("SELECT * from Customers", $db);
beginPrettyTable("3", "$WhatKind $lAccounts");
beginBorderedTable("3");
echo "<tr> <td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lName</b></td> <td><b>$lAmount</b></td> </tr>\n";
while ($customer_row = DBfetch_array($customer_result)) {

	$CustomerID = $customer_row["CustomerID"];
	
	if ($negmoney) {
		if ($customer_row["Balance"] < 0) {
			$Balance = abs($Balance);
			echo "<tr class=odd>\n";
			printf ("<td><a href='showprofile.php?CustomerID=%s'>%s</a></td> <td>%s %s</td> <td><div class='$tot_class'>%.4f</div></td> </tr>\n", $CustomerID, $CustomerID, $customer_row["First"], $customer_row["Last"], $customer_row["Balance"]);
			echo "</tr>\n";
		}
	}
	if ($posmoney) {
		if ($customer_row["Balance"] > 0) {
			$Balance = abs($Balance);
			echo "<tr class=odd>\n";
			printf ("<td><a href='showprofile.php?CustomerID=%s'>%s</a></td> <td>%s %s</td> <td><div class='$tot_class'>%.4f</div></td> </tr>\n", $CustomerID, $CustomerID, $customer_row["First"], $customer_row["Last"], $customer_row["Balance"]);
			echo "</tr>\n";
		}
	}
}
endBorderedTable();
endPrettyTable();
endDocument();
?>
