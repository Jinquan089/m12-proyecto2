<?php

   $dbserver = "localhost";
   $dbuser = "zorrito";
   $dbpwd = "QWEqwe123";
   $dbbasedatos = "db_restaurante";


   try {
      $conn = @mysqli_connect($dbserver, $dbuser, $dbpwd, $dbbasedatos);

   } catch (Exception $e) {
      echo ("Error: " . $e->getMessage());
      die();
   }