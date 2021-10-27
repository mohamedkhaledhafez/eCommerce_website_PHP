<?php 
    ob_start();
    session_start();
    $pageTitle  = 'Profile';
    include 'init.php';
    if(isset($_SESSION['user'])) {
    $getUser = $con->prepare("SELECT * FROM users WHERE UserName = ?");
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();
    $userid = $info['UserID']; 

?>

<h1 class="text-center"><?php echo $_SESSION['user']; ?> Profile</h1>

<div class="informations block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-unlock-alt fa-fw"></i>
                        <span>Login Name</span> : <?php echo $info['UserName'] ?>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Full Name</span> : <?php echo $info['FullName'] ?>
                    </li>
                    <li>
                        <i class="fa fa-envelope-o fa-fw"></i>
                        <span>Email</span> : <?php echo $info['Email'] ?>
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Register Date</span> : <?php echo $info['Date'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Fav Categories</span> :
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>User Image</span> : 
                        <?php 
                            if (! empty($info['avatar'])) {
                                echo "<img class='user_avatar' src='admin/uploads/avatars/" . $info['avatar'] . "' alt='' />"; 
                            } else {
                                echo "<img class='user_avatar' src='man.png' alt='' />";
                            }
                        ?>
                    </li>
                </ul>
                <a href="" class="btn btn-success">Edit Information</a>
            </div>

        </div>
    </div>
</div>
<div id="ads" class="ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Items</div>
            <div class="panel-body">
            <?php
                $myItems = getAllFrom("*", "items", "WHERE Member_ID = $userid", "", "item_ID");
                if(! empty($myItems)) {
                    echo "<div class='row'>" ;
                    foreach ($myItems as $item) {
                        echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box">';
                                if ($item['Approve'] == 0) {
                                    echo "<span class='approve-status'>Waiting Approval</span>";
                                }
                                echo '<span class="price">$' . $item['Price'] . '</span>';
                                echo '<img class="img-responsive img-thumbnail" src="man.png" alt="item-image">';
                                echo '<div class="caption">';
                                    echo '<h3><a href="items.php?itemid='. $item['item_ID'] .'" target="_blank">' . $item['Name'] . '</a></h3>';
                                    echo '<p>' . $item['Description'] . '</p>';
                                    echo '<div class="date">' . $item['Add_Date'] . '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                    echo "</div>";
                } else {
                    echo "There is no advertises to show, Create <a href='newad.php'>New Advertise</a>";

                }
            ?>
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
                    $myComments = getAllFrom("comment", "comments", "WHERE user_id = $userid", "", "c_id");

                    // If ther is no comments 
                    if (! empty($myComments)) {
                        foreach ($myComments as $comment) {
                            echo '<p>' . $comment['comment'] . '</p>';
                        }
                    } else {
                        echo "There is no comments to show";
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
    ob_end_flush();
?>