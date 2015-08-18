<?php

/*
 *  Programmpunkt 1.0 Login / Login für Admin, Architekten und Bauherren
 */
//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');


if (isset($_POST['submit'])) {
  // Es wird untersucht, ob in der Datenbank ein entsprechender User eingetragen ist, ansonsten wird eine Fehlermeldung 
  // ausgegeben
  if (isset($_POST['email']) && isset($_POST['password'])) {
    // Hole die Benutzer-ID aus der Tabelle user
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
    $pw = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    
    $link = connectDB();
    $sql = "SELECT IdUser, Fk_IdUserType, Active FROM User WHERE email='" . $email . "' "
            . "AND password='" . hash('sha256', $pw) . "'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    if($row['Active'] != 3 && !empty($row['IdUser'])) {
        echo '<p style="font-color:red; font-weight:bold">Sie sind noch nicht aktiviert oder sind gesperrt.</p>';
        
    }
    // Konnte eine ID aufgrund der Login-Daten ermittelt werden?
    else if(!empty($row['IdUser'])) {
      
      // Hier wird die Session das erste Mal initialisiert
      session_start();
      // Speichere den PK des Users in der Session
      $_SESSION['idUser'] = $row['IdUser'];
      // Speicher den Username (email) in der Session
      $_SESSION['userName'] = $email;
      //Speichere den entsprechenden Usertype in der Session
      $_SESSION['UserType'] = $row['Fk_IdUserType'];

      // Beschaffen wir uns ein paar interessante Informationen
      $date = date("Y:m:d");
      $time = date("G:i:s");
      $sessionId = session_id();
      $browserTyp = substr($_SERVER['HTTP_USER_AGENT'], 0, 250);
      $datensatz = $row['id'];
      // Es wird ein Update durchgeführt, da die Benutzdaten stimmen
      $sql = "UPDATE User SET LastLoginDate='$date',LastLoginTime='$time',"
              . " SessionId='$sessionId', Browser='$browserTyp'"
              . " WHERE id =$datensatz";
      $result = mysqli_query($link, $sql);


      // Anhand des UserTyps auf die entsprechende Seite weiterleinten.
      switch($row['Fk_IdUserType']) {
          case 1:
              header('Location: ../../../rms/public/index.php');
              break;
          case 2:
              header('Location: projektverwaltung.php');
              break;
          case 3:
              header('Location: projekt.php');
              break;
          
      }
      
    } else {

      echo '<p style="font-color:red; font-weight:bold">Falscher Benutzername '
      . 'oder Password!</p>';
    }
  }
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
