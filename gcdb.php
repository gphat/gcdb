<?
# This file contains lots of functions used by the billing database
# to generate no fun things like tables, forms, and such.
require("gcdb-settings.php");

###############################################################################
# DB INTERFACE
###############################################################################
require("db.php");

###############################################################################
# BILLING FUNCTIONS
###############################################################################

# name: untranslate_interval
#
# arguments: Language version of the Charged field
# returns: DB version of charged field (English)
#  
# desc: This function allows gcdb to have a language neutral way of storing
# desc: the interval for charging an account.
function untranslate_interval($charged) {
	global $lMonthly, $lQuarterly, $lBiannually, $lAnnually;
	if($charged == $lMonthly) {
		return "monthly";
	}
	if($charged == $lQuarterly) {
		return "quarterly";
	}
	if($charged == $lBiannually) {
		return "biannually";
	}
	if($charged == $lAnnually) {
		return "annually";
	}
}
# name: translate_interval
#
# arguments: DB version of charged field (English)
# returns: Language version of the Charged field
#  
# desc: translates the DB interval into a Lang string
function translate_interval($charged) {
	global $lMonthly, $lQuarterly, $lBiannually, $lAnnually;
	if($charged == "monthly") {
		return $lMonthly;
	}
	if($charged == "quarterly") {
		return $lQuarterly;
	}
	if($charged == "biannually") {
		return $lBiannually;
	}
	if($charged == "annually") {
		return $lAnnually;
	}
}

###############################################################################
# DOCUMENT FUNCTIONS
###############################################################################

# name: beginDocument 
#
# arguments: Page title, Username
# returns: None
#
# desc: echos the HTML tags that get the document started, provides a central
# desc: location for the background color, stylesheets, and whatnot.
function beginDocument ($title, $user) {
	
	global $BRAND, $BACKGROUND, $TOPBAR, $sess_lang;

	if(isset($sess_lang)) {
		checkLang($sess_lang);
	}
	echo "<html>\n";
	echo "<head>\n <title>[$BRAND] :: $title</title>\n";
	echo " <link rel=stylesheet href='gcdb.css' type='text/css'>\n";
	echo "</head>\n<body bgcolor='$BACKGROUND' text='black' leftmargin=0 topmargin=0 marginheight=0 marginwidth=0>\n"; 
	echo "<table bgcolor='$TOPBAR' width=100%><tr><td width='80%' align='left' class='header'>&nbsp;&nbsp;$BRAND: $user</td><td width='20%' align='right'>";
	if($user != "Not Logged In") {
  		echo "<a href='main.php'><img src='images/home.gif' width=24 height=24 border=0 alt='Home'></a>";
	}
	echo "&nbsp;&nbsp;</td></tr></table>";
	echo "<table cellpadding=0 width='100%' height='100%' border=0><tr><td valign=top>\n";
}

# name: searchBar
#
# arguments: None
# returns: None
#
# desc: echos the HTML for the search Bar
function searchBar() {
	global $lSearch, $lLast, $lAddress, $lEmail, $lDomain;
	openForm("searcher", "search.php");
		beginPrettyTable("2");
		echo "<tr>\n";
		echo "<td><b>$lSearch</b>: <input name='tosearch' type='text'></td>\n";
		echo "<td valign='center'>\n<select name='searcher' type='text'>\n";
		echo " <option value='last' selected>$lLast\n";
		echo " <option value='address'>$lAddress\n";
		echo " <option value='email'>$lEmail\n";
		echo " <option value='domain'>$lDomain\n";
		echo "</select>\n";
		echo "<input type='image' align='center' src='images/go.gif' value='submit' border=0>\n";
		echo "</td></tr>\n";
		endPrettyTable();
		closeForm();
}

# name: endDocument
# 
# arguments: None
# returns: None
#
# desc: closes all the HTML opened by beginDocument
function endDocument() {
	echo "</td></tr></table></body></html>\n";
}
	

###############################################################################
# TABLE FUNCTIONS
###############################################################################

# name: beginPrettyTable
#
# desc: handle the creation of a pretty, outlined table.  Uses the supplied
# desc: bordercolor, bgcolor, colspan (for header) and text (text of header)
function beginPrettyTable () {
	global $TABLEBORDER, $INNERTABLE;

	# Allow us to take either 1 or 2 arguments
	$numargs = func_num_args();
	if($numargs == 0) {
		die ("Must provide a colspan to beginPrettyTable()\n");
	}
	$arg_list = func_get_args();
	$colspan = $arg_list[0];
	if($numargs > 1) {
		$header = $arg_list[1];
	}
	echo "<table bgcolor='$TABLEBORDER' cellpadding=1 cellspacing=0 border=0>\n";
	if ($header) {
		echo " <tr> <td colspan=$colspan align='right'><div class='header'>$header&nbsp;</div></td> </tr>\n";
	}
	echo " <tr>\n  <td>\n";
	echo "   <table bgcolor='$INNERTABLE' cellpadding=2 cellspacing=0 border=0>\n";
	echo "    <tr>\n  <td>\n";
	echo "      <table bgcolor='$INNERTABLE' cellpadding=2 cellspacing=0 border=0>\n";
}

# name: endPrettyTable
#
# desc: close all tags opened by beginPrettyTable
function endPrettyTable () {
	echo "      </table>\n";
	echo "     </td> </tr>\n";
	echo "   </table>\n";
	echo "  </td> </tr>\n";
	echo "</table>\n";
}

# name: beginBorderedTable
#
# desc: when used in unison with beginPrettyTable, gives a nice visual cue around
# desc: the data inside a table
function beginBorderedTable ($colspan) {
	global $TABLEBORDER, $INNERTABLE;

	echo "<tr>\n  <td>\n";
	echo " <table bgcolor='$INNERTABLE' cellpadding=2 cellspacing=2 border=0>\n";
}

# name: endBorderedTable
#
# desc: close all tags opened by beginBorderedTable
function endBorderedTable () {
	echo "   </td> </tr>\n";
	echo "  </table>\n";
	echo " </td> </tr>\n";
}

###############################################################################
# FORM FUNCTIONS
###############################################################################

# name: openForm
#
# desc: creates a form
function openForm ($name, $action) {
	echo "<form method='post' name='$name' action='$action'>";
}

# name: makeHiddenField
#
# desc: creates a hidden field
function makeHiddenField ($field, $value) {
	echo "<input type=\"hidden\" name=\"$field\" value=\"$value\">\n";
}

# name: makeStaticField
#
# desc: creates an uneditable field
function makeStaticField ($field, $value) {
	echo " <tr> <td><b>$field:</b></td><td><div class='data'>$value</div></td> </tr>\n";
}

# name: makeTextField
#
# arguments: Field label, form name, value
# returns: none
#
# desc: creates a textfield with the supplied paramaters
function makeTextField ($field, $name, $value) {
	echo " <tr> <td><b>$field:</b></td><td><input name='$name' type='text' value='$value'></td> </tr>\n";
}

# name: makeLargeTextField
#
# desc: creates a textarea with the supplied parameters
function makeLargeTextField ($field, $name, $value) {
	echo "<tr> <td valign='top'><b>$field:</b></td><td><textarea name=$name cols='48' rows='15' wrap='physical'>$value</textarea></td></tr>\n";
}

# name: makeDropBox
#
# desc: creates a select control using the supplied options, rearranging them
# desc: according to the first arg passed
function makeDropBox () {
	$numargs = func_num_args();
	if($numargs < 3) {
		echo $numargs."-";
		die ("Must provide arguments to makeDropBox\n");
	}
	$arg_list = func_get_args();
	$field 	= $arg_list[0];
	$name 	= $arg_list[1];
	$current = $arg_list[2];
	echo " <tr> <td><b>$field:</b></td><td><select name='$name'>\n";
	for($i = 3; $i < $numargs; $i++) {
		if($arg_list[$i] != $current) {
			echo "<option value='$arg_list[$i]'>$arg_list[$i]\n";
		} else {
			echo "<option value='$arg_list[$i]' selected>$arg_list[$i]\n";
		}
	}
	echo "</select></td></tr>\n";
}

# name: makeArrayDropBox
#
# desc: creates a select control from an array, but its a special array ;)
function makeArrayDropBox ($field, $name, $current, $array) {
	echo " <tr> <td><b>$field:</b></td><td><select name='$name'>\n";
	$size = sizeof($array);
	for($i = 0; $i < $size; $i += 2) {
		$also = $i + 1;
		if($array[$also] != $current) {
			echo "<option value='$array[$i]'>$array[$also]\n";
		} else {
			echo "<option value='$array[$i]' selected>$array[$also]\n";
		}
	}
	echo "</select></td></tr>\n";
}

# name: makeSubmitter
#
# arguments: none
# returns: none
#
# desc: makes a submit field
function makeSubmitter () {
	echo " <tr> <td colspan=2 align=center>\n";
	echo "  <input type=image type='submit' value='submit' src='images/submit.gif' border=0 alt='Go!'>";
	echo " </td> </tr>\n";
}

# name: closeForm
#
# arguments: none
# returns: none
#
# desc: close a form
function closeForm () {
	echo "</form>";
}
###############################################################################
# SECURITY
###############################################################################
# name: adminify
#
# arguments: User, Admin status
# returns: none
#
# desc: restrict access to a page to only Admins
function adminify($sess_user, $sess_admin) {
	global $lAccessDenied, $lAdminUseOnly;
	if($sess_admin != "Yes") {
		beginDocument("Security", $sess_user);
		beginPrettyTable(1, "$lAccessDenied");
		echo "<tr><td>$lAdminUseOnly</td></tr>";
		endPrettyTable();
		endDocument();
	}
}	
	

###############################################################################
# LANGUAGE API
###############################################################################

# name: checkLang
#
# arguments: Language
# returns: none
#
# desc: Adds content type header for languages that need it
function checkLang($lang) {
	if(isset($HTTPcharser)) {
		    header("Content-Type: text/html; charset=".$HTTPcharset);
	} else {
	    # Ugly hack. Don't use.
	    if($lang == "russian.php") {
		    header("Content-Type: text/html; charset=koi8-r");
	    }
	}
}

if(!(isset($sess_lang))) {
 	$db = getDBConnection();
 	$result = DBquery("select * from Configuration",$db);
	$config_row = dbfetch_array($result);
	require("lang/".$config_row["Language"]);
} else {
	require("lang/".$sess_lang);
}

###############################################################################
# DEBUG API
###############################################################################

# name: pdebug
#
# arguments: string, optional priority
# returns: none
#
# desc: used for sending message in HTML or to syslog for debugging
function pdebug($msg, $pri) {
	$dest = "";
	if ($msg) {
		switch($dest) {
			case "html":
				echo "<!-- $msg -->\n";
				break;
			case "syslog":
				$dpri = LOG_DEBUG;
				if ($pri) {
					$dpri = LOG_DEBUG | $pri;
				}
				openlog("gdbc", LOG_PID, LOG_USER);
				syslog($dpri,$msg);
				closelog();
				break;
		}
	}
}

?>
