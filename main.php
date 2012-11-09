<?
session_start();

require("gcdb.php");
require("security/secure.php");

beginDocument($lMain, $sess_user);

$db = getDBConnection();

$result = DBquery("SELECT * from Configuration", $db);
$configuration = DBfetch_array($result);
$ticket_result = DBquery("SELECT * from Tickets WHERE Status='Open' ORDER BY TicketID DESC LIMIT 5", $db);
checkDBError($db);
$ticket_row = DBfetch_array($ticket_result);

?>
<table cellpadding=5 cellspacing=0 border=0 width='100%'>
 <tr>
  <td valign=top width='15%'>
   <? beginPrettyTable("1"); ?>
    <tr>
     <td><a href='showprofile.php'><?=$lListCustomers?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='entercustomer.php'><?=$lEnterCustomer?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
     <?if($sess_admin == "Yes") {?>
    <tr>
     <td><a href='administration.php'><?=$lAdmin;?></a></td>
    </tr>
     <? } ?>
    <tr>
     <td><a href='stats.php'><?=$lGeneralStatistics?></a></td>
    </tr>
    <tr>
     <td><hr color=black></td>
    </tr>
    <tr>
     <td><a href='money.php?posmoney=yes'><?=$lCreditedAccounts?></a></td>
    </tr>
    <tr>
     <td><a href='money.php?negmoney=yes'><?=$lOwingAccounts?></a></td>
    </tr>
    <tr>
     <td><hr color=black></td>
    </tr>
    <tr>
     <td><a href='index.php'><?=$lLogout?></a></td>
     <? endPrettyTable(); ?>
  </td>
  <td valign=top align=center width='65%'>
  <? $result = DBquery("SELECT * from News", $db); ?>
   <table cellpadding=0 cellspacing=0 border=0 width='75%'>
    <tr>
     <td>
      <?
	beginPrettyTable("1",$lNews);
	$news_row = DBfetch_array($result);
	if($news_row) {
		do {
			echo "<tr><td><b>";
			echo $news_row["Title"];
			echo "</b><br>";
			echo "<li>".$news_row["Content"]."<br>";
			echo "<div class='weedata'>".$news_row["PostedDate"]." by ".$news_row["Poster"]."</div><br>";
			echo "</td></tr>";
		} while($news_row = DBfetch_array($result));
	} else {
		echo "<tr><td><b>$lNoNewsFound</b></td></tr>";
	}
	endPrettyTable(); ?>
     </td>
    </tr>
   </table>
  </td>
  <td valign=top width='20%'>
   <table cellpadding=0 cellspacing=0 border=0>
    <tr>
     <td valign=top>
      <table cellpadding=0 cellspacing=0 border=0>
       <tr>
        <td valign=top>
         <form name='getuser' action='showprofile.php'>
         <? beginPrettyTable("2", $lRetrieveByCustomerID); ?>
          <tr>
           <td><input type='text' name='CustomerID' value=''></td>
           <td><input type='image' src='images/go.gif' value='submit' border=0></td>
          </tr>
         <? endPrettyTable(); ?>
         </form>
        </td>
       </tr>
       <tr>
        <td valign=top>
         <form name='searchuser' action='search.php'>
          <input type='hidden' name='searcher' value='last'>
           <? beginPrettyTable("2", $lSearchByLastName); ?>
           <tr>
            <td><input type='text' name='tosearch' value=''></td>
            <td><input type='image' src='images/go.gif' value='submit' border=0></td>
           </tr>
           <? endPrettyTable(); ?>
          </form>
         </td>
        </tr>
        <tr>
         <td valign=top>
          <form name='getuser' action='search.php'>
           <input type='hidden' name='searcher' value='address'>
           <? beginPrettyTable("2", $lSearchByAddress); ?>
            <tr>
             <td><input type='text' name='tosearch' value=''></td>
             <td><input type='image' src='images/go.gif' value='submit' border=0></td>
            </tr>
           <? endPrettyTable(); ?>
          </form>
        </td>
       </tr>
       <tr>
        <td valign=top>
         <form name='getuser' action='search.php'>
          <input type='hidden' name='searcher' value='email'>
          <? beginPrettyTable("2", $lSearchByEmail); ?>
           <tr>
            <td><input type='text' name='tosearch' value=''></td>
            <td><input type='image' src='images/go.gif' value='submit' border=0></td>
           </tr>
          <? endPrettyTable(); ?>
         </form>
        </td>
       </tr>
       <tr>
        <td valign=top>
         <form name='getuser' action='search.php'>
          <input type='hidden' name='searcher' value='domain'>
          <? beginPrettyTable("2", $lSearchByDomain); ?>
           <tr>
            <td><input type='text' name='tosearch' value=''></td>
            <td><input type='image' src='images/go.gif' value='submit' border=0></td>
           </tr>
          <? endPrettyTable(); ?>
         </form>
        </td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<?
if($configuration["HotTicket"] == "On") {
     beginPrettyTable(4, "$lTickets");
	beginBorderedTable(4);
	if($ticket_row) {
		echo "<tr><td><b>$lCustomer</b></td><td><b>$lID</b></td><td><b>$lDateOpened</b></td><td align='center'><b>$lOpener<b></td></tr>\n";
		do {
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			printf("<tr class='$class'><td align='center'><a href='showprofile.php?CustomerID=%s'>%s</a></td><td>%s</td><td>%s</td><td>%s</td></tr>", $ticket_row["CustomerID"], $ticket_row["CustomerID"], $ticket_row["TicketID"], $ticket_row["OpenDate"], $ticket_row["Opener"]);
		} while($ticket_row = DBfetch_array($ticket_result));
	} else {
		echo "$lNoTicketsFound";
	}
     endBorderedTable();
     endPrettyTable();
}
endDocument(); ?>
