#!/usr/bin/perl
use strict;
use DBI;

# Database Parameters
# These will probably get moved back to the Configuration table at some point
my $db_host = "localhost";
my $db_port = "3306";
my $db = "gcdb";
my $db_user = "root";
my $db_pass = "hilandresbobo";

##### If you want to actually add invoices, turn this on.
my $ADD_INVOICES = "Off";
#####

# Important Billing Parameters
my $anniversary = "Off";
my $same_month = "On";

my $log;
my $logfile = shift;

if($logfile ne "") {
	$log = "On";
	open LOGFILE, ">$logfile";
}

# Initialize counters, sentinels and flags
my $bill = 0;
my $waive = 0;
my $acct_count = 0;
my $already_count = 0;
my $bill_count = 0;
my $ne_count = 0;
my $waive_count = 0;

# Declare subs
sub same_month_waive;
sub already_billed_waive;

# Connect to the billing database
my $driver = "mysql";
my $dsn = "DBI:$driver:database=$db;host=$db_host;port=$db_port";
my $dbh = DBI->connect($dsn, $db_user, $db_pass);

my $ref;
my $waive;

# Select ALL the accounts, and iterate through them
my $sth = $dbh->prepare("SELECT * from Accounts");
$sth->execute();
while($ref = $sth->fetchrow_hashref()) {
	my $tax;
	my $price;
	# Keep track of the accounts we check
	$acct_count++;

	# Reset Waive flag
	# Note, the value of waive is only for debugging purposes, to help
	# the developer locate where an account is getting waved, all that
	# matters is if it is non-zero.
	$waive = 0;

	# Get the current time info
	my @time = localtime();
	my $day = $time[3];
	my $mon = ($time[4] + 1);
	my $year = ($time[5] + 1900);

	# Fetch Package Grouping Information for this Account
	my $account_id = $ref->{"AccountID"};
	my $pack_id = $ref->{"PackageGroupID"};
	my $cust_id = $ref->{"CustomerID"};
	my $pack_grp_sth = $dbh->prepare("SELECT * FROM PackageGroup WHERE PackageGroupID=$pack_id");
	$pack_grp_sth->execute();
	my $pack_grp_ref = $pack_grp_sth->fetchrow_hashref();
	my $pack_name = $pack_grp_ref->{"Description"};
	my $when = $pack_grp_ref->{"Charged"};
	
	if($log eq "On") {
		print LOGFILE "Got $account_id, using Package $pack_id for Customer $cust_id.  Package $pack_name, charged $when\n";
	}


	my @price_uhray;
	my @tax_uhray;
	# Fetch each of the resources and build an array of the prices
	
	my $pack_sth = $dbh->prepare("SELECT * FROM Package WHERE PackageGroupID=$pack_id");
	$pack_sth->execute();
	my $pack_ref;

	my $res_sth;
	while($pack_ref = $pack_sth->fetchrow_hashref()) {
		my $res_id = $pack_ref->{"ResourceID"};
		$res_sth = $dbh->prepare("SELECT * FROM Resources WHERE ResourceID=$res_id");
		$res_sth->execute();
		my $res_ref = $res_sth->fetchrow_hashref();

		# HOOKS FOR ADDING MULTIPLIERS!!
		# Commented out is some code you could use to get a value
		# somehow, like disk space, hours, whatever.  Since theres no
		# way I can write a generic routine, you'll just have to add it.
		#
		# Get the Resource ID
		#$res_id = $res_ref->{'ResourceID'};
		# Then would do something like:
		#if($res_id == 1) { 
		#	$mult = function_I_wrote_for_disk_space();
		#	$price = $res_ref->{'Price'} * $mult;
		#}
		# For now, we'll just use a static price
		$price = $res_ref->{"Price"};
		$tax = $res_ref->{"TaxRate"};
		if($log eq "On") {
			print LOGFILE "Adding a resource with price of $price and tax of $tax.\n";
		}
		push(@price_uhray, $price);
		push(@tax_uhray, $tax);
	}

	# Close the Statement handles
	$pack_grp_sth->finish();
	$pack_sth->finish();
	$res_sth->finish();

	my $opendate = $ref->{"DateOpened"};
	my ($open_year, $open_month, $open_day) = split(/-/, $opendate);
	my $lastdate = $ref->{"LastDateBilled"};
	my ($last_year, $last_month, $last_day) = split(/-/, $lastdate);


	# If same_month is on, waive matching accounts
	if($same_month eq "On") {
		$waive = same_month_waive($open_year, $open_month, $open_day, $year, $mon);
	}
	$waive = already_billed_waive($last_month, $mon);
	if($waive == 6) {
		if($log eq "On") {
			"Already billed this one.\n";
		}
		$already_count++;
	}

	# If the account wasn't added this month, bill it.
	my $total = 0;
	if($anniversary eq "Off") {
		if(!(($year == $open_year) and ($mon == $open_month))) {
			if($when eq "monthly") { $bill = 1; }
			if(($when eq "quarterly") and ($mon%3)) { 
				$waive = 1;
			} 
			if(($when eq "biannually") and ($mon%6)) { 
				$waive = 2; 
			}
			if(($when eq "yearly") and ($mon%12)) {
				$waive = 3;
			}
			if(!($waive)) {
				my $len = scalar(@price_uhray);
				for(my $i = 0; $i < $len; $i++) {
					$tax = 0;

					$tax = ($price_uhray[$i] * $tax_uhray[$i]);
					if($log eq "On") {
						print LOGFILE "Tax is $tax ($price_uhray[$i] * $tax_uhray[$i]).\n";
					}
					$total += ($price_uhray[$i] + $tax);
					if($log eq "On") {
						print LOGFILE "Total is $total.\n";
					}
				}
				$total = sprintf("%.2f", $total);
				print "Bill for $total added to Customer $cust_id.\n"; 
				if($mon < 10) {
					$mon = sprintf("%02d", $mon);
				}
				if($day < 10) {
					$day = sprintf("%02d", $day);
				}
				if($ADD_INVOICES eq "On") {
					if($log eq "On") {
						print LOGFILE "Customer $cust_id getting a bill of $total.\n";	
					}
					my $add_invoice_sth = $dbh->prepare("INSERT INTO Invoices (CustomerID, Description, Amount, DateBilled) VALUES ($cust_id, '$pack_name', '$total', '$year-$mon-$day')");
					$add_invoice_sth->execute();
					$add_invoice_sth->finish();
					my $update_date_sth = $dbh->prepare("Update Accounts SET LastDateBilled = \"$year-$mon-$day\" WHERE AccountID=$account_id");
					$update_date_sth->execute();
					$update_date_sth->finish();
				} else {
					print "INSERT INTO Invoices (CustomerID, Description, Amount, DateBilled) VALUES ($cust_id, '$pack_name', '$total', '$year-$mon-$day')\n";
					print "Update Accounts SET LastDateBilled = \"$year-$mon-$day\" WHERE AccountID=$account_id\n";
				}
				$bill_count++;
			} else {
				$waive_count++;
			}
		} else {
			$ne_count++;
		}
	} else {
		if(($day == $open_day) or ($day == 28)) {
			if(($open_day == 29) and ($open_month == 2) and ($day == 28) and ($mon == 2)) {
				#print "Damn leap days!\n";
			}
			if($when eq "monthly") { $bill = 1; };
			if(($when eq "quarterly") and !($mon%3)) { 
				$bill = 3;
			}; 
			if(($when eq "biannually") and !($mon%6)) { 
				$bill = 6; 
			}
			if(($when eq "yearly") and !($mon%12)) {
				$bill = 12;
			}
			# Anniversary Based Intervals!
			# If we are dealing with something besides monthly
			if($bill > 1) {
				# Find how long till year-wrap
				my $dist_to_twelve = (12 - $open_month);
				# If doesn't make us year wrap
				if($bill < $dist_to_twelve) {
					# Then just add it to the open date
					if($mon != ($open_month + $bill)) {
						$waive = 1;
					}
				# If we do year wrap, then do the proper
				# math (interval - time_to_a_year)
				} else {
					# Wrap!
					if($mon != ($bill - $dist_to_twelve)) {
						$waive = 1;
					}
				}
			}
			if(!($waive) and ($last_month != $mon)) {
				my $len = scalar(@price_uhray);
				for(my $i = 0; $i < $len; $i++) {
					$tax = $price_uhray[$i] * $tax_uhray[$i];
					$total += $price_uhray[$i] + $tax;
				}
				$total = sprintf("%.2f", $total);
				print "Bill for $total added to Customer $cust_id.\n"; 
				if($mon < 10) {
					$mon = sprintf("%02d", $mon);
				}
				if($day < 10) {
					$day = sprintf("%02d", $day);
				}
				if($ADD_INVOICES eq "On") {
					if($log eq "On") {
						print LOGFILE "Customer $cust_id getting a bill of $total.\n";	
					}
					my $add_invoice_sth = $dbh->prepare("INSERT INTO Invoices (CustomerID, Description, Amount, DateBilled) VALUES ($cust_id, '$pack_name', '$total', '$year-$mon-$day')");
					$add_invoice_sth->execute();
					$add_invoice_sth->finish();
					my $update_date_sth = $dbh->prepare("Update Accounts SET LastDateBilled = \"$year-$mon-$day\" WHERE AccountID=$account_id");
					$update_date_sth->execute();
					$update_date_sth->finish();
				} else {
					print "INSERT INTO Invoices (CustomerID, Description, Amount, DateBilled) VALUES ($cust_id, '$pack_name', '$total', '$year-$mon-$day')\n";
					print "Update Accounts SET LastDateBilled = \"$year-$mon-$day\" WHERE AccountID=$account_id\n";
				}
				$bill_count++;
			}
		} else {
			$ne_count++;
		}
	}
}

# Check same month waiving
sub same_month_waive {
	my $local_waive = 0;
	my ($open_year, $open_month, $open_day, $year, $mon) = @_;
	if($open_day >= 25) {
		if((($mon - 1) == $open_month) and ($open_year == $year)) {
			$local_waive = 4;
		}
		if(($mon == 12) and ($open_year == ($year - 1))) {
			$local_waive = 5;
		}
	}
	return $local_waive;
}

# Check if we've already billed them this month
sub already_billed_waive {
	my ($bill_mon, $curr_mon) = @_;
	if($bill_mon == $curr_mon) {
		return 6;
	} else {
		return $waive;
	}
}

$sth->finish();
$dbh->disconnect();

print "$acct_count checked, $bill_count billed, $waive_count waived, $already_count already billed, $ne_count not eligible.\n";
close LOGFILE;
