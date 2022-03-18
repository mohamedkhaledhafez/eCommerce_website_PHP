<?php 
    ob_start();
    session_start();
    $pageTitle  = 'Show Items';
    include 'init.php';

    // Check if Get request itemid is numeric & get its integer value 
    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;  // intval => integer value

    // Select all data in database that depend on this userId 
    $stmt = $con->prepare("SELECT 
                                items.*,
                                categories.Name AS category_name,
                                users.UserName 
                            FROM 
                                items 
                            INNER JOIN
                                categories
                            ON 
                                categories.ID = items.Cat_ID
                            INNER JOIN
                                users
                            ON 
                                users.UserID = items.Member_ID
                            WHERE 
                                item_ID = ?
                            AND 
                                Approve = 1");

    // Execute query
    $stmt->execute(array($itemid));

    $count = $stmt->rowCount();

    // Check if this id is already exist in database
    if ($count > 0) {

    // Fetch the data
    $item = $stmt->fetch();
?>

<h1 class="text-center"><?php echo $item['Name']; ?></h1>

<div class="container">
    <div class="row">
        <div class="col-md-3">
            <img class="item-image img-responsive  center-block" src="./admin/uploads/avatars/<?php echo $item['avatar'] ?>" alt= "item-image">
        </div>
        <div class="col-md-9 item-info">
            <h2><?php echo $item['Name'] ?></h2>
            <p><?php echo $item['Description'] ?></p>
            <ul class="list-unstyled">
                <li>
                    <i class="fa fa-calendar fa-fw"></i>
                    <span>Added Date</span> : <?php echo $item['Add_Date'] ?>
                </li>
                <li>
                    <i class="fa fa-dollar fa-fw"></i>
                    <span>Price</span> : $<?php echo $item['Price'] ?>
                </li>
                <li>
                    <i class="fa fa-globe fa-fw"></i>
                    <span>Made in</span> : <?php echo $item['Made_in'] ?>
                </li>
                <li>
                    <i class="fa fa-tags fa-fw"></i>
                    <span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['category_name'] ?></a>
                </li>
                <li>
                    <i class="fa fa-user fa-fw"></i>
                    <span>Added by</span> : <a href="profile.php"><?php echo $item['UserName'] ?></a>
                </li>
                <li class="tags-items">
                    <i class="fa fa-user fa-fw"></i>
                    <span>Tags</span> : 
                    <?php
                        $allTags = explode(",", $item['tags']);
                        foreach ($allTags as $tag) {
                            $tag = str_replace(' ', '', $tag);
                            $lowerTag = strtolower($tag);
                            if (! empty($tag)) {
                                echo "<a href='tags.php?name={$lowerTag}'>" . $tag . '</a>';
                            }
                            
                        } 
                    
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <hr>
    <!-- Add Comment -->
    <?php  if(isset($_SESSION['user'])) { ?>
        <div class="row">
            <div class="offset-md-3">
                <div class="add-comment">
                <h5>Type a comment</h5>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' .$item['item_ID'] ?>" method="POST">
                    <textarea name="comment" id="" cols="30" rows="10" required></textarea>
                    <input type="submit" class="btn btn-primary" value="Comment">
                </form>
                <?php 
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                        $itemid  = $item['item_ID'];
                        $userid  = $_SESSION['user_id'];
                        
                        // Check if textarea is empty or not
                        if (! empty($comment)) {
                            $stmt = $con->prepare("INSERT INTO 
                                                    comments(comment, status, comment_date, item_id, user_id)
                                                    VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");
                            $stmt->execute(array(
                                'zcomment' => $comment,
                                'zitemid'  => $itemid,
                                'zuserid'  => $userid
                            ));

                            if ($stmt) {
                                echo '<div class="alert alert-success">Comment Added</div>';
                            }
                                                
                        } else {
                            echo '<div class="alert alert-danger">Comment must be not empty!</div>';
                        }
                    }
                ?> 
                </div>
            </div>
        </div>
    <?php } else {
        echo '<a href="login.php">Login</a> or <a href="login.php">register</a> to add comment';
    } ?>
    <hr>
    <?php
        // Select all users in database except the Admin
        $stmt = $con->prepare("SELECT 
                                comments.*, users.UserName AS Member
                            FROM 
                                comments
                            INNER JOIN
                                users
                            ON  
                                users.UserID = comments.user_id
                            WHERE
                                item_id = ?
                            AND 
                                status = 1
                            ORDER BY
                                c_id DESC");
        // Execute the statement 
        $stmt->execute(array($item['item_ID']));
    
        // Assign this info to variable
        $comments = $stmt->fetchAll();            
    ?>
    
        <?php
            foreach ($comments as $comment) { ?>
                <div class="comment-box">
                    <div class='row'>
                        <div class='col-sm-2 text-center'>
                            <img class="img-responsive img-thumbnail rounded-circle" src="man.png" alt="">
                            <?php echo $comment['Member'] ?>
                        </div>
                        <div class='col-sm-10'>
                            <p class="lead"><?php echo $comment['comment'] ?></p>
                        </div>
                    </div>
                </div>
        <?php } ?>   
        
</div>

<?php

    } else {
        echo '<div class="container">';
            echo '<div class="alert alert-danger">There is no such id OR this item is waiting for approval</div>';
        echo '</div>';
    }
    echo '<div class="row item-info container">';
        echo '<h2>Related Items</h2>';
        $itemsTag = getAllFrom('*', 'items', "WHERE tags like '%$tag%'", 'AND Approve = 1', 'item_ID');
        foreach ($itemsTag as $item) {
            echo '<div class="col-sm-6 col-md-3">';
                echo '<div class="thumbnail item-box">';
                    echo '<span class="price">$' . $item['Price'] . '</span>';
                    echo '<img class="item-image img-responsive" src="./admin/uploads/avatars/' . $item['avatar'] .'" alt="item-image">';
                    echo '<div class="caption">';
                        echo '<h3><a href="items.php?itemid='. $item['item_ID'] .'" target="_blank">' . $item['Name'] . '</a></h3>';
                        echo '<p>' . $item['Description'] . '</p>';
                        echo '<div class="date">' . $item['Add_Date'] . '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
    echo '</div>';

    include $templates . 'footer.php';
    ob_end_flush(); 
?>