<?php

/* 
 * Funktionsbibliothek mit allen Datenbank Abfragen und Änderungen.
 */


$host = 'palmers.dynathome.net:8024/phpmyadmin';
$user = 'user04websrv02' ;
$password = 'cfADs12P' ;
$database = 'user04websrv02';

        
function connectDB() {
 $link = mysqli_connect($host, $user, $password, $database) or die ('Verbindung zur Datenbank nicht möglich.');
 
 return $link;
}



