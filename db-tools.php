<?

###############################################################################
# DB TOOLS
###############################################################################

# this function helps the case-insensitive databases become a little sensitive
# to the field names.
function db_tools_fixfields($row) {
	$DBfieldnames = array (
		"accounts"	=>	"Accounts",
		"accountid"	=>	"AccountID",
		"customerid"	=>	"CustomerID",
		"username"	=>	"Username",
		"dateopened"	=>	"DateOpened",
		"dateclosed"	=>	"DateClosed",
		"status"	=>	"Status",
		"packagegroupid"=>	"PackageGroupID",
		"domain"	=>	"Domain",
		"configuration" =>	"Configuration",
		"version"	=>	"Version",
		"language"	=>	"Language",
		"searchbar"	=>	"SearchBar",
		"name"		=>	"Name",
		"taxrate"	=>	"TaxRate",
		"billfromaddress"=>	"BillFromAddress",
		"billreplyaddress"=>	"BillReplyAddress",
		"billsubject"	=>	"BillSubject",
		"billheader"	=>	"BillHeader",
		"billfooter"	=>	"BillFooter",
		"ticketnotifier"=>	"TicketNotifier",
		"hotticket"	=>	"HotTicket",
		"billbcc"	=>	"BillBcc",
		"currencyafter"	=>	"CurrencyAfter",
		"contactid"	=>	"ContactID",
		"customerid"	=>	"CustomerID",
		"first"		=>	"First",
		"mid" 		=>	"Mid",
		"last" 		=>	"Last",
		"phone" 	=>	"Phone",
		"mobile" 	=>	"Mobile",
		"email" 	=>	"Email",
		"notes" 	=>	"Notes",
		"address" 	=>	"Address",
		"telephone" 	=>	"Telephone",
		"fax"		=>	"Fax",
		"city"		=>	"City",
		"state"		=>	"State",
		"zip"		=>	"Zip",
		"balance"	=>	"Balance",
		"ccnumber"	=>	"CCNumber",
		"ccname"	=>	"CCName",
		"password"	=>	"Password",
		"company"	=>	"Company",
		"ccexpire"	=>	"CCExpire",
		"country"	=>	"Country",
		"invoiceid"	=>	"InvoiceID",
		"description"	=>	"Description",
		"datebilled"	=>	"DateBilled",
		"lastdatebilled"=>	"LastDateBilled",
		"amount"	=>	"Amount",
		"newsid"	=>	"NewsID",
		"title"		=>	"Title",
		"content"	=>	"Content",
		"posteddate"	=>	"PostedDate",
		"poster"	=>	"Poster",
		"noteid"	=>	"NoteID",
		"note"		=>	"Note",
		"packageid"	=>	"PackageID",
		"resourceid"	=>	"ResourceID",
		"charged"	=>	"Charged",
		"paymentid"	=>	"PaymentID",
		"datepaid"	=>	"DatePaid",
		"type"		=>	"Type",
		"number"	=>	"Number",
		"price"		=>	"Price",
		"ticketworkid"	=>	"TicketWorkID",
		"ticketid"	=>	"TicketID",
		"opendate"	=>	"OpenDate",
		"opentime"	=>	"OpenTime",
		"closedate"	=>	"CloseDate",
		"closetime"	=>	"CloseTime",
		"opener"	=>	"Opener",
		"billable"	=>	"Billable",
		"billed"	=>	"Billed",
		"userid"	=>	"UserID",
		"realname"	=>	"RealName",
		"admin"		=>	"Admin"
	);

  reset($row); $ct=count($row);

  # add appropriate keys to the row to allow gcdb to work with case-insensive
  # databases.

  #echo "<pre>";
  for($index=0;$index<$ct;$index++) {
    $key=key($row); $value=$row[$key];
    #echo "$index : $key = $value <br>";
    if (isset($DBfieldnames[$key])) {
       $nk=$DBfieldnames[$key];
       #echo "Will translate $key to $nk <br>";
       $row[$nk]=$value;
    }
    next($row);
  }

  return $row;
}
