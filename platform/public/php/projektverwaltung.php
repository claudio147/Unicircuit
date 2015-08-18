<?php

/*
 *  Programmpunkt 1.1 Projektverwaltung
 */

//Session starten oder wiederaufnehmen
 session_start();
    
//Einbindung Librarys
require_once ('../../../library/public/database.inc.php');


$id = $_SESSION['IdUser'];


if(empty($_SESSION['IdUser'])) {
    header('Location: denied.php');
}

$link = connectDB();
$sql = 'SELECT Firstname, Lastname, Company, SessionId FROM User WHERE IdUser ='.$id;
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$fn = $row['Firstname'];
$SID1 = $row['SessionId'];

$SID2 = session_id();
$SID3 = $_SESSION['SID'];

echo '<p>SessionID aus DB:'.$SID1.'</p>' ;
echo '<p>SessionID aus Session:'.$SID2.'</p>' ;
echo '<p>SessionID aus SessionLogin:'.$SID3.'</p>' ;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

