#!/usr/bin/perl

sub usage() {
	print "doc_gen.pl [input_file]\n";
	exit;
}

$infile = shift || usage();

$in_function = 0;
$got_args = 0;
$count = 0;

open INFILE, $infile;
print "<html>\n<link rel=stylesheet href='docgen.css' type='text/css'>\n<body>\n<table border=0>\n";
while($line = <INFILE>) {
	$count++;
	chomp($line);
	if($line =~ m/# name:/) {
		($left, $right) = split(/:/, $line);
		print "<tr><td class='function' colspan=2>$right</td></tr>\n";
		$in_function = 1;
	}
	if($line =~ m/# arguments:/) {
		if($in_function) {
			$got_args = 1;
			($left, $right) = split(/:/, $line);
			print("<tr><td>Arguments:</td> <td class='args'>$right</td></tr>\n");
		} else {
			print "Malformed argument section: no name preceding @ line $count.\n";
		}
	}
	if($line =~ m/# returns:/) {
		if(($in_function) and ($got_args)) {
			($left, $right) = split(/:/, $line);
			print("<tr><td>Returns:</td> <td class='rets'>$right</td><tr>\n");
		} else {
			print "Malformed returns section: no name or no arguments preceding @ line $count.\n";
		}
	}
	if($line =~ m/# desc:/) {
		if($in_function) {
			($left, $right) = split(/:/, $line);
			if($got_desc) {
				print("$right\n");
			} else {
				print "<tr><td colspan=2>Description:</td></tr>\n<tr><td class='desc' colspan=2>$right";
			}
			$got_desc = 1;
		} else {
			print "Malformed description: no name preceding @ line $count.\n";
		}
	}
	if(substr($line, 0, 1) ne "#") {
		if($got_desc) {
			print "</td></tr>\n";
		}
		if($in_function) {
			print "</td></tr><tr><td colspan=2>&nbsp</td></tr>\n<tr><td>\n";
		}
		$in_function = 0;
		$got_args = 0;
		$got_desc = 0;
	}
}
($sec, $min, $hour, $mday, $mon, $year, $wday, $yday, $isdst) = localtime();
$year += 1900;
$month = qw(January February March April May June July August September October November December)[$mon];
# Convert minutes 0 - 9 to 00 - 09
$min=sprintf("%02d",$min);
print "</table>Documentation spewd from Doc_Gen 0.0 at $hour:$min, $month $mday of $year</body></html>";
