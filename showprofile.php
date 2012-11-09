<?
# Show Profile shows all the Customer Info, Invoices, Payments, Tickets,
# and a balance.
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lCustomerProfile, $sess_user);

$db = getDBConnection(); 
if ($CustomerID) {
	if(intval($CustomerID) == 0) {
		beginPrettyTable("2", "Customer");
		echo "$CustomerID is not a valid Customer ID.\n";
		endPrettyTable();
		endDocument();
		die();
	}
	
	$customer_result = DBquery("SELECT * from Customers where CustomerID=$CustomerID", $db);
	checkDBError($db);
	$customer_row = DBfetch_array($customer_result);
	# Check to see if this person exists
	if($customer_row == "") {
		beginPrettyTable("2", "Customer");
		die ("Customer $CustomerID not found.\n");
		endPrettyTable();
	}
	$contact_result = DBquery("SELECT * FROM Contacts WHERE CustomerID=$CustomerID", $db);
	checkDBError($db);
	$contact_num = DBnum_rows($contact_result);
	
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

	$note_result = DBquery("SELECT * FROM Notes WHERE CustomerID=$CustomerID ORDER BY NoteID DESC", $db);
	checkDBError($db);
	$note_row = DBfetch_array($note_result);

	$config_result = DBquery("SELECT * FROM Configuration", $db);
	checkDBError($db);
	$config_row = DBfetch_array($config_result);

	echo "<table cellpadding=0 cellspacing=0 border=0>\n";
	if($config_row["SearchBar"] == "On") {
		echo "<tr>\n";
		echo " <td align=left colspan=2>\n";
		searchBar();
		echo " </td>\n";
		echo "</tr>\n";
		echo "<tr><td><br></td></tr>\n";
	}
	echo " <tr>\n  <td width=24 valign=top>\n";
	echo "<table width=24 cellpadding=0 cellspacing=0 border=0>\n <tr>\n  <td align='left' valign='top'>\n";

	beginPrettyTable("1");
	printf("<tr><td><a href='edit.php?CustomerID=%s'><img src='images/edit_customer.gif' width=24 height=24 border=0 alt='$lEditCustomer'></a></td></tr>\n", $customer_row["CustomerID"]);
	printf("<tr><td><hr color='black' width=24></td></tr>\n");
	printf("<tr><td><a href='enteraccount.php?CustomerID=%s'><img src='images/add_account.gif' width=24 height=24 border=0 alt='$lEnterAccount'></a></td></tr>\n", $customer_row["CustomerID"]);
	printf("<tr><td><a href='entercontact.php?CustomerID=%s'><img src='images/add_contact.gif' width=24 height=24 border=0 alt='$lEnterContact'></a></td></tr>\n", $customer_row["CustomerID"]);
	printf("<tr><td><a href='enterpayment.php?CustomerID=%s'><img src='images/add_payment.gif' width=24 height=24 border=0 alt='$lEnterPayment'></a></td></tr>\n", $customer_row["CustomerID"]);
	printf("<tr><td><a href='enterinvoice.php?CustomerID=%s'><img src='images/add_invoice.gif' width=24 height=24 border=0 alt='$lEnterInvoice'></a></td></tr>\n", $customer_row["CustomerID"]);
	printf("<tr><td><a href='enterticket.php?CustomerID=%s'><img src='images/add_ticket.gif' width=24 height=24 border=0 alt='$lEnterTicket'></a></td></tr>\n", $customer_row["CustomerID"]);
	printf("<tr><td><a href='enternote.php?CustomerID=%s'><img src='images/add_note.gif' width=24 height=24 border=0 alt='$lEnterNote'></a></td></tr>\n", $customer_row["CustomerID"]);
	echo "<tr><td><hr color='black' width='24'></td></tr>\n";
	printf("<tr><td><a href='confirm.php?action=sendbill&CustomerID=%s'><img src='images/sendbill.gif' width=24 height=24 border=0 alt='$lSendBill'><a/></td></tr>\n", $customer_row["CustomerID"]);
	echo "<tr><td><hr color='black' width='24'></td></tr>\n";
	echo "<tr><td><a href='showprofile.php'><img src='images/list.gif' width=24 height=24 border=0 alt='List Customers'></a></td></tr>\n";
	printf("<tr><td><a href='$PHP_SELF?CustomerID=%s'><img src='images/refresh.gif' width=24 height=24 border=0 alt='Refresh'></a></td></tr>\n", $customer_row["CustomerID"]);
	echo "<tr><td><hr color='black' width='24'></td></tr>\n";
	echo "<tr><td><a href='index.php'><img src='images/logout.gif' width=24 height=24 border=0 alt='Logout'></a></td></tr>\n";
	endPrettyTable();

	echo "  </td>  <td>&nbsp;&nbsp;&nbsp;&nbsp;</td> </tr>  </table>\n";

	echo "  </td> <td valign='top'>\n";

	echo "<table cellpadding=5 cellspacing=0 border=0>\n";
	echo " <tr>\n";
	echo "  <td valign=top>\n";

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
	# had to do this anywhere but here, so I won't write a function for it
	echo "<tr><td>&nbsp</td><td><div class='data'>$second_address</div></td></tr>\n";
	if($customer_row["Country"] != "") {
		makeStaticField($lCountry, $customer_row["Country"]);
	}
	makeStaticField($lPhone, $customer_row["Telephone"]);
	if(strlen($customer_row["Fax"]) >= 5) {
		makeStaticField($lFax, $customer_row["Fax"]);
	}
	if(strlen($customer_row["CCNumber"]) >= 16) {
		makeStaticField($lCCNumber, $customer_row["CCNumber"]);
	}
	makeStaticField($lEmail, "<a href=\"mailto:".$customer_row["Email"]."\" class='mailto'>".$customer_row["Email"]."</a>");
	if($contact_num > 0) {
		makeStaticField($lContacts, "<a href='showcontacts.php?CustomerID=".$customer_row["CustomerID"]."'>$contact_num $lTotal</a>");
	}
	endPrettyTable();

	echo ("  </td>\n");
	echo ("  <td valign=top>\n");

	$affectedcount = dbnum_rows($account_result);
	beginPrettyTable("6", "$affectedcount $lAccounts");
	beginBorderedTable("6");
	if ($account_row) {
		$class = "odd";
		echo ("    <tr>\n");
		echo ("     <td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lDescription</b></td> <td><b>$lStatus</b></td>  <td><b>$lCharged</b></td> <td><b>$lDomain</b></td> <td><b>$lActions</b></td>\n");
		echo ("    </tr>\n");
		do {
			$pack_id = $account_row["PackageGroupID"];
			$pack_result = DBquery("SELECT * FROM PackageGroup WHERE PackageGroupID=$pack_id", $db);
			$pack_row = DBfetch_array($pack_result);
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			$charged = translate_interval($pack_row["Charged"]);
			printf ("<tr class='$class'> <td align='center'><a href='showaccounts.php?AccountID=%s&CustomerID=%s'>%s</a></td> <td><a href='showpackage.php?PackageGroupID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%s</td> <td align='center'><a href='edit.php?AccountID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Account'></a><a href='delete.php?AccountID=%s&CustomerID=%s'><img src='images/delete.gif' border=0></a></td></tr>\n", $account_row["AccountID"], $account_row["CustomerID"], $account_row["AccountID"], $pack_id, $pack_row["Description"], $account_row["Status"], $charged, $account_row["Domain"], $account_row["AccountID"], $account_row["AccountID"], $account_row["CustomerID"]);
		} while($account_row = DBfetch_array($account_result));
	} else {
		echo ("    <tr><td>$lNoAccountsFound<td></tr>\n");
	}
	endBorderedTable();
	endPrettyTable();

	echo ("  </td>\n");
	echo (" </tr>\n");
	echo (" <tr>\n");
	echo ("  <td valign=top>\n");
	
	$payment_count = dbnum_rows($payment_result);
	beginPrettyTable("5", "$payment_count $lPayments");
	beginBorderedTable("5");
	if ($payment_row) {
		$class = "odd";
		echo ("    <tr>\n");
		echo ("     <td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lDatePaid</b></td> <td><b>$lType</b></td> <td><b>$lAmount</b></td>  <td><b>$lActions</b></td>\n");
		echo ("    </tr>\n");
		# Unless our display mode is set to long, lets only display the
		# most recent payments.
		if(($payment_count > 5) && ($mode != "long")) {
			$pay_abridged = 1;
			do {
				if($pay_counter < 5) {
					if($class == "odd") { $class = "even"; } else { $class = "odd"; }
					printf("<tr class='$class'><td align='center'><a href='showpayments.php?PaymentID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%.4f</td> <td align='center'><a href='edit.php?PaymentID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Payment'></a> <a href='delete.php?PaymentID=%s&CustomerID=%s'><img src='images/delete.gif' border=0 alt='Delete this Payment'></a></td>  </tr>\n", $payment_row["PaymentID"], $payment_row["PaymentID"], $payment_row["DatePaid"], $payment_row["Type"], $payment_row["Amount"], $payment_row["PaymentID"], $payment_row["PaymentID"], $payment_row["CustomerID"]); 
				}
				$pay_counter++;
				$payment_total += $payment_row["Amount"];
			} while($payment_row = DBfetch_array($payment_result)); 
		} else {
			do {
				if($class == "odd") { $class = "even"; } else { $class = "odd"; }
				printf("    <tr class='$class'><td align='center'><a href='showpayments.php?PaymentID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%.4f</td> <td align='center'><a href='edit.php?PaymentID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Payment'></a> <a href='delete.php?PaymentID=%s&CustomerID=%s'><img src='images/delete.gif' border=0 alt='Delete this Payment'></a></td>  </tr>\n", $payment_row["PaymentID"], $payment_row["PaymentID"], $payment_row["DatePaid"], $payment_row["Type"], $payment_row["Amount"], $payment_row["PaymentID"], $payment_row["PaymentID"], $payment_row["CustomerID"]); 
				$payment_total += $payment_row["Amount"];
			} while($payment_row = DBfetch_array($payment_result));
		}
		printf("<tr><td colspan=5 align=right><b>$lTotal: %.4f</b></td></tr>\n", $payment_total);
	} else {
		echo ("<tr><td>$lNoPaymentsFound</td></tr>\n");
	}
	endBorderedTable();
	endPrettyTable();
	echo ("  </td>\n");
	echo ("  <td valign=top>\n");

	$invoice_count = dbnum_rows($invoice_result);
	beginPrettyTable("5", "$invoice_count $lInvoices");
	beginBorderedTable("5");
	if ($invoice_row) {
		$class = "odd";
		echo ("<tr>\n");
		echo (" <td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lDescription</b></td> <td><b>$lDateBilled</b></td> <td><b>$lAmount</b></td>  <td><b>$lActions</b></td>\n");
		echo ("</tr>\n");
		# Unless our display mode is set to long, lets only display the
		# most recent payments.
		if(($invoice_count > 5) && ($mode != "long")) {
			$inv_abridged = 1;
			do {
				if($inv_counter < 5) {
					if($class == "odd") { $class = "even"; } else { $class = "odd"; }
					printf("<tr class='$class'><td align='center'><a href='showinvoices.php?InvoiceID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%.4f</td> <td align='center'><a href='edit.php?InvoiceID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Invoice'></a> <a href='delete.php?InvoiceID=%s&CustomerID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete this Invoice'></a></td>\n</tr>\n", $invoice_row["InvoiceID"], $invoice_row["InvoiceID"], $invoice_row["Description"], $invoice_row["DateBilled"], $invoice_row["Amount"], $invoice_row["InvoiceID"], $invoice_row["InvoiceID"], $invoice_row["CustomerID"]); 
				}
				$inv_counter++;
				$invoice_total += $invoice_row["Amount"];
			} while($invoice_row = DBfetch_array($invoice_result));
		} else {
			do {
				if($class == "odd") { $class = "even"; } else { $class = "odd"; }
				printf("<tr class='$class'><td align='center'><a href='showinvoices.php?InvoiceID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%.4f</td> <td align='center'><a href='edit.php?InvoiceID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Invoice'></a> <a href='delete.php?InvoiceID=%s&CustomerID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete this Invoice'></a></td>\n</tr>\n", $invoice_row["InvoiceID"], $invoice_row["InvoiceID"], $invoice_row["Description"], $invoice_row["DateBilled"], $invoice_row["Amount"], $invoice_row["InvoiceID"], $invoice_row["InvoiceID"], $invoice_row["CustomerID"]); 
				$invoice_total += $invoice_row["Amount"];
			} while($invoice_row = DBfetch_array($invoice_result));
		}
		printf("<tr><td colspan=5 align=right><b>$lTotal: %.4f</b></td></tr>\n", $invoice_total);
	} else {
		echo ("<tr><td>$lNoInvoicesFound</td></tr>\n");
	}
	endBorderedTable();
	endPrettyTable();
	echo ("  </td>\n");
	echo (" </tr>\n");
	echo (" <tr>\n");
	echo ("  <td colspan=2>\n");
		
	$balance = $payment_total - $invoice_total;
	$balance = sprintf("%.4f", $balance);
	$balance_result = DBquery("UPDATE Customers SET Balance=$balance where CustomerID=$CustomerID", $db);
	if ($balance >= 0) { $bal_color = "positive"; } else { $bal_color = "negative"; } 
	beginPrettyTable("1", "$lBalance");
	echo "<tr> <td width=100 align=center><div class=$bal_color>";
	if($config_row["CurrencyAfter"] == "Off") {
		echo $lCurrency;
	}
	if($balance < 0) {
		$disp_amount = abs($balance);
		echo "<a href='enterpayment.php?CustomerID=$CustomerID&amount=$disp_amount' class='$bal_color'>$balance</a>";
	} else {
		echo $balance;
	}
	if($config_row["CurrencyAfter"] == "On") {
		echo $lCurrency;
	}
	echo "&nbsp;&nbsp;&nbsp;</div></td> </tr>\n";
	endPrettyTable();
	echo " </td>";
	echo "</tr>";
	echo "<tr><td>";
	
	$ticketcount = dbnum_rows($ticket_result);
	beginPrettyTable("4", "$ticketcount $lTickets");
	beginBorderedTable("4");
	if ($ticket_row) {
		$class = "odd";
		echo ("<tr>\n");
		echo (" <td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lStatus</b></td> <td><b>$lDateOpened</b></td> <td><b>$lActions</b></td>\n");
		echo ("</tr>\n");
		do {
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			printf("<tr class='$class'><td align='center'><a href='showtickets.php?TicketID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td align='center'><a href=''><img src='images/work_start.gif' border=0></a><a href='edit.php?TicketID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Ticket'></a> <a href='delete.php?TicketID=%s&CustomerID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete this Ticket'></a></td>\n</tr>\n", $ticket_row["TicketID"], $ticket_row["TicketID"], $ticket_row["Status"], $ticket_row["OpenDate"], $ticket_row["TicketID"], $ticket_row["TicketID"], $ticket_row["CustomerID"]); 
		} while ($ticket_row = DBfetch_array($ticket_result));
	} else {
		echo ("<tr><td>$lNoTicketsFound</td></tr>\n");
	}
	endBorderedTable();
	endPrettyTable();
	echo "</td><td valign='top'>";
	$notecount = dbnum_rows($note_result);
	beginPrettyTable("5", "$notecount $lNotes");
	beginBorderedTable("5");
	if ($note_row) {
		$class = "odd";
		echo ("<tr>\n");
		echo (" <td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lNotes</b></td> <td><b>$lDatePosted</b></td> <td><b>$lPoster</b></td> <td><b>$lActions</b></td>\n");
		echo ("</tr>\n");
		do {
			if(strlen($note_row["Note"]) > 30) {
				$note = sprintf("%s...", substr($note_row["Note"], 0, 27));
			} else {
				$note = $note_row["Note"];
			}
				
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			printf("<tr class='$class'><td align='center'><a href='shownotes.php?NoteID=%s'>%s</a></td> <td>%s</td> <td>%s</td> <td>%s</td> <td align='center'><a href='edit.php?NoteID=%s'><img src='images/edit.gif' height=24 width=24 border=0 alt='Edit this Note'></a> <a href='delete.php?NoteID=%s&CustomerID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete this Note'></a></td>\n</tr>\n", $note_row["NoteID"], $note_row["NoteID"], $note, $note_row["PostedDate"], $note_row["Poster"], $note_row["NoteID"], $note_row["NoteID"], $note_row["CustomerID"]); 
		} while ($note_row = DBfetch_array($note_result));
	} else {
		echo ("<tr><td>$lNoNotesFound</td></tr>\n");
	}
	endBorderedTable();
	endPrettyTable();
	echo "</td></tr></table>";
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
		echo "<div class='darkdata'><a href='showprofile.php?CustomerID=$CustomerID&mode=long'>$abridged</a> $lAbridged</div>\n";
	}
	echo ("</td>\n</tr>\n</table>");
} else {
	$lastditch_result = DBquery("select * from Customers order by CustomerID", $db);
	if ($lastditch_row = DBfetch_array($lastditch_result)) {
		beginPrettyTable("6", "$lCustomers");
		beginBorderedTable("6");
		echo ("<tr>\n");
		echo (" <td><b>&nbsp;$lID&nbsp;</b></td> <td><b>$lName</b></td> <td><b>$lCompany</b></td><td><b>$lAddress</b></td> <td><b>$lEmail</b></td>\n <td><b>$lBalance</b></td> <td><b>$lActions</b></td>\n </tr>\n");
		do {
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			if($lastditch_row["Balance"] >= 0) { $bal_class = "positive"; } else { $bal_class = "negative"; }
			printf("<tr class='$class'>\n<td align='center'><a href='%s?CustomerID=%s'>%s</td> <td>%s %s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td><div class='$bal_class'>%.4f</div></td> <td align='center'><a href='%s?CustomerID=%s'><img src='images/edit.gif' width=24 height=24 border=0></a><a href='confirm.php?action=deletecustomer&CustomerID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='$lDelete'></a></td></tr>\n", $PHP_SELF, $lastditch_row["CustomerID"], $lastditch_row["CustomerID"], $lastditch_row["First"], $lastditch_row["Last"], $lastditch_row["Company"], $lastditch_row["Address"], $lastditch_row["Email"], $lastditch_row["Balance"], $PHP_SELF,$lastditch_row["CustomerID"],$lastditch_row["CustomerID"]); 
		} while ($lastditch_row = DBfetch_array($lastditch_result));
		endPrettyTable();
		endBorderedTable();
	} else {
		echo ("<tr><td>$lNoProfilesFound...</td></tr>\n"); 
	}
}
endDocument();
?>
