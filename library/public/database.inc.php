<?php

/* 
 * Funktionsbibliothek mit allen Datenbank Abfragen und Änderungen.
 */




        
function connectDB() {
    $host = 'localhost';
    $user = 'user04websrv02' ;
    $password = 'cfADs12P' ;
    $database = 'user04websrv02';
 // Stellt die Datenbank verbindung her
 $link = mysqli_connect($host, $user, $password, $database);
 
 
 //Prüft die Datenbank Verbindung
 if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
 // liefert die DB Verbindund zurück
 return $link;
}

function createArchitect($link, $fn, $ln, $co, $zip, $ci, $cn, $pn, $mn, $em, $to, $p1, $da, $ti) {
    
    //Escapen der Variablen, Verhinderung von SQL Injections.
    $fn = mysqli_real_escape_string($link, $fn);
    $ln = mysqli_real_escape_string($link, $ln);
    $co = mysqli_real_escape_string($link, $co);
    $zip = mysqli_real_escape_string($link, $zip);
    $ci = mysqli_real_escape_string($link, $ci);
    $cn = mysqli_real_escape_string($link, $cn);
    $pn = mysqli_real_escape_string($link, $pn);
    $mn = mysqli_real_escape_string($link, $mn);
    $em = mysqli_real_escape_string($link, $em);
    $to = mysqli_real_escape_string($link, $to);
    $p1 = mysqli_real_escape_string($link, $p1);
    $da = mysqli_real_escape_string($link, $da);
    $ti = mysqli_real_escape_string($link, $ti);
    
    //Datenbank INSERT, fixe FK_IdUserType, hier handelt es sich immer um Architekt.
    $sql =  "INSERT INTO User (Firstname, Lastname, Company, ZIP, City, Country, 
              PhoneNumber, MobileNumber, Email, RegCode, Password, LastLoginDate, LastLoginTime, Fk_IdUserType) 
              VALUES('$fn', '$ln','$co',$zip,'$ci','$cn','$pn','$mn', '$em','$to', '$p1', '$da', '$ti', 2)";

    return $sql;
}



