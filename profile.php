<?php 
    session_start();
    $pageTitle  = 'Profile';
    include 'init.php';
    if(isset($_SESSION['user'])) {

    $getUser = $con->prepare("SELECT * FROM users WHERE UserName = ?");

    $getUser->execute(array($sessionUser));

    $info = $getUser->fetch();

?>

<h1 class="text-center"><?php echo $_SESSION['user']; ?> Profile</h1>

<div class="informations block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                Name : <?php echo $info['UserName'] ?> <br>
                Full Name : <?php echo $info['FullName'] ?> <br>
                Email : <?php echo $info['Email'] ?> <br>
                Register Date : <?php echo $info['Date'] ?> <br>
                Favorite Categories : <br>
            </div>

        </div>
    </div>
</div>
<div class="ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Ads</div>
            <div class="panel-body">
                <div class="row">
                    <?php 
                        foreach (getItems('Member_ID' ,$info['UserID']) as $item) {
                            echo '<div class="col-sm-6 col-md-3">';
                                echo '<div class="thumbnail item-box">';
                                    echo '<span class="price">' . $item['Price'] . '</span>';
                                    echo '<img class="img-responsive" src="img.png" alt="item-image">';
                                    echo '<div class="caption">';
                                        echo '<h3>' . $item['Name'] . '</h3>';
                                        echo '<p>' . $item['Description'] . '</p>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        }
                    ?>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Latest Comments</div>
            <div class="panel-body">
                <?php 
                    // Select comments
                    $stmt = $con->prepare("SELECT comment FROM comments WHERE user_id = ?");
                    // Execute the statement 
                    $stmt->execute(array($info['UserID']));

                    // Assign this info to variable
                    $comments = $stmt->fetchAll();
                    // If ther is no comments 
                    if (! empty($comments)) {
                        foreach ($comments as $comment) {
                            echo '<p>' . $comment['comment'] . '</p>';
                        }
                    } else {
                        echo "There is no comments";
                    }
                ?>
            </div>

        </div>
    </div>
</div>

<?php

    } else {
        header('Location: login.php');

        exit();
    }


    include $templates . 'footer.php'; 

?>