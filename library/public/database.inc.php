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

// Erstellung Architekt User in DB
function createArchitect($link, $fn, $ln, $co, $zip, $ci, $cn, $pn, $mn, $em, $to, $p1, $da, $ti, $a1, $a2) {
    
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
    $sql = 'SELECT Firstname, Lastname, Company, ZIP, Country, PhoneNumber, 
            MobileNumber, Email, RegCode, LastLoginDate, LastLoginTime, IdUser, Active FROM User';
    return $sql;
}
// Holt Daten eines spezifischen Users anhand der ID
function userData($id) {
     $sql = 'SELECT Firstname, Lastname, Email, RegCode FROM User WHERE IdUser = '. $id;
     
     return $sql;
}

// Setzt Aktivierungsstand des gewählten Users auf 2(Aktivierungsmail verschickt)
function setActive($id) {
    $sql = 'UPDATE User SET Active = 2 WHERE IdUser = '. $id;
    
    return $sql;
}

// Setzt Aktivierungsstand des gewählten Users auf 3(Aktiviert)
function setActive3($id) {
    $sql = 'UPDATE User SET Active = 3 WHERE RegCode = '. $id;
    
    return $sql;
}



/*
 * Handwerkerliste
 */
function allProjectAddress($projectID){
    $sql= 'SELECT p.IdProjectAddress, p.ProjectCoordinator, p.PhoneDirect, p. MobileNumber, p.EmailDirect, 
        p.Description, g.BKP, g.Company, g.Addressline1, g.Addressline2, g.ZIP, g.City,
        g.Country, g.Email, g.PhoneNumber, g.Homepage FROM ProjectAddresslist as p JOIN
        GlobalAddresslist as g on p.Fk_IdGlobalAddress = g.IdGlobalAddress WHERE p.Fk_IdProject
        ='.$projectID.' ORDER BY g.BKP, g.Company ASC';
    return $sql;
}

function newGlobalAddress($bkp, $company, $addressline1, $addressline2, $zip, $city, $country, 
        $email, $phoneNumber, $homepage){
    $sql= 'INSERT INTO GlobalAddresslist (BKP, Company, Addressline1, Addressline2, ZIP, City,
            Country, Email, PhoneNumber, Homepage) VALUES ('.$bkp.',"'.$company.'","'.$addressline1.'","'.
            $addressline2.'",'.$zip.',"'.$city.'","'.$country.'","'.$email.'","'.$phoneNumber.'","'.$homepage.'")';
    return $sql;
}

function newProjectAddress($projectID, $FKGlobal, $projectCoordinator, $phoneDirect, $mobile, 
        $emailDirect, $description){
    $sql= 'INSERT INTO ProjectAddresslist (Fk_IdProject, FK_IdGlobalAddress, ProjectCoordinator,
        PhoneDirect, MobileNumber, EmailDirect, Description) VALUES ('.$projectID.','.$FKGlobal.',"'
            .$projectCoordinator.'","'.$phoneDirect.'","'.$mobile.'","'.$emailDirect.'","'.$description.'")';
    return $sql;
}

function getIdGlobal($company, $addressline1){
    $sql= 'SELECT IdGlobalAddress from GlobalAddresslist WHERE Company="'.$company.'"';
    return $sql;
}

function allGlobalAddress(){
    $sql= 'SELECT IdGlobalAddress, BKP, Company, ZIP, City, PhoneNumber, Homepage from GlobalAddresslist';
    return $sql;
}

function getGlobalAddress($id){
    $sql= 'SELECT BKP, Company, Addressline1, Addressline2, ZIP, City, Country, Email, PhoneNumber, Homepage
        from GlobalAddresslist WHERE IdGlobalAddress='.$id;
    return $sql;
}

function checkGlobalAddress($company){
    $status=true;
    $link= connectDB();
    $sql= 'SELECT Company from GlobalAddresslist';
    $result= mysqli_query($link, $sql);
    while($row=  mysqli_fetch_array($result)){
        if($company== $row['Company']){
            return false;
        }else{
            $status=true;
        }
    }
    return $status;
}

function getProjectAddress($id){
    $sql= 'SELECT p.IdProjectAddress, p.ProjectCoordinator, p.PhoneDirect, p. MobileNumber, p.EmailDirect, 
        p.Description, g.BKP, g.Company, g.Addressline1, g.Addressline2, g.ZIP, g.City,
        g.Country, g.Email, g.PhoneNumber, g.Homepage FROM ProjectAddresslist as p JOIN
        GlobalAddresslist as g on p.Fk_IdGlobalAddress = g.IdGlobalAddress WHERE p.IdProjectAddress
        ='.$id;
    return $sql;
}

function updateProjectAddress($id, $projectCoordinator, $phoneDirect, $mobileNumber, $emailDirect, $description){
    $sql= 'UPDATE ProjectAddresslist SET Projectcoordinator= "'.$projectCoordinator.'", PhoneDirect="'.$phoneDirect.
            '", MobileNumber="'.$mobileNumber.'", EmailDirect="'.$emailDirect.'", Description="'.$description.'" WHERE
                IdProjectAddress="'.$id.'"';
    return $sql;
}

function deleteProjectAddress($id){
    $sql= 'DELETE FROM ProjectAddresslist WHERE IdProjectAddress="'.$id.'"';
    return $sql;
}

/*
 * Timeline
 */
function addPostwithIMG($idProject,$idVisible, $hashName, $orgName, $path, $title, $content, $date, $time){
    $sql= "INSERT INTO Timeline (Fk_IdProject, Id_visible, HashName, OrgName, Path, Title, Date, Time, Description) VALUES
        ('$idProject', '$idVisible', '$hashName', '$orgName', '$path', '$title', '$date', '$time', '$content')";
    return $sql;
}

function selectPosts($projectID){
    $sql= 'SELECT IdTimeline, Id_visible, HashName, Path, Title, Date, Time, Description FROM Timeline WHERE Fk_IdProject="'.$projectID.'" ORDER BY Date DESC, Time DESC';
    return $sql;
}

function selectPostbyID($postID){
    $sql= 'SELECT IdTimeline, Id_visible, HashName, OrgName, Path, Title, Date, Time, Description FROM Timeline WHERE IdTimeline="'.$postID.'"';
    return $sql;
}

function updatePost($postID, $idVisible, $hashName, $orgName, $path, $title, $date, $time, $description){
    $sql= 'UPDATE Timeline SET Id_visible="'.$idVisible.'", HashName="'.$hashName.'", Orgname="'.$orgname.'",
     Path="'.$path.'", Title="'.$title.'", Date="'.$date.'", Time="'.$time.'", 
     Description="'.$description.'" WHERE IdTimeline="'.$postID.'"';
     return $sql;
}

function selectPostIMG($id){
    $sql= 'SELECT HashName, Path FROM Timeline WHERE IdTimeline='.$id;
    return $sql;
}

function deletePost($id){
    $sql= 'DELETE FROM Timeline WHERE IdTimeline='.$id;
    return $sql;
}


/*
 * SIA Baujournal
 */
function getProjectDates($id){
    $sql= 'SELECT Fk_IdArchitect, Fk_IdBauherr, ProjectNumber, Title, Addressline1, Addressline2, ZIP, City,
            Country, Description, Picture FROM Project WHERE IdProject='.$id;
    return $sql;
}

function selectPostbyDate($projectID, $date){
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
    $sql= 'SELECT Filename, Comment FROM ProductsiteImages WHERE IdHTML='.$idHTML.' AND Active= 1';
    return $sql;
}


/*
 * Redaktionssystem
 */
function allContentOfIdHTML($id){
    $sql='SELECT Title, Content, InputType, Date, Time, Description FROM Productsite WHERE IdHTML='.$id;
    return $sql;
}

function saveToDB($title, $content){
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $sql= 'UPDATE Productsite SET Content ="'.$content.'", Date="'.$date.'", Time="'.$time.'" WHERE Title= "'.$title.'"';
    return $sql;
}

function allImagesOfIdHTML($id){
    $sql='SELECT Date, Time, Orgname, Path, ID, Filename, Comment, Active FROM ProductsiteImages WHERE IdHTML='.$id;
    return $sql;
}

function saveImageToDB($orgname, $comment, $file, $uploaddir, $select){
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $sql= "INSERT INTO ProductsiteImages(Date, Time, Orgname, Comment, Filename, Path, IdHTML) VALUES
                ('$date', '$time', '$orgname', '$comment', '$file', '$uploaddir', '$select')";
    return $sql;
}

function deleteImage($id){
    $sql= 'DELETE FROM ProductsiteImages WHERE ID='.$id;
    return $sql;
}

function selectFilename($id){
    $sql= 'SELECT Filename, Path FROM ProductsiteImages WHERE ID='.$id;
    return $sql;
}

function updateImageStatus($id){
    $sql='UPDATE ProductsiteImages SET Active= 1 WHERE ID= '.$id;
    return $sql;
}

function setAllActiveNull($idHTML){
    $sql= 'UPDATE ProductsiteImages SET Active= 0 WHERE IdHTML= '.$idHTML;
    return $sql;
}

/*
 * Login 
 */

//User aus Datenbank auslesen, anhand übereinstimmung und passwort
function selectUser($email, $pw) {
   $sql = "SELECT IdUser, Fk_IdUserType, Active FROM User WHERE email='" . $email . "' "
            . "AND password='" . hash('sha256', $pw) . "'";
    return $sql;
}
//nach session eröffnung userDaten anpassen 
function updateUser($date, $time, $sessionId, $browserTyp, $datensatz) {
   $sql = "UPDATE User SET LastLoginDate='$date',LastLoginTime='$time',"
              . " SessionId='$sessionId', Browser='$browserTyp'"
              . " WHERE id =$datensatz";
   return $sql;
}


/*
 * Kontaktformular - Plattform
 */

function getMailArch($projectID){
    $sql= "SELECT u.Email, p.ProjectNumber, p.Title FROM User as u JOIN Project as p on u.IdUser = p.Fk_IdArchitect WHERE p.IdProject=".$projectID;
    return $sql;
}

function getNameCust($projectID){
    $sql= "SELECT u.Email, u.Firstname, u.Lastname, p.ProjectNumber, p.Title FROM User as u JOIN Project as p on u.IdUser = p.Fk_IdBauherr WHERE p.IdProject=".$projectID;
    return $sql;
}


/*
 * Projektverwaltung
 */

//erstellt einen Bauherr in der Datenbank
function createBauherr($bhFn, $bhLn, $bhAddressline1, $bhAddressline2, $bhZIP, $bhCity, $bhCountry, $bhEmail, $bhPhNu, $bhMoNu, $pwHash) {
    $sql= "INSERT INTO user (Firstname, Lastname, Addressline1, Addressline2, ZIP, City, Country, Email, PhoneNumber, MobileNumber,
             Password, Fk_IdUserType, Active) VALUES
             ('$bhFn', '$bhLn', '$bhAddressline1', '$bhAddressline2', '$bhZIP', '$bhCity', '$bhCountry', '$bhEmail',
             '$bhPhNu', '$bhMoNu', '$pwHash', 3, 3)";
    return $sql;
}

//Holt ID eines spezifischen Bauherren
function getIdBauherr($pwHash) {
     $sql = 'SELECT IdUser FROM user WHERE Password="'.$pwHash.'"';
     return $sql;
}

//Erstellt das Projekt mit allen benötigten Daten
function createProject($id, $bhId, $projectNumb, $title, $addressline1, $addressline2, $zip, $city, $country, $description) {
     $sql = "INSERT INTO project (Fk_IdArchitect, Fk_IdBauherr, ProjectNumber, Title, Addressline1, Addressline2, ZIP,
             City, Country, Description)
             VALUES ('$id', '$bhId', '$projectNumb', '$title', '$addressline1' ,'$addressline2' ,'$zip' ,'$city' ,'$country' ,'$description')";
     return $sql;
}

//Holt ID eines spezifischen Projektes
function getIdProject($projectNumb, $bhId){
     $sql = "SELECT IdProject FROM project WHERE ProjectNumber ='$projectNumb'AND Fk_IdBauherr = '$bhId' " ;
     return $sql;
}

//Gibt alle Projekte eines Architekten
function getProjectsByArch($id) {
    $sql = 'SELECT p.IdProject, p.ProjectNumber, p.Title, p.Addressline1, p.Addressline2, p.ZIP, p.City,
        p.Country, p.Description, p.Picture, u.IdUser, u.Firstname, u.Lastname FROM project as p JOIN user
        as u on p.Fk_IdBauherr = u.IdUser WHERE Fk_IdArchitect = '.$id.' AND Storage IS NULL ';
    return $sql;
}

/*
 * Galerie - Plattform
 */
function saveIMG($idProject, $hashNameL, $hashNameS, $orgName, $path, $comment, $visible){
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $sql= "INSERT INTO Pictures(Fk_IdProject, HashNameL, HashNameS, Orgname, Path, Date, Time, Comment, Visibility) VALUES
                ('$idProject', '$hashNameL', '$hashNameS', '$orgName', '$path', '$date', '$time', '$comment', '$visible')";
    return $sql;
}

function showIMG($idProject, $visibility){
    $sql= "SELECT IdPicture, HashNameL, HashNameS, Comment, Date, Time FROM Pictures WHERE Fk_IdProject = '$idProject' AND Visibility= '$visibility' ORDER BY Date DESC, Time DESC";
    return $sql;
}

function showAllIMG($idProject){
    $sql= "SELECT IdPicture, HashNameL, HashNameS, Comment, Date, Time FROM Pictures WHERE Fk_IdProject = '$idProject' ORDER BY Date DESC, Time DESC";
    return $sql;
}

function deleteImgGallery($idIMG){
    $sql= 'Delete FROM Pictures WHERE IdPicture='.$idIMG;
    return $sql;
}

function getIMGPath($idIMG){
    $sql= 'SELECT HashNameL, HashNameS FROM Pictures WHERE IdPicture='.$idIMG;
    return $sql;
}


/*
 * ****** Events
 */
function newEvent($projectID, $date, $time, $title, $description, $location){
    $sql= "INSERT INTO Events (Fk_IdProject, Date, Time, Title, Description, Location) VALUES
            ('$projectID','$date','$time','$title','$description','$location')";
    return $sql;
}

function updateEvent($eventID, $date, $time, $title, $description, $location){
    $sql= "UPDATE Events SET Date='$date', Time='$time', Title='$title', Description='$description',"
            . "Location='$location' WHERE IdEvent= ".$eventID;
    return $sql;
}


function getAllEvents($projectID){
    $sql= 'SELECT IdEvent, Date, Time, Title, Description, Location FROM Events WHERE Fk_IdProject='.$projectID.' ORDER BY Date ASC, Time ASC';
    return $sql;
}

function selectEvent($eventID){
    $sql= 'SELECT Date, Time, Title, Description, Location FROM Events WHERE IdEvent='.$eventID;
    return $sql;
}

function deleteOld($projectID){
    $sql= 'DELETE FROM Events WHERE Fk_IdProject='.$projectID.' AND date < CURDATE()';
    return $sql;
}

function deleteEvent($eventID){
    $sql= 'DELETE FROM Events WHERE IdEvent='.$eventID;
    return $sql;
}


/*
 ***** Deadlines
 */
function newDeadline($projectID, $deadlineDate, $title, $description, $idCraftsman){
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $sql= "INSERT INTO Deadlines (Fk_IdProject, IdCraftsman, Date, Time, DeadlineDate, DeadlineTitle, DeadlineDescription) VALUES
            ('$projectID','$idCraftsman','$date','$time','$deadlineDate','$title','$description')";
    return $sql;
}

function getAllDeadlines($projectID){
    $sql= 'SELECT IdDeadlines, IdCraftsman, DeadlineDate, DeadlineTitle, DeadlineDescription FROM Deadlines WHERE Fk_IdProject='.$projectID.' ORDER BY DeadlineDate ASC';
    return $sql;
}

function selectDeadlines($id){
    $sql= 'SELECT IdCraftsman, DeadlineDate, DeadlineTitle, DeadlineDescription FROM Deadlines WHERE IdDeadlines='.$id;
    return $sql;
}

function updateDeadline($id, $deadlineDate, $title, $description, $idCraftsman){
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $sql= "UPDATE Deadlines SET IdCraftsman='$idCraftsman', Date='$date', Time='$time', DeadlineDate='$deadlineDate', DeadlineTitle='$title', "
            . "DeadlineDescription='$description' WHERE IdDeadlines=".$id;
    return $sql;
}

function deleteDeadline($id){
    $sql= 'DELETE FROM Deadlines WHERE IdDeadlines='.$id;
    return $sql;
}
