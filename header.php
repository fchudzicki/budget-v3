<!DOCTYPE html>
<!--
/* 
 * Copyright Filip Chudzicki 2021
 */
-->
<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
include 'include/functions.php'; 
$mysqli = dbconnect();
?>
<html lang="pl">
    <head>
        <title>Chudziccy</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        <!-- <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/> -->
        <link href="css/style.css" rel="stylesheet" type="text/css"/>

        
        <!-- <script src="js/jquery-3.3.1.min.js" type="text/javascript" ></script> -->
       
        <script  src="https://code.jquery.com/jquery-3.6.0.min.js"  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="  crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
        <!-- <script src="js/bootstrap.min.js" type="text/javascript" ></script> -->
        <!--<script src="js/jquery-ui.min.js" type="text/javascript"></script>-->
        <script src="https://use.fontawesome.com/69dd2db930.js"></script>
        <script src="js/script.js" type="text/javascript"></script>


    </head>
    
    <body>
         <div>  </div>
        <nav id="nav"><h2 class="invisible"> Menu główne</h2>
           
              	
            <ul id="menu" class="nav nav-tabs justify-content-center shadow-sm">
                   <li class="nav-item bg-primary rounded-left shadow-sm">
                       <a class="nav-link  text-white" href="oplaty.php">Opłaty</a>
                   </li>
                   <li class="nav-item bg-primary shadow-sm">
                       <a class="nav-link text-white" href="wierzyciele.php">Wierzyciele</a>
                   </li>
                   <li class="nav-item bg-primary shadow-sm">
                       <a class="nav-link text-white" href="wydatki.php">Wydatki</a>
                   </li>
                   <li class="nav-item bg-primary shadow-sm">
                       <a class="nav-link text-white" href="exptype.php">Edycja Typów wydatków</a>
                   </li>
                   <li class="nav-item bg-primary shadow-sm">
                       <a class="nav-link text-white" href="zajecia.php">Zajęcia NFM</a>
                   </li>
                   <li class="nav-item bg-primary shadow-sm">
                       <a class="nav-link text-white" href="#">Statystyki</a>
                   </li>

                   <li class="nav-item bg-primary shadow-sm rounded-right">
                      <?php echo '<a class="nav-link text-white-50" href="include/logout.php">Witaj'. $_SESSION['username'].'[ Wyloguj się! ]</a>'; ?>
                   </li>
                </ul>
   
        </nav>

