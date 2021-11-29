<!DOCTYPE html>
<!--
/* 
 * Copyright Filip Chudzicki 2019
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
require_once 'include/connect.php';
?>
<html lang="pl">
    <head>
        <title>Chudziccy</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
       
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>

        
        <script src="js/jquery-3.3.1.min.js" type="text/javascript" ></script>
        <script src="js/bootstrap.min.js" type="text/javascript" ></script>
        <!--<script src="js/jquery-ui.min.js" type="text/javascript"></script>-->
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
                       <a class="nav-link text-white" href="zajecia.php">Zajęcia NFM</a>
                   </li>
                   <li class="nav-item bg-primary shadow-sm">
                       <a class="nav-link text-white" href="#">Statystyki</a>
                   </li>
                   <li class="nav-item bg-primary shadow-sm">
                       <a class="nav-link text-white" href="warunki.php">INTELIGENTNY DOM</a>
                   </li>
                   <li class="nav-item bg-primary shadow-sm rounded-right">
                      <?php echo '<a class="nav-link text-white-50" href="include/logout.php">Witaj'. $_SESSION['username'].'[ Wyloguj się! ]</a>'; ?>
                   </li>
                </ul>
   
        </nav>

