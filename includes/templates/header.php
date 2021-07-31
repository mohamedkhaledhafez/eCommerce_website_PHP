<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echoTitle(); ?> </title>
        <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css" /> 
        <link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css" /> 
        <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css" /> 
        <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" /> 
        <link rel="stylesheet" href="<?php echo $css ?>frontend.css" /> 
    </head>
    <body>
    <div class="upper-bar">
        <div class="container">
            <?php 
                if (isset($_SESSION['user'])) {
                    echo $sessionUser . ' ';
                    echo '<a href="profile.php">Profile</a>';
                    echo ' - <a href="logout.php">Logout</a>';

                    $userStatus = checkUserStatus($sessionUser);
                    
                    if ($userStatus == 1) {
                        // User is not Active
                    }
                } else {
            ?>
            <a href="login.php">
                <span>Login/SignUp</span>
            </a>
            <?php } ?>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark ">
        <div class="container">
            <button class="navbar-toggler" class="navbar-toggler" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#app-nav" 
                    aria-controls="app-nav" 
                    aria-expanded="false" 
                    aria-label="Toggle navigation" >
            <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="index.php">Home Page</a>

            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="nav navbar-nav navbar-right">
                    <?php 
                            $categories = getCat();

                            foreach ($categories as $cat) {
                                echo '<li> <a href="categories.php?pageid=' . $cat['ID'] . '&pagename=' . str_replace(' ', '-', $cat['Name']) . '">' . $cat['Name'] . '</a></li>';
                            }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    </body>
</html>