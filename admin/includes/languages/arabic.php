<?php

    function lang( $phrase ) {
        static $lang = array (

            'Message' => 'أهلاً وسهلاً',
            'Admin' => 'محمد'
        );

        return $lang[$phrase];
    }
