<?php

/* 
 * Programmpunkt 2.0 , Registrierung eines neuen Kunden(Architekten)
 * 
 * 
 */

//Einbindung Librarys

require_once ('../../../library/public/database.inc.php');

//check mail
function checkMailFormat($email) {
  if (preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+\.([a-zA-Z]{2,4})$/", $email)) {
    return true;
  } else {
  return false;
  }
    
}

//Fehler Abfragen
// Wurde Submit-Button angeklickt?
if (isset($_POST['submit'])) {
  $fn = trim($_POST['firstname']);
  $ln = trim($_POST['lastname']);
  $co = trim($_POST['company']);
  $a1 = trim($_POST['adressline1']);
  $a2 = trim($_POST['adressline2']);
  $zip = trim($_POST['zip']);
  $ci = trim($_POST['city']);
  $cn = trim($_POST['country']);
  $pn = trim($_POST['PhoneNumber']);
  $mn = trim($_POST['MobileNumber']);
  $em = trim($_POST['email']);
  $p1 = trim($_POST['password1']);
  $p2 = trim($_POST['password2']);

  // Fehler im Eingabefeld?
  if (empty($fn) || strlen($fn) < 2) {
    echo 'error firstname';
    $errorFN = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($ln) || strlen($ln) < 2) {
    echo 'error lastname';
    $errorLN = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($co) || strlen($co) < 2) {
    echo 'error company';
    $errorLN = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($a1) || strlen($a1) < 5) {
    echo 'error adressline1';
    $errorLN = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($zip) || strlen($zip) < 4) {
    echo 'error zip';
    $errorLN = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($ci) || strlen($ci) < 4) {
    echo 'error city';
    $errorLN = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($cn) || strlen($cn) < 2) {
    echo 'error country';
    $errorLN = true;
    $error = true;
  }
  
  // Fehler im Eingabefeld?
  if (empty($pn) || strlen($pn) < 2) {
    echo 'error PhoneNumber';
    $errorLN = true;
    $error = true;
  }

  // Mailadresse korrekt?
  if (!checkMailFormat($em)) {
    echo 'error email';
    $errorEM = true;
    $error = true;
  }

  // Passworte identisch
  if (($p1 != $p2) || (strlen($p1) < 8)) {
    echo 'error password';
    $errorPW = true;
    $error = true;
  }

  // Prüfen, ob ein Fehler im Eingabeformular seitens Benutzer/in erkannt wurde
  if (!isset($error)) {
    // Wenn kein Fehler, dann zuerst prüfen, ob eventuell Benutzer/in schon in
    // der Datenbank registiert ist. Dabei können wir uns auf die E-Mail
    // Adresse beschränken, da diese ja weltweit eindeutig ist
    // Verbinde mit der Datenbank
     $link = connectDB();
    // Hole aus der Datebank ID von Benutzer/in, welche die eingegebene 
    // E-Mail Adresse besitzt
    $sql2 = "SELECT User.IDUser FROM User WHERE Email='$em'";
    

    // Zähle alle Datensätze, welcher das SQL-Statement zurück gegeben hat
    //$anzahl = mysqli_num_rows($result);
    $anzahl = 0;

    // Wenn die Anzahl 1 ist, dann ist die Person nicht in der Datenbank 
    // registiert
    if ($anzahl < 1) {
      // Beschaffe das aktuelle Datum (siehe php.net)
      $da = date('Y-m-d');
      // Beschaffe die aktuelle Uhrzeit 
      $ti = date('H:i:s');
      // Beschaffe Anzahl Millisekunden seit 1.1.1970
      $tm = time();
      // Beschaffe IP-Adresse des Clients 
      $ra = $_SERVER['REMOTE_ADDR'];
      // Bestime Salt
      $sa = 'FB8w?xn!Ju.j+Dk“YcG1EHpPFWc_!"*';
      // Füge alle Daten zusammen und genereriere einen HASH-Wert
      $to = sha1($fn . $ln . $em . $da . $ti . $ra . $sa);
      // SQL-Statement zusammensetzen, um Datensatz in DB zu speichern
      $sql = createArchitect($fn, $ln, $co, $zip, $ci, $cn, $pn, $mn, $em, $to, $p1, $da, $ti);
      // Anfrage an Datenbank senden
     
     
      $status = mysqli_query($link, $sql);
    
    if($status == true){
        header('location:registration.php');
    }else
    {
    echo '<p> Datensatz konnte nicht erzeugt werden</p>';
    }
    mysqli_close($link);

      
  }
}
}
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
   <td width="200"><input type="text" name="MobileNumber" value="<?php echo $mn; ?>"></td>
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
<div id="ergebnis"></div>
