<?php
require_once 'database.inc.php';

$link=  connectDB();
$sql= allContentProductsite();

$result= mysqli_query($link, $sql)
    or die('Error: '.mysqli_error($link));
$data=array();

while($row = mysqli_fetch_array($result)){
    $data[$row['Title']] = $row['Content'];
}




/*
 * Setzt alle Inhalte aus DB in die variablen, 
 * welche auf der index.php verwendet werden.
 */

//Landing page
$LP_company= $data['LP_company'];
$LP_productName= $data['LP_productName'];
$LP_slogan= $data['LP_slogan'];

//Navigation
$NA_productName= $data['NA_productName'];
$NA_menu1= $data['NA_menu1'];
$NA_menu2= $data['NA_menu2'];
$NA_menu3= $data['NA_menu3'];
$NA_menu4= $data['NA_menu4'];
$NA_menu5= $data['NA_menu5'];
$NA_menu6= $data['NA_menu6'];
$NA_disturber= $data['NA_disturber'];

//Section1: Animation
$AN_title= $data['AN_title'];
$AN_subtitle= $data['AN_subtitle'];

//Section2: Module
$MD_title= $data['MD_title'];
$MD_subtitle= $data['MD_subtitle'];
$MD_1_title= $data['MD_1_title'];
$MD_1_description= $data['MD_1_description'];
$MD_2_title= $data['MD_2_title'];
$MD_2_description= $data['MD_2_description'];
$MD_3_title= $data['MD_3_title'];
$MD_3_description= $data['MD_3_description'];
$MD_4_title= $data['MD_4_title'];
$MD_4_description= $data['MD_4_description'];
$MD_5_title= $data['MD_5_title'];
$MD_5_description= $data['MD_5_description'];
$MD_6_title= $data['MD_6_title'];
$MD_6_description= $data['MD_6_description'];
$MD_7_title= $data['MD_7_title'];
$MD_7_description= $data['MD_7_description'];
$MD_8_title= $data['MD_8_title'];
$MD_8_description= $data['MD_8_description'];

//Section3: Galerie
$GA_title= $data['GA_title'];
$GA_subtitle= $data['GA_subtitle'];

//Section4: Unterbruch (Call to Action)
$CA_title= $data['CA_title'];
$CA_subtitle= $data['CA_subtitle'];
$CA_btn= $data['CA_btn'];

//Section5: Preise
$PR_title= $data['PR_title'];
$PR_subtitle= $data['PR_subtitle'];
$PR_L_title= $data['PR_L_title'];
$PR_L_subtitle= $data['PR_L_subtitle'];
$PR_L_content1= $data['PR_L_content1'];
$PR_L_content2= $data['PR_L_content2'];
$PR_L_content3= $data['PR_L_content3'];
$PR_L_content4= $data['PR_L_content4'];
$PR_L_content5= $data['PR_L_content5'];
$PR_L_btn= $data['PR_L_btn'];
$PR_M_title= $data['PR_M_title'];
$PR_M_subtitle= $data['PR_M_subtitle'];
$PR_M_content1= $data['PR_M_content1'];
$PR_M_content2= $data['PR_M_content2'];
$PR_M_content3= $data['PR_M_content3'];
$PR_M_content4= $data['PR_M_content4'];
$PR_M_content5= $data['PR_M_content5'];
$PR_M_btn= $data['PR_M_btn'];
$PR_R_title= $data['PR_R_title'];
$PR_R_subtitle= $data['PR_R_subtitle'];
$PR_R_content1= $data['PR_R_content1'];
$PR_R_content2= $data['PR_R_content2'];
$PR_R_content3= $data['PR_R_content3'];
$PR_R_content4= $data['PR_R_content4'];
$PR_R_content5= $data['PR_R_content5'];
$PR_R_btn= $data['PR_R_btn'];

//Section6: Über Uns
$AU_title= $data['AU_title'];
$AU_text= $data['AU_text'];
$AU_personLeftName= $data['AU_personLeftName'];
$AU_personLeftFunction= $data['AU_personLeftFunction'];
$AU_personRightName= $data['AU_personRightName'];
$AU_personRightFunction= $data['AU_personRightFunction'];

//Section7: Kontakt
$CO_title= $data['CO_title'];
$CO_subtitle= $data['CO_subtitle'];

//Footer
$FO_1_title= $data['FO_1_title'];
$FO_1_contentLine1= $data['FO_1_contentLine1'];
$FO_1_contentLine2= $data['FO_1_contentLine2'];
$FO_1_contentLine3= $data['FO_1_contentLine3'];
$FO_2_title= $data['FO_2_title'];
$FO_2_contentLine1= $data['FO_2_contentLine1'];
$FO_2_contentLine2= $data['FO_2_contentLine2'];
$FO_2_contentLine3= $data['FO_2_contentLine3'];
$FO_copyright= $data['FO_copyright'];

//Impressum
$IP_content= $data['IP_content'];

//AGB
$AG_title= $data['AG_title'];
$AG_subtitle= $data['AG_subtitle'];
$AG_introductionTitle= $data['AG_introductionTitle'];
$AG_introductionSubtitle= $data['AG_introductionSubtitle'];
$AG_content= $data['AG_content'];