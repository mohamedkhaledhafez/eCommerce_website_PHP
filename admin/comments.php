<?php

/*
============================================================
== Manage Comments Page
== You can Add | Edit|  Delete Members from here
============================================================
*/


session_start();

$pageTitle = 'Comments';

if (isset($_SESSION['Username'])) {
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page
    if ($do =='Manage') {  // Manage Members Page 

        // Select all users in database except the Admin
        $stmt = $con->prepare("SELECT 
                                    comments.*, items.Name AS Item_Name, users.UserName AS Member
                                FROM 
                                    comments
                                INNER JOIN 
                                    items
                                ON
                                    items.item_ID = comments.item_id
                                INNER JOIN
                                    users
                                ON  
                                    users.UserID = comments.user_id
                                ORDER BY
                                    c_id DESC");
        // Execute the statement 
        $stmt->execute();
    
        // Assign this info to variable
        $comments = $stmt->fetchAll();

        if (!empty($comments)) {
    
        ?>
            
        <h1 class="text-center">Manage Comments</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>ID</td>
                        <td>Comment</td>
                        <td>Item Name</td>
                        <td>UserName</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>

                    <?php 
                        foreach($comments as $comment) {
                            echo "<tr>";
                            echo "<td>" . $comment['c_id']          . "</td>";
                            echo "<td>" . $comment['comment']       . "</td>";
                            echo "<td>" . $comment['Item_Name']       . "</td>";
                            echo "<td>" . $comment['Member']       . "</td>";
                            echo "<td>" . $comment['comment_date']  . "</td>";
                            echo "<td>
                                    <a href='comments.php?do=Edit&commid=" . $comment['c_id'] . 
                                    "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                    <a href='comments.php?do=Delete&commid=" . $comment['c_id'] . 
                                    "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                    if ($comment['status'] == 0) {
                                        echo "<a href='comments.php?do=Approve&commid=" .
                                         $comment['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                                    }
                            
                            echo "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
        </div>

        <?php  } else {
            echo "<div class='container'>";
                echo "<div class='empty-msg'>There is no comments to show here ):</div>";
            echo "</div>";
        } ?>
        
    <?php 

    } elseif ($do == 'Edit') {  ////////////////////////////////////////////////////// Edit Page 
    
        // Check if Get request commid is numeric & get its integer value 
        $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;  // intval => integer value

        // Select all data in database that depend on this commid 
        $stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");

        // Execute query
        $stmt->execute(array($commid));
        // Fetch the data
        $comment = $stmt->fetch();
        // The row count that prove that there is a record in database with this commid
        $count = $stmt->rowCount();
    
        // If thre is such userId, Show the Form
        if ($count > 0) { ?>

            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="commid" value="<?php echo $commid ?>">
                    <!--start Comment Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-6">
                            <textarea name="comment" class="form-control" cols="30" rows="10"><?php echo $comment['comment'] ?></textarea>
                        </div>
                    </div>
                    <!--End Comment Field-->

                    <!--start Submit Field-->
                    <div class="form-group  form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!--End Submit Field-->

                </form>
            </div>
    <?php 
        } else {
            echo "<div class='container'>";

                $theMsg = '<div class="alert alert-danger">There is no such ID</div>';

                redirectHome($theMsg);

            echo "</div>";
        }
        
    } elseif ($do == 'Update') {  /////////////////////////////////// Update Page 

        echo "<h1 class='text-center'>Update Comment</h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get variables from Form
            $commid   = $_POST['commid'];
            $comment  = $_POST['comment'];

            // Update the database with this informations
            $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
            $stmt->execute(array($comment, $commid));

            // Echo success Message
            $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record UPDATED</div>';
            redirectHome($theMsg, 'back');

        } else {

            $theMsg = '<div class="alert alert-danger">SORRY, YOU CANT EDIT THIS PAGE DIRECTLY WITHOUT POST METHOD</div>';

            redirectHome($theMsg);
        }

        echo "</div>";


    } elseif($do == 'Delete') {  /////////////////////////////////// Delete Members Page

        echo "<h1 class='text-center'>Delete Comment</h1>";
        echo "<div class='container'>";

            // Check if Get request commid is numeric & get its integer value 
            $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;   // intval => integer value

            // Select all data depend on this commid
            $check = checkItem('c_id', 'comments', $commid);
            
            // If thre is such commid, Show the Form
            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :mid ");

                $stmt->bindParam(":mid", $commid);

                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record DELETED </div>';
                redirectHome($theMsg, 'back');
            } else {
                $theMsg = "<div class='alert alert-danger'>No such ID</div>";
                redirectHome($theMsg);
            }

        echo "</div>";
  

    } elseif( $do == 'Approve') { ///////////////////////////////////////////// Approve Members Page   

        echo "<h1 class='text-center'>Approve Comment</h1>";
        echo "<div class='container'>";

            // Check if Get request commid is numeric & get its integer value 
            $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;   // intval => integer value

            // Select all data depend on this userID
            $check = checkItem('c_id', 'comments', $commid);
            
            // If thre is such userId, Show the Form
            if ($check > 0) {

                $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");

                $stmt->execute(array($commid));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Comment Approved </div>';
                redirectHome($theMsg, 'back');
            } else {
                $theMsg = "<div class='alert alert-danger'>This ID is Not Exist</div>";
                redirectHome($theMsg);
            }

          echo "</div>";    
    }

    include $templates . 'footer.php';

} else {

    header('Location: index.php');

    exit();
}

