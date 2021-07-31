<?php

    /**
     * Categories => [ Manage | Edit | Update | Add | Insert | Delete | Statistics ]
     * 
     * short if conditions :
     * condition ? True : False
     */

     
     
    $do = '';

    if (isset($_GET['do'])) {
        $do = $_GET['do'];
    } else {
        $do = 'Manage';
    }

    // short if condition
    //  $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    
    // if the page is Main Page

        if ($do == 'Manage') {
            echo 'Welcom to Manage Page';
            echo '<a href="page.php?do=Add">Add New Category +</a>';
        } elseif ($do == 'Add') {
            echo 'Welcom to Add Page';
        } else {
            echo 'Error, There is no page with this name';
        }
 

