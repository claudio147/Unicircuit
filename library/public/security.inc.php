<?php

/*
 * Funktionsbibliothek mit Sicherheitsrelevanten funktionen
 * 
 */

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