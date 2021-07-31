<?php

    include 'connect.php';

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

    // include the navbar in all pages except the one with variable : $noNavbar 
    if (!isset($noNavbar)) {
        include $templates . 'navbar.php';
    }
    
