<?php

    //Error Reporting 
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    include 'admin/connect.php';

    $sessionUser = '';
    
    if (isset($_SESSION['user'])) {
        $sessionUser = $_SESSION['user'];
    }


    // Routes :

    $templates = 'includes/templates/'; // Templates directory
    $lang      = 'includes/languages/'; // languages directory
    $func      = 'includes/functions/';  // functions directory 
    $css       = 'layout/css/';         // Css directory
    $js        = 'layout/js/';          // js directory
 
    // include the important files :
    include $func . 'functions.php';
    include $lang . 'english.php';
    include $templates . 'header.php'; 

  
    
