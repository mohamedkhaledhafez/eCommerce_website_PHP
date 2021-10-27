<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echoTitle(); ?> </title>
        <link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css" /> 
        <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css" /> 
        <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" /> 
        <link rel="stylesheet" href="<?php echo $css ?>frontend.css" /> 
        <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css" /> 
    </head>
    <body>
    <div class="upper-bar">
        <div class="container">
            <?php
                $getUser = $con->prepare("SELECT * FROM users WHERE UserName = ?");
                $getUser->execute(array($sessionUser));
                $info = $getUser->fetch();

                if (isset($_SESSION['user'])) { 
                    if (! empty($info['avatar'])) {
                        echo "<img class='rounded-circle  my-image' src='admin/uploads/avatars/" . $info['avatar'] . "' alt='' /> ";
                    } else {
                        echo "<img class='rounded-circle  my-image' src='admin/uploads/avatars/man.png' alt='' />";
                    }
                    echo $sessionUser;
                    echo '<div class="upper-links">';
                        echo '<a href="profile.php"> Profile | </a>';
                        echo '<a href="newad.php"> New Item | </a>';
                        echo '<a href="profile.php#ads"> My Items - </a>';
                        echo '<a href="logout.php"> Logout</a>';
                    echo '</div>';

                    $userStatus = checkUserStatus($sessionUser);

                    if ($userStatus == 1) {

                    }                    

                } else { 
            ?>
            <a href="login.php">
                <span class="login-out">Login | SignUp</span>
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
                <ul class="nav navbar-nav navbar">
                    <?php 
                        $categories = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "ASC");

                        foreach ($categories as $cat) {
                            echo 
                                '<li>
                                    <a href="categories.php?pageid=' . $cat['ID'] . '">' . $cat['Name'] . '</a>
                                </li>';
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    </body>
</html>