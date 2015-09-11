<?php

/*
 * Funktionsbibliothek mit Sicherheitsrelevanten funktionen
 * 
 */

//Funktion generiert ein Passwort
function generatePassword(){

   
        $alpha = "abcdefghikmnopqrstuvqxyz";
       
        $alpha .= "23456789";
       
        $alpha .= "ABCDEFGHKLMNOPQRSTUVWXYZ";
       
        $alpha .= "!$%&/()=";

        srand ((double)microtime()*1000000);
       
        for($index = 0; $index < 7; $index++)
        {
                   $password .= substr($alpha,(rand()%(strlen ($alpha))), 1);
        }
        return $password;
   
}

//Funktion verkleinert Bilder und verschiebt diese
function resizeImage ($filepath_old, $filepath_new, $image_dimension, $scale_mode = 0){ 
  if (!(file_exists($filepath_old)) || file_exists($filepath_new)) return false; 

  $image_attributes = getimagesize($filepath_old); 
  $image_width_old = $image_attributes[0]; 
  $image_height_old = $image_attributes[1]; 
  $image_filetype = $image_attributes[2]; 

  if ($image_width_old <= 0 || $image_height_old <= 0){
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
 function checkImageType($uploadfile){
     $file= pathinfo($uploadfile, PATHINFO_EXTENSION);
     
    if($file != "jpg" && $file != "png" && $file != "jpeg"
        && $file != "gif" && $file != "JPG" && $file != "JPEG"
        && $file != "PNG" && $file != "GIF"){
        return false;
    }else{
        return true;
    }
 }