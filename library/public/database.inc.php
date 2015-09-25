<?php

/* 
 * Funktionsbibliothek mit allen Datenbank Abfragen und Änderungen.
 */


/*
 * BN: user04websrv02
 * PW: cfADs12P
 * DB: user04websrv02
 */

        
function connectDB() {
    $host = 'localhost';
    $user = 'root' ;
    $password = 'root' ;
    $database = 'unicircuit';
 // Stellt die Datenbank verbindung her
 $link = mysqli_connect($host, $user, $password, $database);
 //Codiert zeichen in UTF-8
 mysqli_set_charset($link, 'utf8');
 
 //Prüft die Datenbank Verbindung
 if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
 // liefert die DB Verbindund zurück
 return $link;
}

/*
 * Allgemein
 */

function getCountriesDe() {
    $sql = 'SELECT de FROM countries';
    return $sql;
}

/*
 * Userverwaltung
 */
function checkUser($em){
    $sql = "SELECT IdUser FROM User WHERE Email='$em'";
    return $sql;
}
// Erstellung Architekt User in DB
function createArchitect($link, $fn, $ln, $co, $zip, $ci, $cn, $pn, $mn, $em, $to, $p1, $da, $ti, $a1, $a2) {
    global $link;
    
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
    $a1 = mysqli_real_escape_string($link, $a1);
    $a2 = mysqli_real_escape_string($link, $a2);
    
    //Datenbank INSERT, fixe FK_IdUserType, hier handelt es sich immer um Architekt.
    $sql =  "INSERT INTO User (Firstname, Lastname, Company, ZIP, City, Country, 
              PhoneNumber, MobileNumber, Email, RegCode, Password, LastLoginDate, LastLoginTime, Fk_IdUserType, Active, Addressline1, Addressline2) 
              VALUES('$fn', '$ln','$co',$zip,'$ci','$cn','$pn','$mn', '$em','$to', '$p1', '$da', '$ti', 2, 1,'$a1', '$a2')";

    return $sql;
}
// Holt alle Daten der User in der Datenbank
function allUserData() {
    $sql = 'SELECT Fk_IdUserType, Firstname, Lastname, Company, ZIP, Country, PhoneNumber, 
            MobileNumber, Email, RegCode, LastLoginDate, LastLoginTime, IdUser, Active FROM User';
    return $sql;
}
// Holt Daten eines spezifischen Users anhand der ID
function userData($id) {
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'SELECT Fk_IdUserType, Firstname, Lastname, Company, Addressline1, Addressline2, ZIP, City, Country, Email,
    PhoneNumber, MobileNumber, RegCode, SessionId, LastLoginTime, LastLoginDate, Active, Picture FROM User WHERE IdUser = '. $id;
     
     return $sql;
}

// Setzt Aktivierungsstand des gewählten Users auf 2(Aktivierungsmail verschickt)
function setActive($id) {
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'UPDATE User SET Active = 2 WHERE IdUser = '. $id;
    
    return $sql;
}

// Setzt Aktivierungsstand des gewählten Users auf 3(Aktiviert)
function setActive3($id) {
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'UPDATE User SET Active = 3 WHERE RegCode = '. $id;
    
    return $sql;
}

// Setzt Aktivierungsstand des gewählten Users auf 4(Gesperrt)
function setActive4($id) {
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'UPDATE User SET Active = 4 WHERE IdUser = '. $id;

    return $sql;
}

// Setzt Aktivierungsstand des gewählten Users auf 3(Reaktivierung)
function reactivateUser($id) {
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $sql = 'UPDATE User SET Active = 3 WHERE IdUser = '. $id;

    return $sql;
}

//Hole Logo Architekt
function selectArchLogo($projectID){
    $id = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT u.Picture FROM User as u JOIN
        Project as p on p.Fk_IdArchitect = u.IdUser WHERE p.IdProject
        ='.$id;
    return $sql;
}



/*
 * Handwerkerliste
 */
function allProjectAddress($projectID){
    $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    $sql= 'SELECT p.IdProjectAddress, p.ProjectCoordinator, p.PhoneDirect, p. MobileNumber, p.EmailDirect, 
        p.Description, g.BKP, g.Company, g.Addressline1, g.Addressline2, g.ZIP, g.City,
        g.Country, g.Email, g.PhoneNumber, g.Homepage FROM ProjectAddresslist as p JOIN
        GlobalAddresslist as g on p.Fk_IdGlobalAddress = g.IdGlobalAddress WHERE p.Fk_IdProject
        ='.$projectID.' ORDER BY g.BKP, g.Company ASC';
    return $sql;
}

function newGlobalAddress($bkp, $company, $addressline1, $addressline2, $zip, $city, $country, 
        $email, $phoneNumber, $homepage){
    global $link;
    
    $bkp = filter_var($bkp, FILTER_SANITIZE_NUMBER_INT);
    $company = mysqli_real_escape_string($link, $company);
    $addressline1 = mysqli_real_escape_string($link, $addressline1);
    $addressline2 = mysqli_real_escape_string($link, $addressline2);
    $zip = filter_var($zip, FILTER_SANITIZE_NUMBER_INT);
    $city = mysqli_real_escape_string($link, $city);
    $country = mysqli_real_escape_string($link, $country);
    $email = mysqli_real_escape_string($link, $email);
    $phoneNumber = mysqli_real_escape_string($link, $phoneNumber);
    $homepage = mysqli_real_escape_string($link, $homepage);
    
    $sql= 'INSERT INTO GlobalAddresslist (BKP, Company, Addressline1, Addressline2, ZIP, City,
            Country, Email, PhoneNumber, Homepage) VALUES ('.$bkp.',"'.$company.'","'.$addressline1.'","'.
            $addressline2.'",'.$zip.',"'.$city.'","'.$country.'","'.$email.'","'.$phoneNumber.'","'.$homepage.'")';
    return $sql;
}

function newProjectAddress($projectID, $FKGlobal, $projectCoordinator, $phoneDirect, $mobile, 
        $emailDirect, $description){
    global $link;
    
    $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    $FKGlobal = filter_var($FKGlobal, FILTER_SANITIZE_NUMBER_INT);
    $projectCoordinator = mysqli_real_escape_string($link, $projectCoordinator);
    $phoneDirect = mysqli_real_escape_string($link, $phoneDirect);
    $mobile = mysqli_real_escape_string($link, $mobile);
    $emailDirect = mysqli_real_escape_string($link, $emailDirect);
    $description = mysqli_real_escape_string($link, $description);
    
    $sql= 'INSERT INTO ProjectAddresslist (Fk_IdProject, FK_IdGlobalAddress, ProjectCoordinator,
        PhoneDirect, MobileNumber, EmailDirect, Description) VALUES ('.$projectID.','.$FKGlobal.',"'
            .$projectCoordinator.'","'.$phoneDirect.'","'.$mobile.'","'.$emailDirect.'","'.$description.'")';
    return $sql;
}

function getIdGlobal($company, $addressline1){
    global $link;
    
    $company = mysqli_real_escape_string($link, $company);
    $addressline1 = mysqli_real_escape_string($link, $addressline1);
    
    $sql= 'SELECT IdGlobalAddress from GlobalAddresslist WHERE Company="'.$company.'"';
    return $sql;
}

function allGlobalAddress(){
    $sql= 'SELECT IdGlobalAddress, BKP, Company, ZIP, City, Country, Email, PhoneNumber, Homepage from GlobalAddresslist';
    return $sql;
}

function getGlobalAddress($id){
    $sql= 'SELECT BKP, Company, Addressline1, Addressline2, ZIP, City, Country, Email, PhoneNumber, Homepage
        from GlobalAddresslist WHERE IdGlobalAddress='.$id;
    return $sql;
}

function checkGlobalAddress($company){
    global $link;
    
    $company = mysqli_real_escape_string($link, $company);
    $comp= strtolower($company);
    
    $status=true;
    $link= connectDB();
    $sql= 'SELECT Company from GlobalAddresslist';
    $result= mysqli_query($link, $sql);
    while($row=  mysqli_fetch_array($result)){
        //Wandelt den String in kleinschreibung
        $data=strtolower($row['Company']);
        
        //Gibt die Anzahl an unterschiedlichen Zeichen aus
        $lev = levenshtein($comp, $data);
        
        if($lev < 2){
            return false;
        }else{
            $status=true;
        }
    }
    return $status;
}

function getProjectAddress($id){
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT p.IdProjectAddress, p.ProjectCoordinator, p.PhoneDirect, p. MobileNumber, p.EmailDirect, 
        p.Description, g.BKP, g.Company, g.Addressline1, g.Addressline2, g.ZIP, g.City,
        g.Country, g.Email, g.PhoneNumber, g.Homepage FROM ProjectAddresslist as p JOIN
        GlobalAddresslist as g on p.Fk_IdGlobalAddress = g.IdGlobalAddress WHERE p.IdProjectAddress
        ='.$id;
    return $sql;
}

function updateProjectAddress($id, $projectCoordinator, $phoneDirect, $mobileNumber, $emailDirect, $description){
    global $link;
    
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $projectCoordinator = mysqli_real_escape_string($link, $projectCoordinator);
    $phoneDirect = mysqli_real_escape_string($link, $phoneDirect);
    $mobileNumber = mysqli_real_escape_string($link, $mobileNumber);
    $emailDirect = mysqli_real_escape_string($link, $emailDirect);
    $description = mysqli_real_escape_string($link, $description);
    
    $sql= 'UPDATE ProjectAddresslist SET Projectcoordinator= "'.$projectCoordinator.'", PhoneDirect="'.$phoneDirect.
            '", MobileNumber="'.$mobileNumber.'", EmailDirect="'.$emailDirect.'", Description="'.$description.'" WHERE
                IdProjectAddress="'.$id.'"';
    return $sql;
}

function updateGlobalAddress($id, $bkp, $company, $addressline1, $addressline2, $zip, $city, $country, $email, $phone, $homepage){
    global $link;
    
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $bkp = mysqli_real_escape_string($link, $bkp);
    $company = mysqli_real_escape_string($link, $company);
    $addressline1 = mysqli_real_escape_string($link, $addressline1);
    $addressline2 = mysqli_real_escape_string($link, $addressline2);
    $zip = mysqli_real_escape_string($link, $zip);
    $city = mysqli_real_escape_string($link, $city);
    $country = mysqli_real_escape_string($link, $country);
    $email = mysqli_real_escape_string($link, $email);
    $phone = mysqli_real_escape_string($link, $phone);
    $homepage = mysqli_real_escape_string($link, $homepage);

    
    $sql= 'UPDATE GlobalAddresslist SET BKP= "'.$bkp.'", Company="'.$company.
            '", Addressline1="'.$addressline1.'", Addressline2="'.$addressline2.'", ZIP="'.$zip.'", City="'.$city.'", Country="'.$country.'"
                , Email="'.$email.'", PhoneNumber="'.$phone.'", Homepage="'.$homepage.'" WHERE
                IdGlobalAddress="'.$id.'"';
    return $sql;
}

function deleteProjectAddress($id){
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'DELETE FROM ProjectAddresslist WHERE IdProjectAddress='.$id;
    return $sql;
}

function statsOfGlobalAddress($idGlobal){
    $id = filter_var($idGlobal, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT IdProjectAddress FROM ProjectAddresslist WHERE Fk_IdGlobalAddress='.$id;
    return $sql;
}

function countProjectAddress($idGlobal){
    $id = filter_var($idGlobal, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT COUNT(*) FROM ProjectAddresslist as a JOIN Project as p on a.Fk_IdProject=
    p.IdProject WHERE Fk_IdGlobalAddress='.$id.' AND Storage IS NULL';
    return $sql;
}

function deleteGlobal1($idGlobal){
    $id = filter_var($idGlobal, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= "DELETE FROM ProjectAddresslist WHERE Fk_IdGlobalAddress=$id";
    return $sql;
}

function deleteGlobal2($idGlobal){
    $id = filter_var($idGlobal, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= "DELETE FROM GlobalAddresslist WHERE IdGlobalAddress=$id";
    return $sql;
}

function updateDeadlineCraft($idCraftsman){
    $id = filter_var($idCraftsman, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= "UPDATE Deadlines SET IdCraftsman=0 WHERE IdCraftsman=$id";
    return $sql;
}

/*
 * Timeline
 */
function addPostwithIMG($idProject,$idVisible, $hashName, $orgName, $path, $title, $content, $date, $time){
    global $link;
    
    $idProject = filter_var($idProject, FILTER_SANITIZE_NUMBER_INT);
    $idVisible = filter_var($idVisible, FILTER_SANITIZE_NUMBER_INT);
    $hashName = mysqli_real_escape_string($link, $hashName);
    $orgName = mysqli_real_escape_string($link, $orgName);
    // $path = mysqli_real_escape_string($link, $path);
    $title = mysqli_real_escape_string($link, $title);
    $content = mysqli_real_escape_string($link, $content);
   // $date = 
    // $time =
    $sql= "INSERT INTO Timeline (Fk_IdProject, Id_visible, HashName, OrgName, Path, Title, Date, Time, Description) VALUES
        ('$idProject', '$idVisible', '$hashName', '$orgName', '$path', '$title', '$date', '$time', '$content')";
    return $sql;
}

function selectPosts($projectID){
     $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    $sql= 'SELECT IdTimeline, Id_visible, HashName, Path, Title, Date, Time, Description FROM Timeline WHERE Fk_IdProject="'.$projectID.'" ORDER BY Date DESC, Time DESC';
    return $sql;
}

function selectPostbyID($postID){
     $postID = filter_var($postID, FILTER_SANITIZE_NUMBER_INT);
     
    $sql= 'SELECT IdTimeline, Id_visible, HashName, OrgName, Path, Title, Date, Time, Description FROM Timeline WHERE IdTimeline="'.$postID.'"';
    return $sql;
}

function updatePost($postID, $idVisible, $hashName, $orgName, $path, $title, $date, $time, $description){
    global $link;
    
    $postID = filter_var($postID, FILTER_SANITIZE_NUMBER_INT);
    $idVisible = filter_var($idVisible, FILTER_SANITIZE_NUMBER_INT);
    $hashName = mysqli_real_escape_string($link, $hashName);
    $orgName = mysqli_real_escape_string($link, $orgName);
    //path
    $title = mysqli_real_escape_string($link, $title);
    //$date
    // $time
    $description = mysqli_real_escape_string($link, $description);
    
    $sql= 'UPDATE Timeline SET Id_visible="'.$idVisible.'", HashName="'.$hashName.'", Orgname="'.$orgname.'",
     Path="'.$path.'", Title="'.$title.'", Date="'.$date.'", Time="'.$time.'", 
     Description="'.$description.'" WHERE IdTimeline="'.$postID.'"';
     return $sql;
}

function selectPostIMG($id){
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT HashName, Path FROM Timeline WHERE IdTimeline='.$id;
    return $sql;
}

function deletePost($id){
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'DELETE FROM Timeline WHERE IdTimeline='.$id;
    return $sql;
}


/*
 * SIA Baujournal
 */
function getProjectDates($id){
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT Fk_IdArchitect, Fk_IdBauherr, ProjectNumber, Title, Addressline1, Addressline2, ZIP, City,
            Country, Description, Picture FROM Project WHERE IdProject='.$id;
    return $sql;
}

function selectPostbyDate($projectID, $date){
    $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    // $date
    $sql= 'SELECT IdTimeline, Id_visible, HashName, OrgName, Path, Title, Date, Time, Description FROM Timeline WHERE Fk_IdProject="'.$projectID.'" AND Date="'.$date.'"';
    return $sql;
}


/*
 * Productsite
 */
function allContentProductsite(){    
    $sql = 'SELECT Title, Content FROM Productsite';
    return $sql;
}

function selectImages($idHTML){
    global $link;
    
    $idHTML = filter_var($idHTML, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT Filename, Comment FROM ProductsiteImages WHERE IdHTML='.$idHTML.' AND Active= 1';
    return $sql;
}


/*
 * Redaktionssystem
 */
function allContentOfIdHTML($id){
    global $link;
    
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql='SELECT Title, Content, InputType, Date, Time, Description FROM Productsite WHERE IdHTML='.$id;
    return $sql;
}

function saveToDB($title, $content){
    global $link;
    
    $title = mysqli_real_escape_string($link, $title);
    $content = mysqli_real_escape_string($link, $content);
    
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $sql= 'UPDATE Productsite SET Content ="'.$content.'", Date="'.$date.'", Time="'.$time.'" WHERE Title= "'.$title.'"';
    return $sql;
}

function allImagesOfIdHTML($id){
    global $link;
    
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql='SELECT Date, Time, Orgname, Path, ID, Filename, Comment, Active FROM ProductsiteImages WHERE IdHTML='.$id;
    return $sql;
}

function saveImageToDB($orgname, $comment, $file, $uploaddir, $select){
    global $link;
    
    $orgname = mysqli_real_escape_string($link, $orgname);
    $comment = mysqli_real_escape_string($link, $comment);
    // $file
    // $uploaddir
    $select = filter_var($select, FILTER_SANITIZE_NUMBER_INT);
    
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $sql= "INSERT INTO ProductsiteImages(Date, Time, Orgname, Comment, Filename, Path, IdHTML) VALUES
                ('$date', '$time', '$orgname', '$comment', '$file', '$uploaddir', '$select')";
    return $sql;
}

function deleteImage($id){
    global $link;
    
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'DELETE FROM ProductsiteImages WHERE ID='.$id;
    return $sql;
}

function selectFilename($id){
    global $link;
    
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT Filename, Path FROM ProductsiteImages WHERE ID='.$id;
    return $sql;
}

function updateImageStatus($id){
    global $link;
    
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql='UPDATE ProductsiteImages SET Active= 1 WHERE ID= '.$id;
    return $sql;
}

function setAllActiveNull($idHTML){
    global $link;
    
    $idHTML = filter_var($idHTML, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'UPDATE ProductsiteImages SET Active= 0 WHERE IdHTML= '.$idHTML;
    return $sql;
}

/*
 * Login 
 */

//User aus Datenbank auslesen, anhand übereinstimmung und passwort
function selectUser($email, $pw) {
    global $link;
    $email = mysqli_real_escape_string($link, $email);
    $pw = mysqli_real_escape_string($link, $pw);
    
   $sql = "SELECT IdUser, Fk_IdUserType, Active FROM User WHERE email='" . $email . "' "
            . "AND password='" . hash('sha256', $pw) . "'";
    return $sql;
}
//nach session eröffnung userDaten anpassen 
function updateUser($date, $time, $sessionId, $browserTyp, $datensatz) {
    global $link;
    
    //$date
    //$time
    //$sessionId
    //$browserTyp
    //$datensatz
   $sql = "UPDATE User SET LastLoginDate='$date',LastLoginTime='$time',
              SessionId='$sessionId', Browser='$browserTyp'
              WHERE IdUser ='$datensatz'";
   return $sql;
}

//Holt ProjectID
function getProjectId($datensatz) {
     $sql = "SELECT IdProject FROM Project WHERE Fk_IdBauherr = $datensatz ";
     return $sql;
}

//Holt UserDetails anhand Email
function getDetailsByMail($email) {
    $sql = "SELECT Firstname, Lastname FROM User WHERE Email = '$email'";
    return $sql;
}

//Ändert Passwort des Users in DB
function updatePassword($newPwHash, $email) {
    $sql = "UPDATE User SET Password = '$newPwHash' WHERE Email = '$email'";
    return $sql;
}


/*
 * Kontaktformular - Plattform
 */

function getMailArch($projectID){
    global $link;
    
    $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= "SELECT u.Email, p.ProjectNumber, p.Title FROM User as u JOIN Project as p on u.IdUser = p.Fk_IdArchitect WHERE p.IdProject=".$projectID;
    return $sql;
}

function getNameCust($projectID){
    global $link;
    
    $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    $sql= "SELECT u.Email, u.Firstname, u.Lastname, p.ProjectNumber, p.Title, p.Fk_IdArchitect FROM User as u JOIN Project as p on u.IdUser = p.Fk_IdBauherr WHERE p.IdProject=".$projectID;
    return $sql;
}


/*
 * Projektverwaltung
 */

//erstellt einen Bauherr in der Datenbank
function createBauherr($bhFn, $bhLn, $bhAddressline1, $bhAddressline2, $bhZIP, $bhCity, $bhCountry, $bhEmail, $bhPhNu, $bhMoNu, $pwHash) {
    global $link;
    
    $bhFn = mysqli_real_escape_string($link, $bhFn);
    $bhLn = mysqli_real_escape_string($link, $bhLn);
    $bhAddressline1 = mysqli_real_escape_string($link, $bhAddressline1);
    $bhAddressline2 = mysqli_real_escape_string($link, $bhAddressline2);
    $bhZIP = filter_var($bhZIP, FILTER_SANITIZE_NUMBER_INT);
    $bhCity = mysqli_real_escape_string($link, $bhCity);
    $bhCountry = mysqli_real_escape_string($link, $bhCountry);
    $bhEmail = mysqli_real_escape_string($link, $bhEmail);
    $bhPhNu = mysqli_real_escape_string($link, $bhPhNu);
    $bhMoNu = mysqli_real_escape_string($link, $bhMoNu);
    $pwHash = mysqli_real_escape_string($link, $pwHash);
    
    $sql= "INSERT INTO User (Firstname, Lastname, Addressline1, Addressline2, ZIP, City, Country, Email, PhoneNumber, MobileNumber,
             Password, Fk_IdUserType, Active) VALUES
             ('$bhFn',  '$bhLn', '$bhAddressline1', '$bhAddressline2', '$bhZIP', '$bhCity', '$bhCountry', '$bhEmail',
             '$bhPhNu', '$bhMoNu', '$pwHash', 3, 3)";
    return $sql;
}

//Holt ID eines spezifischen Bauherren
function getIdBauherr($pwHash) {
    global $link;
    
    $pwHash = mysqli_real_escape_string($link, $pwHash);
    
     $sql = 'SELECT IdUser FROM User WHERE Password="'.$pwHash.'"';
     return $sql;
}

//Erstellt das Projekt mit allen benötigten Daten
function createProject($id, $bhId, $projectNumb, $title, $addressline1, $addressline2, $zip, $city, $country, $description) {
    global $link;
    
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    $bhId = filter_var($bhId, FILTER_SANITIZE_NUMBER_INT);
    $projectNumb = filter_var($projectNumb, FILTER_SANITIZE_NUMBER_INT);
    $title = mysqli_real_escape_string($link, $title);
    $addressline1 = mysqli_real_escape_string($link, $addressline1);
    $addressline2 = mysqli_real_escape_string($link, $addressline2);
    $zip = filter_var($zip, FILTER_SANITIZE_NUMBER_INT);
    $city = mysqli_real_escape_string($link, $city);
    $country = mysqli_real_escape_string($link, $country);
    $description = mysqli_real_escape_string($link, $description);
    
     $sql = "INSERT INTO Project (Fk_IdArchitect, Fk_IdBauherr, ProjectNumber, Title, Addressline1, Addressline2, ZIP,
             City, Country, Description)
             VALUES ('$id', '$bhId', '$projectNumb', '$title', '$addressline1' ,'$addressline2' ,'$zip' ,'$city' ,'$country' ,'$description')";
     return $sql;
}

//Holt ID eines spezifischen Projektes
function getIdProject($projectNumb, $bhId){
    global $link;
    
    $projectNumb = filter_var($projectNumb, FILTER_SANITIZE_NUMBER_INT);
    $bhId = filter_var($bhId, FILTER_SANITIZE_NUMBER_INT);
    
     $sql = "SELECT IdProject FROM Project WHERE ProjectNumber ='$projectNumb'AND Fk_IdBauherr = '$bhId' " ;
     return $sql;
}

//Gibt alle Projekte eines Architekten
function getProjectsByArch($id) {
    global $link;
    
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql = 'SELECT p.IdProject, p.ProjectNumber, p.Title, p.Addressline1, p.Addressline2, p.ZIP, p.City,
        p.Country, p.Description, p.Picture, u.IdUser, u.Firstname, u.Lastname FROM Project as p JOIN User
        as u on p.Fk_IdBauherr = u.IdUser WHERE Fk_IdArchitect = '.$id.' AND Storage IS NULL ';
    return $sql;
}

//Fügt Bild dem Projekt hinzu
function addPicToProject($uploadfile, $proId){
    global $link;
    
    //$uploadfile
    $proId = filter_var($proId, FILTER_SANITIZE_NUMBER_INT);
    
     $sql= "UPDATE Project SET picture = '$uploadfile' WHERE IdProject = '$proId'";
     return $sql;
}

//Projekt Update mit neuem Bild
function updateProjectWithPic($projectNumb, $title, $addressline1, $addressline2, $zip, $city, $country, $description, $uploadfile, $bhFn, $bhLn,
        $bhAddressline1, $bhAddressline2, $bhZIP, $bhCity, $bhCountry, $bhPhNu, $bhMoNu, $bhEmail, $proId2) {
    global $link;
    
    $projectNumb = filter_var($projectNumb, FILTER_SANITIZE_NUMBER_INT);
    $title = mysqli_real_escape_string($link, $title);
    $addressline1 = mysqli_real_escape_string($link, $addressline1);
    $addressline2 = mysqli_real_escape_string($link, $addressline2);
    $zip = filter_var($zip, FILTER_SANITIZE_NUMBER_INT);
    $city = mysqli_real_escape_string($link, $city);
    $country = mysqli_real_escape_string($link, $country);
    $description = mysqli_real_escape_string($link, $description);
    //$uploadfile
    $bhFn = mysqli_real_escape_string($link, $bhFn);
    $bhLn = mysqli_real_escape_string($link, $bhLn);
    $bhAddressline1 = mysqli_real_escape_string($link, $bhAddressline1);
    $bhAddressline2 = mysqli_real_escape_string($link, $bhAddressline2);
    $bhZIP = filter_var($bhZIP, FILTER_SANITIZE_NUMBER_INT);
    $bhCity = mysqli_real_escape_string($link, $bhCity);
    $bhCountry = mysqli_real_escape_string($link, $bhCountry);
    $bhPhNu = mysqli_real_escape_string($link, $bhPhNu);
    $bhMoNu = mysqli_real_escape_string($link, $bhMoNu);
    $bhEmail = mysqli_real_escape_string($link, $bhEmail);
    $proId2 = filter_var($proId2, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= "UPDATE Project AS p, User AS u SET p.ProjectNumber = '$projectNumb', p.Title = '$title',
                    p.Addressline1 = '$addressline1', p.Addressline2 = '$addressline2', p.ZIP = '$zip', p.City = '$city',
                    p.Country = '$country', p.Description = '$description', p.Picture= '$uploadfile' ,
                    u.Firstname = '$bhFn' , u.Lastname = '$bhLn' , u.Addressline1 = '$bhAddressline1' ,
                    u.Addressline2 = '$bhAddressline2', u.ZIP = '$bhZIP' , u.City = '$bhCity' , u.Country = '$bhCountry' ,
                    u.PhoneNumber = '$bhPhNu' , u.MobileNumber = '$bhMoNu', u.Email = '$bhEmail' 
                    WHERE p.Fk_IdBauherr = u.IdUser AND IdProject = '$proId2'";
                    
    return $sql;
}

//Projekt Update ohne neues Bild
function updateProjectWithout($projectNumb, $title, $addressline1, $addressline2, $zip, $city, $country, $description, $bhFn, $bhLn,
        $bhAddressline1, $bhAddressline2, $bhZIP, $bhCity, $bhCountry, $bhPhNu, $bhMoNu, $bhEmail, $proId2) {
    global $link;
    
    $projectNumb = filter_var($projectNumb, FILTER_SANITIZE_NUMBER_INT);
    $title = mysqli_real_escape_string($link, $title);
    $addressline1 = mysqli_real_escape_string($link, $addressline1);
    $addressline2 = mysqli_real_escape_string($link, $addressline2);
    $zip = filter_var($zip, FILTER_SANITIZE_NUMBER_INT);
    $city = mysqli_real_escape_string($link, $city);
    $country = mysqli_real_escape_string($link, $country);
    $description = mysqli_real_escape_string($link, $description);
    $bhFn = mysqli_real_escape_string($link, $bhFn);
    $bhLn = mysqli_real_escape_string($link, $bhLn);
    $bhAddressline1 = mysqli_real_escape_string($link, $bhAddressline1);
    $bhAddressline2 = mysqli_real_escape_string($link, $bhAddressline2);
    $bhZIP = filter_var($bhZIP, FILTER_SANITIZE_NUMBER_INT);
    $bhCity = mysqli_real_escape_string($link, $bhCity);
    $bhCountry = mysqli_real_escape_string($link, $bhCountry);
    $bhPhNu = mysqli_real_escape_string($link, $bhPhNu);
    $bhMoNu = mysqli_real_escape_string($link, $bhMoNu);
    $bhEmail = mysqli_real_escape_string($link, $bhEmail);
    $proId2 = filter_var($proId2, FILTER_SANITIZE_NUMBER_INT);
    
   $sql = "UPDATE Project AS p, User AS u SET p.ProjectNumber = '$projectNumb', p.Title = '$title',
                    p.Addressline1 = '$addressline1', p.Addressline2 = '$addressline2', p.ZIP = '$zip', p.City = '$city',
                    p.Country = '$country', p.Description = '$description',
                    u.Firstname = '$bhFn' , u.Lastname = '$bhLn' , u.Addressline1 = '$bhAddressline1' ,
                    u.Addressline2 = '$bhAddressline2', u.ZIP = '$bhZIP' , u.City = '$bhCity' , u.Country = '$bhCountry' ,
                    u.PhoneNumber = '$bhPhNu' , u.MobileNumber = '$bhMoNu', u.Email = '$bhEmail' 
                    WHERE p.Fk_IdBauherr = u.IdUser AND IdProject = '$proId2'";
    return $sql;
}

//Archivierung eines Projektes
function storeProject($proId2) {
    global $link;
    $proId2 = filter_var($proId2, FILTER_SANITIZE_NUMBER_INT);
    
     $sql = "UPDATE Project AS p, User AS u SET p.Storage = 1 , u.Active = 4 WHERE p.FK_IdBauherr = u.IdUser AND IdProject = '$proId2'";
     return $sql;
}

//gibt Status des Projekts
function getStatusProject($proID){
    global $link;
    $proID = filter_var($proID, FILTER_SANITIZE_NUMBER_INT);
    
    $sql="SELECT Storage FROM Project WHERE IdProject=".$proID;
    return $sql;
}

//Passwort Update des Bauherren
function resetBauhPw($IdProject, $pwHash) {
    global $link;
    $IdProject = filter_var($IdProject, FILTER_SANITIZE_NUMBER_INT);
    $pwHash = mysqli_real_escape_string($link, $pwHash);
    
    $sql = "UPDATE User AS u JOIN Project AS p ON u.IdUser = p.Fk_IdBauherr AND p.IdProject = '$IdProject' SET u.Password = '$pwHash'";
    return $sql;
}

//Update der Usersettings Architekt, mit neuem Logo
function updateArchWithPic($firstname, $lastname, $company, $addressline1, $addressline2, $zip, $city, 
        $country, $phoneNumber, $mobileNumber, $email, $uploadfile, $id) {
    global $link;
    $firstname = mysqli_real_escape_string($link, $firstname);
    $lastname = mysqli_real_escape_string($link, $lastname);
    $company = mysqli_real_escape_string($link, $company);
    $addressline1 = mysqli_real_escape_string($link, $addressline1);
    $addressline2 = mysqli_real_escape_string($link, $addressline2);
    $zip = filter_var($zip, FILTER_SANITIZE_NUMBER_INT);
    $city = mysqli_real_escape_string($link, $city);
    $country = mysqli_real_escape_string($link, $country);
    $phoneNumber = mysqli_real_escape_string($link, $phoneNumber);
    $mobileNumber = mysqli_real_escape_string($link, $mobileNumber);
    $email = mysqli_real_escape_string($link, $email);
    //$uploadfile
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql = "UPDATE User SET Firstname = '$firstname', Lastname = '$lastname', Company = '$company', Addressline1 = '$addressline1', 
           Addressline2 = '$addressline2', ZIP = '$zip', City = '$city', Country = '$country', PhoneNumber = '$phoneNumber',
           MobileNumber = '$mobileNumber', Email = '$email', Picture = '$uploadfile' WHERE IdUser = '$id'";
   return $sql;
            
}

//Update Usersettings Architekt ohne Logo
function updateArchWithoutPic($firstname, $lastname, $company, $addressline1, $addressline2, $zip, $city, $country, $phoneNumber,
                        $mobileNumber, $email, $id) {
    global $link;
    $firstname = mysqli_real_escape_string($link, $firstname);
    $lastname = mysqli_real_escape_string($link, $lastname);
    $company = mysqli_real_escape_string($link, $company);
    $addressline1 = mysqli_real_escape_string($link, $addressline1);
    $addressline2 = mysqli_real_escape_string($link, $addressline2);
    $zip = filter_var($zip, FILTER_SANITIZE_NUMBER_INT);
    $city = mysqli_real_escape_string($link, $city);
    $country = mysqli_real_escape_string($link, $country);
    $phoneNumber = mysqli_real_escape_string($link, $phoneNumber);
    $mobileNumber = mysqli_real_escape_string($link, $mobileNumber);
    $email = mysqli_real_escape_string($link, $email);
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql = "UPDATE User SET Firstname = '$firstname', Lastname = '$lastname', Company = '$company', Addressline1 = '$addressline1', 
           Addressline2 = '$addressline2', ZIP = '$zip', City = '$city', Country = '$country', PhoneNumber = '$phoneNumber',
           MobileNumber = '$mobileNumber', Email = '$email' WHERE IdUser = '$id'";
   return $sql;
}


//Holt Infos eines Users anhand der Id
function getUserbyId($id) {
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        
    $sql = 'SELECT Firstname, Lastname, Company, Addressline1, Addressline2, ZIP, City, Country,
            Email, PhoneNumber, MobileNumber, Fk_IdUserType FROM User WHERE IdUser = '.$id ;
    return $sql;
}
//Neues Passwort für den User in der DB sichern
function updateUserPw($p1, $id) {
    $sql = "UPDATE User SET Password = '$p1' WHERE IdUser = '$id' ";
    return $sql;
}

/*
 * Storage Projekte
 */

function getProjectsByArchStore($id) {
    global $link;
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql = 'SELECT p.IdProject, p.ProjectNumber, p.Title, p.Addressline1, p.Addressline2, p.ZIP, p.City,
        p.Country, p.Description, p.Picture, u.IdUser, u.Firstname, u.Lastname FROM Project as p JOIN User
        as u on p.Fk_IdBauherr = u.IdUser WHERE Fk_IdArchitect = '.$id.' AND Storage = 1 ORDER BY p.ProjectNumber ASC';
    return $sql;
}

function deleteProject($idProject) {
    $idProject = filter_var($idProject, FILTER_SANITIZE_NUMBER_INT);
    $sql = "DELETE FROM Project WHERE IdProject = '$idProject'";
    
    return $sql;
    
}

function deleteBauherr($idBauherr) {
    $idBauherr = filter_var($idBauherr, FILTER_SANITIZE_NUMBER_INT);
    $sql = "DELETE FROM User WHERE IdUser = '$idBauherr'";
    
    return $sql;
}
/*
 * Galerie - Plattform
 */
function saveIMG($idProject, $hashNameL, $hashNameS, $orgName, $path, $comment, $visible){
    global $link;
    $idProject = filter_var($idProject, FILTER_SANITIZE_NUMBER_INT);
    //$hashNameS
    //$orgName
    //$path
    $comment = mysqli_real_escape_string($link, $comment);
    $visible = filter_var($visible, FILTER_SANITIZE_NUMBER_INT);
    
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $sql= "INSERT INTO Pictures(Fk_IdProject, HashNameL, HashNameS, Orgname, Path, Date, Time, Comment, Visibility) VALUES
                ('$idProject', '$hashNameL', '$hashNameS', '$orgName', '$path', '$date', '$time', '$comment', '$visible')";
    return $sql;
}

function showIMG($idProject, $visibility){
    global $link;
    $idProject = filter_var($idProject, FILTER_SANITIZE_NUMBER_INT);
    $visibility = filter_var($visibility, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= "SELECT IdPicture, HashNameL, HashNameS, Comment, Date, Time FROM Pictures WHERE Fk_IdProject = '$idProject' AND Visibility= '$visibility' ORDER BY Date DESC, Time DESC";
    return $sql;
}

function showAllIMG($idProject){
    global $link;
    $idProject = filter_var($idProject, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= "SELECT IdPicture, HashNameL, HashNameS, Comment, Date, Time FROM Pictures WHERE Fk_IdProject = '$idProject' ORDER BY Date DESC, Time DESC";
    return $sql;
}

function deleteImgGallery($idIMG){
    global $link;
    $idIMG = filter_var($idIMG, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'Delete FROM Pictures WHERE IdPicture='.$idIMG;
    return $sql;
}

function getIMGPath($idIMG){
    global $link;
    $idIMG = filter_var($idIMG, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT Fk_IdProject, HashNameL, HashNameS FROM Pictures WHERE IdPicture='.$idIMG;
    return $sql;
}


/*
 * ****** Events
 */
function newEvent($projectID, $date, $time, $title, $description, $location){
    global $link;
    $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    //$date
    //$time
    $title = mysqli_real_escape_string($link, $title);
    $description = mysqli_real_escape_string($link, $description);
    $location = mysqli_real_escape_string($link, $location);
    
    $sql= "INSERT INTO Events (Fk_IdProject, Date, Time, Title, Description, Location) VALUES
            ('$projectID','$date','$time','$title','$description','$location')";
    return $sql;
}

function updateEvent($eventID, $date, $time, $title, $description, $location){
    global $link;
    $eventID = filter_var($eventID, FILTER_SANITIZE_NUMBER_INT);
    //$date
    //$time
    $title = mysqli_real_escape_string($link, $title);
    $description = mysqli_real_escape_string($link, $description);
    $location = mysqli_real_escape_string($link, $location);
    
    $sql= "UPDATE Events SET Date='$date', Time='$time', Title='$title', Description='$description',"
            . "Location='$location' WHERE IdEvent= ".$eventID;
    return $sql;
}


function getAllEvents($projectID){
    global $link;
    $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT IdEvent, Date, Time, Title, Description, Location FROM Events WHERE Fk_IdProject='.$projectID.' ORDER BY Date ASC, Time ASC';
    return $sql;
}

function selectEvent($eventID){
    global $link;
    $eventID = filter_var($eventID, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT Date, Time, Title, Description, Location FROM Events WHERE IdEvent='.$eventID;
    return $sql;
}

function deleteOld($projectID){
    global $link;
    $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'DELETE FROM Events WHERE Fk_IdProject='.$projectID.' AND date < CURDATE()';
    return $sql;
}

function deleteEvent($eventID){
    global $link;
    $eventID = filter_var($eventID, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'DELETE FROM Events WHERE IdEvent='.$eventID;
    return $sql;
}


/*
 ***** Deadlines
 */
function newDeadline($projectID, $deadlineDate, $title, $description, $idCraftsman){
    global $link;
    $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    //$deadlineDate
    $title = mysqli_real_escape_string($link, $title);
    $description = mysqli_real_escape_string($link, $description);
    $idCraftsman = filter_var($idCraftsman, FILTER_SANITIZE_NUMBER_INT);
    
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $sql= "INSERT INTO Deadlines (Fk_IdProject, IdCraftsman, Date, Time, DeadlineDate, DeadlineTitle, DeadlineDescription) VALUES
            ('$projectID','$idCraftsman','$date','$time','$deadlineDate','$title','$description')";
    return $sql;
}

function getAllDeadlines($projectID){
    global $link;
    $projectID = filter_var($projectID, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT IdDeadlines, IdCraftsman, DeadlineDate, DeadlineTitle, DeadlineDescription FROM Deadlines WHERE Fk_IdProject='.$projectID.' ORDER BY DeadlineDate ASC';
    return $sql;
}

function selectDeadlines($id){
    global $link;
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'SELECT IdCraftsman, DeadlineDate, DeadlineTitle, DeadlineDescription, Fk_IdProject FROM Deadlines WHERE IdDeadlines='.$id;
    return $sql;
}

function updateDeadline($id, $deadlineDate, $title, $description, $idCraftsman){
    global $link;
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    //$deadlineDate
    $title = mysqli_real_escape_string($link, $title);
    $description = mysqli_real_escape_string($link, $description);
    $idCraftsman = filter_var($idCraftsman, FILTER_SANITIZE_NUMBER_INT);
    
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $sql= "UPDATE Deadlines SET IdCraftsman='$idCraftsman', Date='$date', Time='$time', DeadlineDate='$deadlineDate', DeadlineTitle='$title', "
            . "DeadlineDescription='$description' WHERE IdDeadlines=".$id;
    return $sql;
}

function deleteDeadline($id){
    global $link;
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
    
    $sql= 'DELETE FROM Deadlines WHERE IdDeadlines='.$id;
    return $sql;
}

/*
 ******* Schedule
 */
function saveSchedule($idProject, $hashName, $orgName, $path, $comment){
    global $link;
    $idProject = filter_var($idProject, FILTER_SANITIZE_NUMBER_INT);
    //$hashName
    //$orgName
    //$path
    $comment = mysqli_real_escape_string($link, $comment);
    
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $sql= "INSERT INTO Schedule(Fk_IdProject, HashName, Orgname, Path, Date, Time, Comment) VALUES
                ('$idProject', '$hashName', '$orgName', '$path', '$date', '$time', '$comment')";
    return $sql;
}

function showAllSchedule($idProject){
    global $link;
    $idProject = filter_var($idProject, FILTER_SANITIZE_NUMBER_INT);

    $sql= "SELECT IdSchedule, HashName, Orgname, Path, Comment, Date, Time FROM Schedule WHERE Fk_IdProject = '$idProject' ORDER BY Date DESC, Time DESC";
    return $sql;
}
