<?php

/*
============================================================
== Template Page
============================================================
*/
ob_start();

session_start();

$pageTitle = '';

if (isset($_SESSION['Username'])) {
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page
    if ($do =='Manage') {  // Manage Members Page 
        
    } elseif ($do == 'Add') { // Add Member Page 
    
    } elseif ($do == 'Insert') {  ////////////////////////////////////////////// Insert Member Page
    
    } elseif ($do == 'Edit') {  ////////////////////////////////////////////////////// Edit Page 

    } elseif ($do == 'Update') {  /////////////////////////////////// Update Page 

    } elseif($do == 'Delete') {  /////////////////////////////////// Delete Members Page

    } elseif( $do == 'Activate') { ///////////////////////////////////////////// Activate Members Page   

    }
    include $templates . 'footer.php';

} else {

        header('Location: index.php');
        exit();
    }

ob_end_flush();