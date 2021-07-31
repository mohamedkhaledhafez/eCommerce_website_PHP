<?php

    function lang( $phrase ) {
        static $lang = array (

            // Navbar Links

            'ADMIN_AREA'        => 'Admin Dashboard',
            'SECTIONS'          => 'Categories',
            'ITEMS'             => 'Items',
            'MEMBERS'           => 'Members',
            'COMMENTS'          => 'Comments',
            'STATISTICS'        => 'Statistics',
            'LOGS'              => 'Logs',
            ''                  => ''
        );

        return $lang[$phrase];
    }
