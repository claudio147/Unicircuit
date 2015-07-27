<?php

/* 
 * Programmpunkt 2.0 , Registrierung eines neuen Kunden(Architekten)
 * 
 * 
 */

//Einbindung Librarys

require_once ('../../library/public/database.inc.php');





//Registrationsformular
?>

<form action="registration.php" method="post">
    <table border="0" width="600">
   <tr>
   <td width="200">Vorname: </td>
   <td width="200"><input type="text" name="firstname" value="<?php echo $fn; ?>"></td>
   <td>&nbsp;</td>
  </tr>
  <tr>
   <td width="200">Nachnae: </td>
   <td width="200"><input type="text" name="lastname" value="<?php echo $ln; ?>"></td>
   <td>&nbsp;</td>
  </tr>
  <tr>
   <td width="200">Firma: </td>
   <td width="200"><input type="text" name="company" value="<?php echo $co; ?>"></td>
   <td>&nbsp;</td>
  </tr>
  <tr>
   <td width="200">Strasse & Hausnummer: </td>
   <td width="200"><input type="text" name="adressline1" value="<?php echo $a1; ?>"></td>
   <td>&nbsp;</td>
  </tr>
  <tr>
   <td width="200">Adresszeile 2: </td>
   <td width="200"><input type="text" name="adressline2" value="<?php echo $a2; ?>"></td>
   <td>&nbsp;</td>
  </tr>
  <tr>
   <td width="200">PLZ: </td>
   <td width="200"><input type="text" name="zip" value="<?php echo $zip; ?>"></td>
   <td>&nbsp;</td>
  </tr>
  <tr>
   <td width="200">Ort: </td>
   <td width="200"><input type="text" name="city" value="<?php echo $ci; ?>"></td>
   <td>&nbsp;</td>
  </tr>
  <tr>
   <td width="200">Land: </td>
   <td width="200"><input type="text" name="country" value="<?php echo $cn; ?>"></td>
   <td>&nbsp;</td>
  </tr>
  <tr>
   <td width="200">E-Mail: </td>
   <td width="200"><input type="text" name="email" value="<?php echo $em; ?>"></td>
   <td>&nbsp;</td>
  </tr>
  <tr>
   <td width="200">Telefon Nummer: </td>
   <td width="200"><input type="text" name="PhoneNumber" value="<?php echo $pn; ?>"></td>
   <td>&nbsp;</td>
  </tr>
  <tr>
   <td width="200">Mobile Nummer: </td>
   <td width="200"><input type="text" name="MobileNumber" value="<?php echo mn; ?>"></td>
   <td>&nbsp;</td>
  </tr>
  <td>Passwort: </td>
   <td><input type="text" name="password1" id="p1" value="<?php echo $p1; ?>"></td>
   <td>&nbsp;</td>
  </tr> 
  <tr>
   <td>Passwort erneut eingeben: </td>
   <td><input type="text" name="password2" id="p2" value="<?php echo $p2; ?>"></td>
   <td></td>
  </tr>
  <tr>
   <td>&nbsp;</td>
   <td colspan="2"><input type="submit" name="submit"></td>
  </tr>
        
    </table>
</form>

