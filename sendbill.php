<?
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lSendBill, $sess_user);

if(isset($CustomerID)) {

	$db = getDBConnection(); 

	$conf_result = DBquery("select * from Configuration", $db);
	checkDBError($db);
	$config = DBfetch_array($conf_result);
	$configuration = DBfetch_array($conf_result);
	$cust_result = DBquery("select * from Customers where CustomerID=$CustomerID", $db);
	checkDBError($db);
	$customer = DBfetch_array($cust_result);
	$act_result = DBquery("select * from Accounts where CustomerID=$CustomerID", $db);
	checkDBError($db);
	$accounts = DBfetch_array($act_result);
	$pay_result = DBquery("select * from Payments where CustomerID=$CustomerID ORDER BY PaymentID DESC", $db);
	checkDBError($db);
	$payments = DBfetch_array($pay_result);
	$inv_result = DBquery("select * from Invoices where CustomerID=$CustomerID ORDER BY InvoiceID DESC", $db);
	checkDBError($db);
	$invoices = DBfetch_array($inv_result);

	if(isset($payments)) {
		do {
			$pay_total += $payments["Amount"];
			if($pay_count < 3) {
				$payset .= "\t".$payments["DatePaid"]." ".$payments["Type"]." ".sprintf("%.4f",$payments["Amount"])."\n";
			}
			$pay_count++;
		} while($payments = DBfetch_array($pay_result));
	}
	if(isset($invoices)) {
		do {
			$inv_total += $invoices["Amount"];
			if($inv_count < 3) {
				$invset .= "\t".$invoices["DateBilled"]." ".$invoices["Description"]." ".sprintf("%.4f",$invoices["Amount"])."\n";
			}
			$inv_count++;
		} while($invoices = DBfetch_array($inv_result));
	}
	if(isset($accounts)) {
		do {
			$pid = $accounts["PackageGroupID"];
			$result = DBquery("SELECT * from PackageGroup where PackageGroupID=$pid", $db);
			$pack_row = DBfetch_array($result);
			$actset .= "$lAccountID: ".$accounts["AccountID"].":\n\t$lDescription: ".$pack_row["Description"]."\n\t$lDateOpened: ".$accounts["DateOpened"];
			if($accounts["Domain"] != "") {
				$actset .= "\n\t$lDomain: ".$accounts["Domain"];
			}
			if($accounts["DateClosed"] != "0000-00-00") {
				$actset .= "\n\t$lDateClosed: ".$accounts["DateClosed"];
			}
			$actset .= "\n";
		} while($accounts = DBfetch_array($act_result));
	}
	if($customer["Email"]) {
		$grandtotal = $pay_total - $inv_total;
		if($grandtotal < 0) {
			$grandtotal = abs($grandtotal);
		} else {
			$grandtotal =	"-".$grandtotal;
		} 
		$fullname = $customer["First"]." ".$customer["Mid"]." ".$customer["Last"];
		$message = $config["BillHeader"]."\n\n"; 
		if($customer["Company"] != "") {
			$message .= $customer["Company"]."\n";
		}
		$message .= $fullname."\n";
		$message .= $customer["Address"]."\n";
		$message .= $customer["City"]." ".$customer["State"]." ".$customer["Zip"]."\n\n\n";
		$message .= "$lAccounts:\n";
		$message .= $actset."\n";
		$message .= "$lRecentPayments:\n";
		$message .= $payset."\n";
		$message .= "$lRecentInvoices:\n";
		$message .= $invset."\n";
		if ( $grandtotal > 0 ) {
			$message .= "$lTotalOwed:\n";
			$message .= sprintf("\t$lCurrency %.2f", $grandtotal)."\n";
		} else {
			$message .= "$lTotalWeOwe:\n";
			$message .= sprintf("\t$lCurrency %.2f", $grandtotal)."\n";
		}
		$message .= $config["BillFooter"]; 
		mail($customer["Email"], $config["BillSubject"], $message, "From: ".$config["BillFromAddress"]."\nReply-To:".$config["BillReplyAddress"]."\nX-Mailer: PHP/".phpversion()."\nBcc: ".$config["BillBcc"]);
		beginPrettyTable(1, $lSuccess);
		echo "<tr><td><div class='data'>";
		echo "$lBillSent [$fullname - ".$customer["Email"]."], return to <a href='showprofile.php?CustomerID=$CustomerID'>$lCustomer $CustomerID</a>.";
		echo "</div></td></tr>";
		endPrettyTable();
	}
} else {
	echo "Sending bills requires a CustomerID";
}

endDocument();
?>
