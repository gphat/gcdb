<?
session_start();
session_destroy();

require("gcdb.php");

if($username != "") {
	$db = getDBConnection();
	$bleh = (int) $username;
	if($bleh == 0) {
		$user_result = DBquery("SELECT * FROM Users where Username='$username'", $db);
	} else {
		$user_result = DBquery("SELECT * FROM Customers where CustomerID=$username", $db);
		$customer = 1;
	}
	checkDBError($db);
	$user_row = DBfetch_array($user_result);
	if(($user_row["Password"] == $password) and (DBnum_rows($user_result)) and ($password != "")) {
		session_start();
		$sess_lang = $user_row["Language"];
		$sess_name = $user_row["RealName"];
		$sess_admin = $user_row["Admin"];
		if($customer != 1) {
			$sess_user = $username;
			session_register("sess_user");
		} else {
			$sess_customer = $username;
			session_register("sess_customer");
		}
		session_register("sess_lang");
		session_register("sess_name");
		session_register("sess_admin");
		if($customer != 1) {
			header("Location: main.php");
			exit;
		} else {
			header("Location: publicprofile.php");
			exit;
		}
	} else {
		header("Location: index.php");
	}
} else {

beginDocument($lLogin, $lNotLoggedIn);
?>
<form name='login' method='POST' action='<?$PHP_SELF?>'>
<? openForm("login", $PHP_SELF); ?>
<table width='100%' height='50%' cellpadding=0 cellspacing=0 border=0>
 <tr>
  <td valign='center' align='center'>
  <? beginPrettyTable("2", $lLogin); ?>
  <tr>
   <td><b><?=$lUsername?>:</b></td>
   <td><input type='text' name='username' value=''></td>
  </tr>
  <tr>
   <td><b><?=$lPassword?>:</b></td>
   <td><input type='password' name='password' value=''></td>
  </tr>
  <? makeSubmitter(); ?>
  <? closeForm(); ?>
  <? endPrettyTable(); ?>
  </td>
 </tr>
</table>
<?
}
endDocument();
?>
