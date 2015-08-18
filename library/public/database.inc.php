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

function selectPost($projectID){
    $sql= 'SELECT IdTimeline, HashName, Path, Title, Date, Time, Description FROM Timeline WHERE Fk_IdProject="'.$projectID.'" ORDER BY Date, Time DESC';
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


