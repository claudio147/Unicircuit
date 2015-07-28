<?php

/* 
 * Funktionsbibliothek mit allen Datenbank Abfragen und Änderungen.
 */




        
function connectDB() {
    $host = 'localhost';
    $user = 'user04websrv02' ;
    $password = 'cfADs12P' ;
    $database = 'user04websrv02';
    
 $link = mysqli_connect($host, $user, $password, $database) or die ('Verbindung zur Datenbank nicht möglich.');
 
 return $link;
}

function createArchitect($fn, $ln, $co, $zip, $ci, $cn, $pn, $mn, $em, $to, $p1, $da, $ti) {
    $sql =  "INSERT INTO User (Firstname, Lastname, Company, ZIP, City, Country, 
              PhoneNumber, MobileNumber, Email, RegCode, Password, LastLoginDate, LastLoginTime, Fk_IdUserType) 
              VALUES('$fn', '$ln','$co',$zip,'$ci','$cn','$pn','$mn', '$em','$to', '$p1', '$da', '$ti', 2)";

    return $sql;
}

function createArchitect2($fn, $ln, $co, $zip, $ci, $cn, $pn, $mn, $em, $da, $ti, $p1, $to) {
    $sql = "INSERT INTO User (Firstname, Lastname, Company, ZIP, City, Country, 
              PhoneNumber, MobileNumber,  Email, LastLoginDate, LastLoginTime, Password, RegCode, Fk_IdUserType) 
              VALUES('$fn', '$ln','$co',$zip,'$ci','$cn','$pn','$mn' '$em', '$da', '$ti', md5($p1), '$to', 2)";

    return $sql;
}


