<?
# Show Profile shows all the Customer Info, Invoices, Payments, Tickets,
# and a balance.
session_start();

$CustomerID = $sess_customer;

require("gcdb.php");
require("security/public.php");

beginDocument($lCustomerProfile, $sess_customer);

$db = getDBConnection(); 
if ($CustomerID) {
	if(intval($CustomerID) == 0) {
		beginPrettyTable("2", $lCustomer);
		die ("$lNoRecords ($CustomerID)\n");
		endPrettyTable();
	}

	$customer_result = DBquery("SELECT * from Customers where CustomerID=$CustomerID", $db);
	checkDBError($db);
	$customer_row = DBfetch_array($customer_result);
	# Check to see if this person exists
	if($customer_row == "") {
		beginPrettyTable("2", $lCustomer);
		die ("$lNoRecords ($CustomerID).\n");
		endPrettyTable();
	}
	
	$account_result = DBquery("SELECT * FROM Accounts WHERE CustomerID=$CustomerID", $db);
	checkDBError($db);
	$account_row = DBfetch_array($account_result);

	$payment_result = DBquery("SELECT * FROM Payments WHERE CustomerID=$CustomerID ORDER BY PaymentID DESC", $db);
	checkDBError($db);
	$payment_row = DBfetch_array($payment_result);

	$invoice_result = DBquery("SELECT * FROM Invoices WHERE CustomerID=$CustomerID ORDER BY InvoiceID DESC", $db);
	checkDBError($db);
	$invoice_row = DBfetch_array($invoice_result);

	$ticket_result = DBquery("SELECT * FROM Tickets WHERE CustomerID=$CustomerID ORDER BY TicketID DESC", $db);
	checkDBError($db);
	$ticket_row = DBfetch_array($ticket_result);

	$config_result = DBquery("SELECT * FROM Configuration", $db);
	checkDBError($db);
	$config_row = DBfetch_array($config_result);

	echo "<table cellpadding=0 cellspacing=0 border=0>\n";
	echo " <tr>\n  <td width=24 valign=top>\n";
	echo "<table width=24 cellpadding=0 cellspacing=0 border=0>\n <tr>\n  <td align='left' valign='top'>\n";

	beginPrettyTable("1");
	printf("<tr><td><a href='enterticket.php?CustomerID=%s'><img src='images/add_ticket.gif' width=24 height=24 border=0 alt='$lEnterTicket'></a></td></tr>\n", $CustomerID);
	echo "<tr><td><hr color='black' width='24'></td></tr>\n";
	printf("<tr><td><a href='$PHP_SELF?CustomerID=%s'><img src='images/refresh.gif' width=24 height=24 border=0 alt='Refresh'></a></td></tr>\n", $customer_row["CustomerID"]);
	echo "<tr><td><hr color='black' width='24'></td></tr>\n";
	echo "<tr><td><a href='index.php'><img src='images/logout.gif' width=24 height=24 border=0 alt='Logout'></a></td></tr>\n";
	endPrettyTable();

	echo "  </td>  <td>&nbsp;&nbsp;&nbsp;&nbsp;</td> </tr>  </table>\n";

	echo "  </td> <td valign='top'>\n";

	echo ("<table cellpadding=5 cellspacing=0 border=0>\n");
	echo (" <tr>\n");
	echo ("  <td valign=top>\n");

	$name = $customer_row["First"]." ";
	if($customer_row["Mid"] != "") {
		$name = $name.$customer_row["Mid"].". ";
	}
	$name = $name.$customer_row["Last"];
	$customer_thing = $customer_row["CustomerID"]." : ".$name;
	$second_address = $customer_row["City"].", ".$customer_row["State"]."  ".$customer_row["Zip"];
	beginPrettyTable("2", $customer_thing);
	if($customer_row["Company"] != "") {
		makeStaticField($lCompany, $customer_row["Company"]);
	}
	makeStaticField($lAddress, $customer_row["Address"]);
	# Silly little hack to add a second line to a info display, I haven't
	# had to do this anywhere but here, so I wront write a function for it
	echo "<tr><td>&nbsp</td><td><div class='data'>$second_address</div></td></tr>\n";
	makeStaticField($lPhone, $customer_row["Telephone"]);
	if(strlen($customer_row["Fax"]) >= 5) {
		makeStaticField($lFax, $customer_row["Fax"]);
	}
	if(strlen($customer_row["CCNumber"]) >= 16) {
		makeStaticField($lCCNumber, $customer_row["CCNumber"]);
	}
	makeStaticField($lEmail, "<a href=\"mailto:".$customer_row["Email"]."\" class='mailto'>".$customer_row["Email"]."</a>");
	endPrettyTable();

	echo ("  </td>\n");
	echo ("  <td valign=top>\n");

	$affectedcount = DBnum_rows($account_result);
	beginPrettyTable("5", "$affectedcount $lAccounts");
	beginBorderedTable("5");
	if ($account_row) {
		echo ("<tr><td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lDescription</b></td> <td><b>$lStatus</b></td>  <td><b>$lCharged</b></td></tr>\n");
		do {
			$pack_id = $account_row["PackageGroupID"];
			$pack_result = DBquery("SELECT * FROM PackageGroup WHERE PackageGroupID=$pack_id", $db);
			$pack_row = DBfetch_array($pack_result);
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			$charged = translate_interval($pack_row["Charged"]);
	  		printf ("<tr class='$class'> <td align='center'><a href='showaccounts.php?AccountID=%s&CustomerID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%.2f</td> </tr>\n", $account_row["AccountID"], $account_row["CustomerID"], $account_row["AccountID"], $pack_row["Description"], $account_row["Status"], $charged, $total_price);
	  	} while($account_row = DBfetch_array($account_result));
		$class = "";
	} else {
		echo ("<tr><td>$lNoAccountsFound<td></tr>\n");
	}
	endBorderedTable();
	endPrettyTable();

	echo ("  </td>\n");
	echo (" </tr>\n");
	echo (" <tr>\n");
	echo ("  <td valign=top>\n");
	
	$payment_count = DBnum_rows($payment_result);
	beginPrettyTable("4", "$payment_count $lPayments");
	beginBorderedTable("4");
	if ($payment_row) {
		echo ("<tr><td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lDatePaid</b></td> <td><b>$lType</b></td> <td><b>$lAmount</b></td></tr>\n");
		# Unless our display mode is set to long, lets only display the
		# most recent payments.
		if(($payment_count > 5) && ($mode != "long")) {
			$pay_abridged = 1;
			do {
				if($pay_counter < 5) {
					if($class == "odd") { $class = "even"; } else { $class = "odd"; }
					printf("<tr class='$class'><td align='center'><a href='showpayments.php?PaymentID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%.4f</td> </tr>\n", $payment_row["PaymentID"], $payment_row["PaymentID"], $payment_row["DatePaid"], $payment_row["Type"], $payment_row["Amount"]); 
				}
				$pay_counter++;
				$payment_total += $payment_row["Amount"];
			} while($payment_row = DBfetch_array($payment_result)); 
		} else {
			do {
				if($class == "odd") { $class = "even"; } else { $class = "odd"; }
				printf("<tr class='$class'><td align='center'><a href='showpayments.php?PaymentID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%.4f</td> </tr>\n", $payment_row["PaymentID"], $payment_row["PaymentID"], $payment_row["DatePaid"], $payment_row["Type"], $payment_row["Amount"]); 
				$payment_total += $payment_row["Amount"];
			} while($payment_row = DBfetch_array($payment_result));
			$class = "";
		}
		printf("<tr><td colspan=5 align=right><b>$lTotal: %.4f</b></td></tr>\n", $payment_total);
	} else {
		echo ("<tr><td>$lNoPaymentsFound</td></tr>\n");
	}
	endBorderedTable();
	endPrettyTable();
	echo ("</td>\n");
	echo ("<td valign=top>\n");

	$invoice_count = DBnum_rows($invoice_result);
	beginPrettyTable("4", "$invoice_count $lInvoices");
	beginBorderedTable("4");
	if ($invoice_row) {
		echo ("<tr><td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lDescription</b></td> <td><b>$lDateBilled</b></td> <td><b>$lAmount</b></td></tr>\n");
		# Unless our display mode is set to long, lets only display the
		# most recent payments.
		if(($invoice_count > 5) && ($mode != "long")) {
			$inv_abridged = 1;
			do {
				if($inv_counter < 5) {
					if($class == "odd") { $class = "even"; } else { $class = "odd"; }
					printf("<tr class='$class'><td align='center'><a href='showinvoices.php?InvoiceID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%.4f</td></tr>\n", $invoice_row["InvoiceID"], $invoice_row["InvoiceID"], $invoice_row["Description"], $invoice_row["DateBilled"], $invoice_row["Amount"]); 
				}
				$inv_counter++;
				$invoice_total += $invoice_row["Amount"];
			} while($invoice_row = DBfetch_array($invoice_result));
		} else {
			do {
				if($class == "odd") { $class = "even"; } else { $class = "odd"; }
				printf("<tr class='$class'><td align='center'><a href='showinvoices.php?InvoiceID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%.4f</td></tr>\n", $invoice_row["InvoiceID"], $invoice_row["InvoiceID"], $invoice_row["Description"], $invoice_row["DateBilled"], $invoice_row["Amount"]); 
				$invoice_total += $invoice_row["Amount"];
			} while($invoice_row = DBfetch_array($invoice_result));
			$class = "";
		}
		printf("<tr><td colspan=5 align=right><b>$lTotal: %.4f</b></td></tr>\n", $invoice_total);
	} else {
		echo ("<tr><td>$lNoInvoicesFound</td></tr>\n");
	}
	endBorderedTable();
	endPrettyTable();
	echo ("</td></tr>\n");
	echo ("<tr><td colspan=2>\n");
	
	$balance = $payment_total - $invoice_total;
	$balance = sprintf("%.4f", $balance);
	$balance_result = DBquery("UPDATE Customers SET Balance=$balance where CustomerID=$CustomerID", $db);
	if ($balance >= 0) { $bal_color = "positive"; } else { $bal_color = "negative"; } 
	beginPrettyTable("1", "$lBalance");
  	echo("<tr> <td width=100 align=center><div class=$bal_color>");
	if($config_row["CurrencyAfter"] == "Off") {
		echo $lCurrency;
	}
	echo $balance;
	if($config_row["CurrencyAfter"] == "On") {
		echo $lCurrency;
	}
	endPrettyTable();

	echo "</td></tr><tr><td colspan=2>\n";
	
	$ticketcount = DBnum_rows($ticket_result);
	beginPrettyTable("3", "$ticketcount $lTickets");
	beginBorderedTable("3");
	if ($ticket_row) {
		echo ("<tr><td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lStatus</b></td> <td><b>$lDateOpened</b></td></tr>\n");
		do {
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			printf("<tr class='$class'><td align='center'><a href='showtickets.php?TicketID=%s'>%s</a></td> <td>%s</td> <td>%s</td> </tr>\n", $ticket_row["TicketID"], $ticket_row["TicketID"], $ticket_row["Status"], $ticket_row["OpenDate"]); 
		} while ($ticket_row = DBfetch_array($ticket_result));
		$class = "";
	} else {
		echo ("$lNoTicketsFound\n");
	}
	endBorderedTable();
	endPrettyTable();
	echo "<br>\n";
	if($pay_abridged == 1) {
		if($inv_abridged == 1) {
			$abridged = "$lPayments and $lInvoices";
		} else {
			$abridged = "$lPayments";
		}
	} else if($inv_abridged == 1) {
		$abridged = "$lInvoices";
	}
	if($abridged != "") {
		echo "<div class='data'><a href='publicprofile.php?CustomerID=$CustomerID&mode=long'>$abridged</a> $lAbridged</div>\n";
	}
	echo ("</td></tr></table>");
}
endDocument();
?>
