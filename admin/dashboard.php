<?php 

    ob_start();   // Output Buffering Start

    session_start();

    if (isset($_SESSION['Username'])) {
        
        $pageTitle  = 'Dashboard';

        include 'init.php';
        /************************************* Start Dashboard Page *************************************************/
        $NumberOfLatestUsers = 4; // Number Of Latest Users/Member registered 
        $latestUser = getLatest("*", "users", "UserID", $NumberOfLatestUsers); // Latest Users Array 


        $NumberOfLatestItems = 4; // Number Of Latest Items Added
        $latestItem = getLatest("*", "items", "item_ID", $NumberOfLatestItems);  // Latest Items Array

        $NumberOfLatestComments = 4; // Number Of Latest Comments Added

        ?>

        <div class="container home-stats text-center">
            <h1 class="text-center">Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="statistics st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Members
                            <span>
                                <a href="members.php" target="_blank"><?php echo countItems('UserID', 'users'); ?></a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="statistics st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Members
                            <span><a href="members.php?do=Manage&page=Pending" target="_blank">
                                <?php echo checkItem('RegisterStatus', 'users', 0) ?></a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="statistics st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span>
                                <a href="items.php" target="_blank"><?php echo countItems('item_ID', 'items'); ?></a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="statistics st-comments">
                        <i class="fa fa-comments"></i>
                        <div class="info">
                            Total Comments
                            <span>
                                <a href="comments.php" target="_blank"><?php echo countItems('c_id', 'comments'); ?></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container latest">
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i>Latest <?php echo $NumberOfLatestUsers ?> Registered Members
                            <span class="pull-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-users">
                                <?php
                                    if (!empty($latestUser)) {
                                        foreach ($latestUser as $user) {                     
                                            echo '<li>';
                                                echo $user['UserName']; 
                                                echo '<a href="members.php?do=Edit&userId=' . $user['UserID'] .'">';
                                                    echo '<span class="btn btn-success pull-right">';
                                                        echo '<i class="fa fa-edit"></i> Edit';
                                                        if ($user['RegisterStatus'] == 0) {
                                                            echo "<a href='members.php?do=Activate&userId=" . $user['UserID'] .
                                                            "' class='btn btn-info pull-right activate'><i class='fa fa-check'></i>Activate</a>";
                                                        } 
                                                    echo '</span>';
                                                echo '</a>'; 
                                            echo '</li>';
                                        }
                                    } else {
                                        echo "There is no users to show here";
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i>Latest <?php echo $NumberOfLatestItems ?> Items
                            <span class="pull-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled latest-items">
                                <?php
                                    if (!empty($latestItem)) {
                                        foreach ($latestItem as $item) {
                                            echo '<li>';
                                                echo $item['Name']; 
                                                echo '<a href="items.php?do=Edit&itemid=' . $item['item_ID'] .'">';
                                                    echo '<span class="btn btn-success pull-right">';
                                                        echo '<i class="fa fa-edit"></i> Edit';
                                                        if ($item['Approve'] == 0) {
                                                            echo "<a href='items.php?do=Approve&itemid=" . $item['item_ID'] .
                                                            "' class='btn btn-info pull-right activate'><i class='fa fa-check'></i>Approve</a>";
                                                        } 
                                                    echo '</span>';
                                                echo '</a>'; 
                                            echo '</li>';
                                        }
                                    } else {
                                        echo "There is no items to show here";
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Start Latest Comments -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments-o"></i>Latest <?php echo $NumberOfLatestComments ?> Comments
                            <span class="pull-right toggle-info">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                                $stmt = $con->prepare("SELECT 
                                comments.*, users.UserName AS Member
                                                        FROM 
                                                            comments
                                                        
                                                        INNER JOIN
                                                            users
                                                        ON  
                                                            users.UserID = comments.user_id
                                                        ORDER BY
                                                            c_id DESC
                                                        LIMIT 
                                                            $NumberOfLatestComments");
                                // Execute the statement 
                                $stmt->execute();
                                // Assign this info to variable
                                $comments = $stmt->fetchAll();

                                if (!empty($comments)) {    
                                    foreach ($comments as $comment) {
                                        echo "<div class='comment-box'>";
                                            echo '<span class="member-name"> <a href="members.php?do=Edit&userId=' . $comment['user_id'] . '">
                                                                               ' . $comment['Member'] . '</a></span>';
                                            echo "<p class='member-comment'>" . $comment['comment'] . "</p>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "There is no comments to show here";
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Latest Comments -->

        </div>

        <?php

        /************************************* End Dashboard Page *************************************************/

        include $templates . 'footer.php';
    } else {

        header('Location: index.php');
        exit();
    }

    ob_end_flush();

?>