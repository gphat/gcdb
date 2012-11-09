<?
session_start();

require("gcdb.php");
require("security/secure.php");
adminify($sess_user, $sess_admin);

beginDocument($lAdmin, $sess_user);
?>
<table width='100%' height='50%' cellpadding=0 cellspacing=0 border=0>
 <tr>
  <td valign=center align=center>
   <? beginPrettyTable("2", $lAdmin); ?>
   <tr>
    <td><a href="configuration.php"><img src='images/configuration.gif' border=0></a></td><td><a href="configuration.php"><?=$lSystemConfiguration?></a></td>
   </tr>
   <tr><td>&nbsp;</td></tr>
   <tr>
    <td><a href="enteruser.php"><img src='images/add_user.gif' border=0></a></td><td><a href="enteruser.php"><?=$lEnterUser?></a></td>
   </tr>
   <tr>
    <td><a href="usermanagement.php"><img src='images/manage_user.gif' border=0></a></td><td><a href="usermanagement.php"><?=$lUserManagement?></a></td>
   </tr>
   <tr><td>&nbsp;</td></tr>
   <tr>
    <td><a href="enternews.php"><img src='images/add_news.gif' border=0></a></td><td><a href="enternews.php"><?=$lEnterNews?></a></td>
   </tr>
   <tr>
    <td><a href="newsmanagement.php"><img src='images/manage_news.gif' border=0></a></td><td><a href="newsmanagement.php"><?=$lNewsManagement?></a></td>
   </tr>
   <tr><td>&nbsp;</td></tr>
   <tr>
    <td><a href="enterresource.php"><img src='images/add_resource.gif' border=0></a></td><td><a href="enterresource.php"><?=$lEnterResource?></a></td>
   </tr>
   <tr>
    <td><a href="resourcemanagement.php"><img src='images/manage_resource.gif' border=0></a></td><td><a href="resourcemanagement.php"><?=$lResourceManagement?></a></td>
   </tr>
   <tr><td>&nbsp;</td></tr>
   <tr>
    <td><a href="createpackage.php"><img src='images/create_package.gif' border=0></a></td><td><a href="createpackage.php"><?=$lCreatePackage?></a></td>
   </tr>
   <tr>
    <td><a href="packagemanagement.php"><img src='images/manage_package.gif' border=0></a></td><td><a href="packagemanagement.php"><?=$lPackageManagement?></a></td>
   </tr>
   <? endPrettyTable(); ?>
  </td>
 </tr>
</table>
<?
endDocument();
?>
