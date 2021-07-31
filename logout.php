<?php

    // Start the session
    session_start();

    // unset the data of the session
    session_unset();

    // destroy the session
    session_destroy();

    header('Location: index.php');

    exit();