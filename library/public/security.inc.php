<?php
/*
*   Funktionsbibliothek
*   «security.inc.php / Beinhaltet alle externen Funktionen der Plattform und des RMS»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/

require_once ('../../../library/public/database.inc.php');

//Funktion zur Session Überprüfüng, liefert true oder false zurück
function checkSessionId($idUser, $sessionId) {
    $link = connectDB();
    $sql = getSessionId($idUser);
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_array($result);
    
    if($sessionId == $row['SessionId']) {
        return true;
    } else {
        return false;
    }
}

//Funktion generiert ein Passwort
function generatePassword() {

    $alpha = "abcdefghikmnopqrstuvqxyz";

    $alpha .= "23456789";

    $alpha .= "ABCDEFGHKLMNOPQRSTUVWXYZ";

    $alpha .= "!$%&/()=";

    srand((double) microtime() * 1000000);

    for ($index = 0; $index < 7; $index++) {
        $password .= substr($alpha, (rand() % (strlen($alpha))), 1);
    }
    return $password;
}

//Funktion verkleinert Bilder und verschiebt diese
function resizeImage($filepath_old, $filepath_new, $image_dimension, $scale_mode = 0) {
    if (!(file_exists($filepath_old)) || file_exists($filepath_new))
        return false;

    $image_attributes = getimagesize($filepath_old);
    $image_width_old = $image_attributes[0];
    $image_height_old = $image_attributes[1];
    $image_filetype = $image_attributes[2];

    if ($image_width_old <= 0 || $image_height_old <= 0) {
        return false;
    }
    $image_aspectratio = $image_width_old / $image_height_old;

    if ($scale_mode == 0) {
        $scale_mode = ($image_aspectratio > 1 ? -1 : -2);
    } elseif ($scale_mode == 1) {
        $scale_mode = ($image_aspectratio > 1 ? -2 : -1);
    }

    if ($scale_mode == -1) {
        $image_width_new = $image_dimension;
        $image_height_new = round($image_dimension / $image_aspectratio);
    } elseif ($scale_mode == -2) {
        $image_height_new = $image_dimension;
        $image_width_new = round($image_dimension * $image_aspectratio);
    } else {
        return false;
    }

    switch ($image_filetype) {
        case 1:
            $image_old = imagecreatefromgif($filepath_old);
            $image_new = imagecreate($image_width_new, $image_height_new);
            imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old);
            imagegif($image_new, $filepath_new);
            break;

        case 2:
            $image_old = imagecreatefromjpeg($filepath_old);
            $image_new = imagecreatetruecolor($image_width_new, $image_height_new);
            imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old);
            imagejpeg($image_new, $filepath_new);
            break;

        case 3:
            $image_old = imagecreatefrompng($filepath_old);
            $image_colordepth = imagecolorstotal($image_old);

            if ($image_colordepth == 0 || $image_colordepth > 255) {
                $image_new = imagecreatetruecolor($image_width_new, $image_height_new);
            } else {
                $image_new = imagecreate($image_width_new, $image_height_new);
            }

            imagealphablending($image_new, false);
            imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old);
            imagesavealpha($image_new, true);
            imagepng($image_new, $filepath_new);
            break;

        default:
            return false;
    }

    imagedestroy($image_old);
    imagedestroy($image_new);
    return true;
}

//Funktion Überprüfung auf Dateityp (Fotos: JPG, JPEG, GIF, PNG
function checkImageType($uploadfile) {
    $file = pathinfo($uploadfile, PATHINFO_EXTENSION);

    if ($file != "jpg" && $file != "png" && $file != "jpeg" && $file != "gif" && $file != "JPG" && $file != "JPEG" && $file != "PNG" && $file != "GIF") {
        return false;
    } else {
        return true;
    }
}

//Event-Handling für Projektspezifikationen
function checkResponse($response) {
    if (isset($response)) {
        $x = $response;
        switch ($x) {
            case 0:
                $stat = '<br/><div class="alert alert-success" role="alert">Projekt erfolgreich bearbeitet.</div>';
                break;
            case 1:
                $stat = '<br/><div class="alert alert-danger" role="alert">Bearbeiten fehlgeschlagen</div>';
                break;
            case 2:
                $stat = '<br/><div class="alert alert-success" role="alert">Projekt erfolgreich hinzugefügt</div>';
                break;
            case 3:
                $stat = '<br/><div class="alert alert-danger" role="alert">Projekt hinzufügen fehlgeschlagen</div>';
                break;
            case 4:
                $stat = '<br/><div class="alert alert-danger" role="alert">Max. 2MB und Filetypen: .jpg/.png/.gif</div>';
                break;
            case 5:
                $stat = '<br/><div class="alert alert-success" role="alert">Benutzer Einstellungen wurden bearbeitet.</div>';
                break;
            case 6:
                $stat = '<br/><div class="alert alert-danger" role="alert">Passwort konnte nicht geändert werden.
                 Die Passwörter stimmen nicht überein oder das Passwort hat weniger als 8 Zeichen!</div>';
                break;
            case 7:
                $stat = '<br/><div class="alert alert-success" role="alert">Projekt wurde archiviert</div>';
                break;
            case 8:
                $stat = '<br/><div class="alert alert-danger" role="alert">Projekt konnte nicht archiviert werden</div>';
                break;
            case 9:
                $stat = '<br/><div class="alert alert-danger" role="alert">Projekt konnte nicht gelöscht werden</div>';
                break;
            case 10:
                $stat = '<br/><div class="alert alert-success" role="alert">Projekt wurde erfolgreich gelöscht</div>';
                break;
        }
    }
    return $stat;
}

//Event-Handling für Gallery
function checkEventGallery($respone) {
    $x = $respone;
    switch ($x) {
        case 1:
            $stat = '<br/><div class="alert alert-success" role="alert">Foto(s) erfolgreich hochgeladen</div>';
            break;
        case 0:
            $stat = '<br/><div class="alert alert-warning" role="alert">Hochladen Fehlgeschlagen! - Bitte erneut versuchen</div>';
            break;
        case 2:
            $stat = '<br/><div class="alert alert-warning" role="alert">Hochladen Fehlgeschlagen! - Nur JPG, JPEG, PNG, GIF</div>';
            break;
        case 3:
            $stat = '<br/><div class="alert alert-warning" role="alert">Hochladen Fehlgeschlagen! max. 4 MB pro Foto</div>';
            break;
        case 4:
            $stat = '<br/><div class="alert alert-success" role="alert">Foto erfolgreich gelöscht</div>';
            break;
        case 5:
            $stat = '<br/><div class="alert alert-warning" role="alert">Löschen fehlgeschlagen! Bitte erneut versuchen</div>';
            break;
    }
    return $stat;
}

//Event-Handling für Deadlines
function checkEventDeadlines($response) {
    $x = $response;
    switch ($x) {
        case 0:
            $stat = '<br/><div class="alert alert-success" role="alert">Deadline erfolgreich hinzugefügt</div>';
            break;
        case 1:
            $stat = '<br/><div class="alert alert-danger" role="alert">Hinzufügen fehlgeschlagen! Bitte erneut versuchen</div>';
            break;
        case 2:
            $stat = '<br/><div class="alert alert-success" role="alert">Deadline erfolgreich bearbeitet</div>';
            break;
        case 3:
            $stat = '<br/><div class="alert alert-danger" role="alert">Bearbeiten fehlgeschlagen! Bitte erneut versuchen</div>';
            break;
        case 4:
            $stat = '<br/><div class="alert alert-success" role="alert">Deadline erfolgreich gelöscht</div>';
            break;
        case 5:
            $stat = '<br/><div class="alert alert-danger" role="alert">Löschen fehlgeschlagen! Bitte erneut versuchen</div>';
            break;
    }

    return $stat;
}

//Event-Handling für Addresslist
function checkEventAddresslist($response) {
     $x= $response;
     switch ($x) {
        case 0:
            $stat = '<br/><div class="alert alert-danger" role="alert">Firma existiert bereits in globaler Adressdatenbank! - Bitte Suchfunktion benutzen.</div>';
            break;
        case 1:
            $stat = '<br/><div class="alert alert-success" role="alert">Adress erfolgreich hinzugefügt.</div>';
            break;
        case 2:
            $stat = '<br/><div class="alert alert-danger" role="alert">Adresse hinzufügen fehlgeschlagen.</div>';
            break;
        case 3:
            $stat = '<br/><div class="alert alert-success" role="alert">Adresse erfolgreich gelöscht.</div>';
            break;
        case 4:
            $stat = '<br/><div class="alert alert-danger" role="alert">Löschen fehlgeschlagen.</div>';
            break;
        case 5:
            $stat = '<br/><div class="alert alert-success" role="alert">Adresse erfolgreich bearbeitet.</div>';
            break;
        case 6:
            $stat = '<br/><div class="alert alert-danger" role="alert">Bearbeiten fehlgeschlagen!</div>';
            break;
        }
    return $stat;
}

//Event-Handling für Contact
function checkEventContact($response) {
    $x= $response;
    switch ($x){
        case 1:
            $stat = '<br/><div class="alert alert-success" role="alert">Nachricht erfolgreich gesendet!</div>';
            break;
        case 2:
            $stat = '<br/><div class="alert alert-warning" role="alert">Senden fehlgeschlagen! Bitte eingaben überprüfen</div>';
            break;
        }
    return $stat;
}

//Event-Handling für Events
function checkEventEvents($response) {
    $x= $response;
    switch ($x) {
        case 0:
            $stat = '<br/><div class="alert alert-success" role="alert">Event erfolgreich hinzugefügt</div>';
            break;
        case 1:
            $stat = '<br/><div class="alert alert-danger" role="alert">Hinzufügen fehlgeschlagen! Bitte erneut versuchen</div>';
            break;
        case 2:
            $stat = '<br/><div class="alert alert-success" role="alert">Event erfolgreich bearbeitet</div>';
            break;
        case 3:
            $stat = '<br/><div class="alert alert-danger" role="alert">Bearbeiten fehlgeschlagen! Bitte erneut versuchen</div>';
            break;
        case 4:
            $stat = '<br/><div class="alert alert-success" role="alert">Event erfolgreich gelöscht</div>';
            break;
        case 5:
            $stat = '<br/><div class="alert alert-danger" role="alert">Löschen fehlgeschlagen! Bitte erneut versuchen</div>';
            break;
        }
        return $stat;    
}

//Event-Handling für Schedule
function checkEventSchedule($response) {
    $x= $response;
    switch ($x) {
        case 0:
            $stat = '<br/><div class="alert alert-success" role="alert">Terminplan erfolgreich hochgeladen</div>';
            break;
        case 1:
            $stat = '<br/><div class="alert alert-warning" role="alert">Hochladen Fehlgeschlagen! - Bitte erneut versuchen</div>';
            break;
        case 2:
            $stat = '<br/><div class="alert alert-warning" role="alert">Hochladen Fehlgeschlagen! - Nur PDF und X-PDF erlaubt</div>';
            break;
        case 3:
            $stat = '<br/><div class="alert alert-warning" role="alert">Hochladen Fehlgeschlagen! max. 8 MB</div>';
            break;
        case 4:
            $stat = '<br/><div class="alert alert-success" role="alert">Terminplan erfolgreich gelöscht</div>';
            break;
        case 5:
            $stat = '<br/><div class="alert alert-warning" role="alert">Löschen fehlgeschlagen! Bitte erneut versuchen</div>';
            break;
        }
        return $stat;
}

//Event-Handling für SIA
function checkEventSia($response) {
    $x= $response;
    switch ($x) {
        case 0:
            $stat = '<br/><div class="alert alert-danger" role="alert">Fehlgeschlagen! – kein Datum oder Datum > heute</div>';
            break;
        }
        return $stat;
}

//Event-Handling für Timeline
function checkEventTimeline($response) {
    $x= $response;
    switch ($x) {
        case 0:
            $stat = '<br/><div class="alert alert-success" role="alert">Beitrag erfolgreich hochgeladen</div>';
            break;
        case 1:
            $stat = '<br/><div class="alert alert-danger" role="alert">Bearbeiten fehlgeschlagen</div>';
            break;
        case 2:
            $stat = '<br/><div class="alert alert-danger" role="alert">Löschen fehlgeschlagen</div>';
            break;
        case 3:
            $stat = '<br/><div class="alert alert-danger" role="alert">Beitrag hinzufügen fehlgeschlagen</div>';
            break;
        case 4:
            $stat = '<br/><div class="alert alert-success" role="alert">Beitrag wurde erfolgreich gelöscht</div>';
            break;
        case 5:
            $stat = '<br/><div class="alert alert-success" role="alert">Beitrag wurde erfolgreich bearbeitet</div>';
            break;
        case 6:
            $stat = '<br/><div class="alert alert-danger" role="alert">Hochladen Fehlgeschlagen! - Nur JPG, JPEG, PNG, GIF</div>';
            break;
        case 7:
            $stat = '<br/><div class="alert alert-danger" role="alert">Hochladen Fehlgeschlagen! - max. Dateigrösse 4 MB</div>';
            break;
        }
        return $stat;
}

//Event-Handling für Timeline
function checkRegistration($response) {
    $x= $response;
    switch ($x) {
        case 0:
            $stat = '<br/><div class="alert alert-success col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3" role="alert"><i class="fa fa-exclamation-triangle"></i>Besten Dank für Ihre Registrierung! Wir benachrichtigen Sie bei Freischaltung.</div>';
            break;
        case 1:
            $stat = '<br/><div class="alert alert-danger col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3" role="alert"><i class="fa fa-exclamation-triangle"></i>Registration fehlgeschlagen!</div>';
            break;
        case 2:
            $stat = '<br/><div class="alert alert-danger col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3" role="alert"><i class="fa fa-exclamation-triangle"></i>Sie sind bereits registriert. Bitte warten Sie auf Ihre Freigabe.</div>';
            break;
        case 3:
            $stat = '<br/><div class="alert alert-danger col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3" role="alert"><i class="fa fa-exclamation-triangle"></i>Registration fehlgeschlagen. Bitte eingaben überprüfen.</div>';
            break;
        }
        return $stat;
}

//Event-Handling Login Seite
function checkLogin($response) {
    $x = $response;
    switch ($x) {
        case 1:
            $stat = '<br/><div class="alert alert-danger col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" role="alert"><i class="fa fa-exclamation-triangle"></i>Falscher Benutzername oder Passwort!</div>';
            break;
        case 2:
            $stat = '<br/><div class="alert alert-danger col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" role="alert"><i class="fa fa-exclamation-triangle"></i>Sie erhalten eine Email mit Ihrem neuen Passwort</div>';
            break;
        case 3:
            $stat = '<br/><div class="alert alert-success col-xs-8 col-xs-offset-2 col-md-6 col-md-offset-3" role="alert"><i class="fa fa-exclamation-triangle"></i>Sie wurden erfolgreich aktiviert. Bitte loggen Sie sich hier mit Ihren Zugangsdaten ein.</div>';
            break;
        case 4:
            $stat = '<br/><div class="alert alert-danger col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" role="alert"><i class="fa fa-exclamation-triangle"></i>
                    Entschulding, leider ist die Registration fehlgeschlagen<br>Setzen Sie sich mit unserem Helpdesk in Verbindung.</div>';
            break;
        case 5:
            $stat = '<br/><div class="alert alert-danger col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" role="alert"><i class="fa fa-exclamation-triangle"></i>
                    Sie sind noch nicht aktiviert oder gesperrt.<br/>Bitte wenden Sie sich an die Hotline der Archconsulting.</div>';
            break;
        case 6:
            $stat = '<br/><div class="alert alert-danger col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" role="alert"><i class="fa fa-exclamation-triangle"></i>
                    Ihre Email Adresse wurde nicht in unserer Datenbank gefunden. Bitte Eingaben überprüfen.</div>';
            break; 
        case 7:
            $stat = '<br/><div class="alert alert-danger col-xs-6 col-xs-offset-3 col-md-4 col-md-offset-4" role="alert"><i class="fa fa-exclamation-triangle"></i>
                    ERROR! – Sie haben keine Berechtigung für diese Seite!</div>';
            break; 
    }
        return $stat;
}

//Event-Handling RMS Adressliste
function checkRMSAddress($response) {
    $x = $response;
    switch ($x) {
        case 0:
            $stat = '<div class="alert alert-success" role="alert">Update erfolgreich</div>';
            break;
        case 1:
            $stat = '<div class="alert alert-danger" role="alert">Update fehlgeschlagen!</div>';
            break;
        case 2:
            $stat = '<div class="alert alert-success" role="alert">Adresse erfolgreich gelöscht</div>';
            break;
        case 3:
            $stat = '<div class="alert alert-danger" role="alert">Löschen fehlgeschlagen!</div>';
            break;
        case 4:
            $stat = '<div class="alert alert-success" role="alert">Adresse erfolgreich hinzugefügt</div>';
            break;
        case 5:
            $stat = '<div class="alert alert-danger" role="alert">Adresse hinzufügen fehlgeschlagen!</div>';
            break;
        case 6:
            $stat = '<div class="alert alert-danger" role="alert">Adresse schon vorhanden!</div>';
            break; 
    }
        return $stat;
}

//Event-Handling RMS Userverwaltung
function checkRMSUser($response) {
    $x = $response;
    switch ($x) {
        case 0:
            $stat = '<div class="alert alert-success" role="alert">User erfolgreich aktiviert</div>';
            break;
        case 1:
            $stat = '<div class="alert alert-warning" role="alert">User aktivierung fehlgeschlagen!</div>';
            break;
        case 2:
            $stat = '<div class="alert alert-success" role="alert">User erfolgreich gesperrt</div>';
            break;
        case 3:
            $stat = '<div class="alert alert-warning" role="alert">User sperren fehlgeschlagen</div>';
            break;
        case 4:
            $stat = '<div class="alert alert-warning" role="alert">User ist bereits gesperrt oder inaktiv.</div>';
            break;
        case 5:
            $stat = '<div class="alert alert-warning" role="alert">User reaktivierung fehlgeschlagen!</div>';
            break;
        case 6:
            $stat = '<div class="alert alert-warning" role="alert">User die nicht gesperrt sind können nicht reaktiviert werden.</div>';
            break;
        case 7:
            $stat = '<div class="alert alert-success" role="alert">User erfolgreich gelöscht.</div>';
            break; 
        case 8:
            $stat = '<div class="alert alert-warning" role="alert">User löschen fehlgeschlagen!</div>';
            break; 
        case 9:
            $stat = '<div class="alert alert-warning" role="alert">Das Löschen ist nur für User des Typs «Architekt» zulässig!</div>';
            break; 
    }
        return $stat;
}

//Event-Handling RMS Bildverwaltung
function checkRMSimg($response) {
    $x = $response;
    switch ($x) {
        case 0:
            $stat = '<div class="alert alert-success" role="alert">Update erfolgreich</div>';
            break;
        case 1:
            $stat = '<div class="alert alert-warning" role="alert">Update fehlgeschlagen!</div>';
            break;
        case 2:
            $stat = '<div class="alert alert-success" role="alert">Löschen erfolgreich.</div>';
            break;
        case 3:
            $stat = '<div class="alert alert-warning" role="alert">Löschen fehlgeschlagen.</div>';
            break; 
    }
        return $stat;
}

//Event-Handling RMS Textverwaltung
function checkRMStext($response) {
    $x = $response;
    switch ($x) {
        case 0:
            $stat = '<div class="alert alert-warning" role="alert">Update fehlgeschlagen!</div>';
            break;
        case 1:
            $stat = '<div class="alert alert-success" role="alert">Update erfolgreich</div>';
            break; 
    }
        return $stat;
}