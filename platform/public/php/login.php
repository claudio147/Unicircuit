<?php

/*
 *  Programmpunkt x.x Login / Login für Admin, Architekten und Bauherren
 */
//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');


if(isset($_POST['submit'])) {
    
}
?>

<form action="login.php" method="post">
 <table border="1">
  <tr>
   <td>E-Mail:</td><td><input type="text" name="email" /></td>
  <tr>

  </tr>
  <td>Password:</td><td><input type="password" name="password" /></td>
  </tr>
  <tr>
   <td colspan="2" align="right"><input type="submit" value="anmelden" name="submit"/></td>
  </tr>
  <tr>
   <td colspan="2"><a href="">Passwort vergessen?</a>
  </tr>
 </table>
</form>
