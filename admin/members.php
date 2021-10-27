<?php

/*
============================================================
== Manage Members Page
== You can Add | Edit|  Delete Members from here
============================================================
*/
ob_start();
session_start();

$pageTitle = 'Members';

if (isset($_SESSION['Username'])) {
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page
    if ($do =='Manage') {  // Manage Members Page 

        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegisterStatus = 0';
        }
        // Select all users in database except the Admin
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupId != 1 $query ORDER BY UserID DESC");
        // Execute the statement 
        $stmt->execute();
    
        // Assign this info to variable
        $rows = $stmt->fetchAll();

        if (!empty($rows)) {
    
        ?>
            
        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registered Date</td>
                        <td>Control</td>
                    </tr>

                    <?php 
                        foreach($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['UserID'] . "</td>";
                            echo "<td>";
                                if (empty ($row['avatar'])) {
                                    echo "<img class='user_avatar' src='uploads/avatars/man.png' alt='' />";
                                } else {
                                    echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
                                }
                            
                            echo "</td>";
                            echo "<td>" . $row['UserName'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['FullName'] . "</td>";
                            echo "<td>" . $row['Date'] . "</td>";
                            echo "<td>
                                    <a href='members.php?do=Edit&userId=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                    <a href='members.php?do=Delete&userId=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                    if ($row['RegisterStatus'] == 0) {
                                        echo "<a href='members.php?do=Activate&userId=" .
                                         $row['UserID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Activate</a>";
                                    }
                            
                            echo "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
        </div>

        <?php  } else {
            echo "<div class='container'>";
                echo "<div class='empty-msg'>There is no members to show here ):</div>";
                echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
            echo "</div>";
        } ?>
        
    <?php 
    } elseif ($do == 'Add') { ///////////////////////////////////////////////////// Add Member Page ?>

            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                    <!--start Username Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class="form-control" autocomplete="off"  placeholder="Username To Login To Shop" required="required"/>
                        </div>
                    </div>
                    <!--End Username Field-->

                    <!--start Password Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="password" name="password" class="password form-control" autocomplete="new-password" placeholder="Password Must Be Complicated and Hard" required="required" />
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>
                    <!--End Password Field-->

                    <!--start Email Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" class="form-control" placeholder="Email Must Be Valid" required="required"/>
                        </div>
                    </div>
                    <!--End Email Field-->

                    <!--start FullName Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">FullName</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="fullname" class="form-control" placeholder="Full-Name That Apear In Your Profile Page" required="required"/>
                        </div>
                    </div>
                    <!--End FullName Field-->
                    
                    <!--start Profile Image Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">User Image</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="file" name="avatar" class="form-control"/>
                        </div>
                    </div>
                    <!--End FullName Field-->

                    <!--start Submit Field-->
                    <div class="form-group  form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Member" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!--End Submit Field-->
                </form>
            </div>

    <?php
    } elseif ($do == 'Insert') {  ////////////////////////////////////////////// Insert Member Page 
       
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'>Insert Member</h1>";
            echo "<div class='container'>";

            // Upload Variables

            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];

            // List of allowed file type to upload
            $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

            // Get avatar extensions
            $exp = explode('.', $avatarName);
            $avatarExtension = strtolower(end($exp));

            // Get variables from Form
            $user   = $_POST['username'];
            $pass   = $_POST['password'];
            $email  = $_POST['email'];
            $full   = $_POST['fullname'];

            $hashPassword = sha1($pass);

            // Validate the Form
            $formErrors = array();
            
            if (empty($user)) {
                $formErrors[] = 'Username can\'t be empty';
            }

            if (strlen($user) < 3) {
                $formErrors[] = 'Username can\'t be Less than <strong>3</strong> charachters';
            }

            if (strlen($user) > 20) {
                $formErrors[] = 'Username can\'t be More than <strong>20</strong> charachters';
            }

            if (empty($pass)) {
                $formErrors[] = 'Password can\'t be empty';
            }

            if (empty($full)) {
                $formErrors[] = 'Full name can\'t be empty';
            }

            if (empty($email)) {
                $formErrors[] = 'Email can\'t be empty';
            }

            if (! empty ($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
                $formErrors[] = 'This Extension Is Not <Strong>Allowed</Strong>';
            }
            
            if (empty($avatarName)) {
                $formErrors[] = 'Image can\'t be empty';
            }
            
            if ($avatarSize > 4194304) {
                $formErrors[] = 'Image can\'t be larger than <strong>4MB</strong>';
            }


            // Loop into errors array and Echo it
            foreach($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // Check if there is no error proceed the update operation            
            if (empty($formErrors)) {

                $avatar = rand(0, 10000) . '_' . $avatarName;

                move_uploaded_file($avatarTmp, "uploads/avatars/" . $avatar);
            
                // Check if user is exist in Database
                $check = checkItem("UserName", "users", $user);

                if ($check == 1) {

                    $theMsg = "<div class='alert alert-danger'>Sorry, This user is already exist in database</div>";
                    redirectHome($theMsg, 'back');
                } else {
                    // Insert user informations to database
                    $stmt = $con->prepare("INSERT INTO 
                                            users(UserName, Password, Email, FullName, RegisterStatus, Date, avatar)
                                            VALUES(:muser, :mpass, :mmail, :mfull, 1, now(), :mavatar) ");

                    $stmt->execute(array(
                        'muser' => $user,
                        'mpass' => $hashPassword,
                        'mmail' => $email,
                        'mfull' => $full,
                        'mavatar' => $avatar

                    ));

                    // Echo success Message
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record INSERTED</div>";
                    redirectHome($theMsg, 'back');
                }
            }
            
        } else {

            echo "<div class='container'>";

                $theMsg = '<div class="alert alert-danger">SORRY, YOU CANT EDIT THIS PAGE DIRECTLY WITHOUT POST METHOD</div>';
                redirectHome($theMsg);

            echo "</div>";
        }

        echo "</div>";


        } elseif ($do == 'Edit') {  ////////////////////////////////////////////////////// Edit Page 
    
        // Check if Get request UserId is numeric & get its integer value 
        $userid = isset($_GET['userId']) && is_numeric($_GET['userId']) ? intval($_GET['userId']) : 0;  // intval => integer value

        // Select all data in database that depend on this userId 
        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

        // Execute query
        $stmt->execute(array($userid));
        // Fetch the data
        $row = $stmt->fetch();
        // The row count that prove that there is a record in database with this userId
        $rowsCount = $stmt->rowCount();
    
        // If thre is such userId, Show the Form
        if ($rowsCount > 0) { ?>

            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <div class="row">
                    <form class="col-md-9 form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                        <!--start Username Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">Username</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="username" class="form-control" value="<?php echo $row['UserName'] ?>" autocomplete="off" required="required"/>
                            </div>
                        </div>
                        <!--End Username Field-->

                        <!--start Password Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
                                <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave it Blank if you dont want to change" />
                            </div>
                        </div>
                        <!--End Password Field-->

                        <!--start Email Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="email" name="email" value="<?php echo $row['Email'] ?>"  class="form-control" required="required"/>
                            </div>
                        </div>
                        <!--End Email Field-->

                        <!--start FullName Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">FullName</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="fullname" value="<?php echo $row['FullName'] ?>" class="form-control" />
                            </div>
                        </div>
                        <!--End FullName Field-->

                        <!--start Profile Image Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">User Image</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="file" name="avatar" value="<?php echo $row['avatar'] ?>" class="form-control"/>
                            </div>
                        </div>
                        <!--End FullName Field-->

                        <!--start Submit Field-->
                        <div class="form-group  form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                        <!--End Submit Field-->

                    </form>
                    <div class="col-md-3">
                        <?php 
                            if(! empty ($row['avatar'])) {
                                echo "<img class='user_avatar' src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
                            } else {
                                echo "<img class='user_avatar' src='uploads/avatars/man.png' alt='' />";
                            }
                        
                        ?>
                    </div>
                </div>
            </div>
    <?php 
        } else {
            echo "<div class='container'>";

                $theMsg = '<div class="alert alert-danger">There is no such ID</div>';

                redirectHome($theMsg);

            echo "</div>";
        }
        
    } elseif ($do == 'Update') {  ///////////////////////////////////////////////// Update Page 

        echo "<h1 class='text-center'>Update Member</h1>";
        echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Upload Variables

            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];

            // List of allowed file type to upload
            $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

            // Get avatar extensions
            $exp = explode('.', $avatarName);
            $avatarExtension = strtolower(end($exp));

                        
            // Get variables from Form
            $id     = $_POST['userid'];
            $user   = $_POST['username'];
            $email  = $_POST['email'];
            $full   = $_POST['fullname'];

             // Update Password
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            
            // Validate the Form
            $formErrors = array();
            
            if (empty($user)) {

                $formErrors[] = 'Username can\'t be empty';
            }

            if (strlen($user) < 3) {

                $formErrors[] = 'Username can\'t be Less than <strong>3</strong> charachters';
            }

            if (empty($full)) {

                $formErrors[] = 'Full name can\'t be empty';
            }

            if (empty($email)) {

                $formErrors[] = 'Email can\'t be empty';
            }

            // Loop into errors array and Echo it
            foreach($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // Check if there is no error proceed the update operation
            
            if (empty($formErrors)) {

                $avatar = rand(0, 10000) . '_' . $avatarName;

                move_uploaded_file($avatarTmp, "uploads/avatars/" . $avatar);
                
                $stmt2 = $con->prepare("SELECT 
                                            * 
                                        FROM 
                                            users
                                        WHERE
                                            UserName = ?
                                        AND
                                            UserID != ?");
                $stmt2->execute(array($user, $id));       
                
                $count = $stmt2->rowCount();

                if ($count == 1) {
                    $theMsg = "<div class='alert alert-danger'>Sorry, This user is already exist</div>";

                    redirectHome($theMsg, 'back');
                } else {
                    // Update the database with this informations
                    $stmt = $con->prepare("UPDATE users SET UserName = ?, Email = ?, FullName = ?, Password = ?, avatar = ? WHERE UserID = ?");
                    $stmt->execute(array($user, $email, $full, $pass, $avatar, $id));

                    // Echo success Message
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record UPDATED</div>';
                    redirectHome($theMsg, 'back');
                }
                
            }

        } else {

            $theMsg = '<div class="alert alert-danger">SORRY, YOU CANT EDIT THIS PAGE DIRECTLY WITHOUT POST METHOD</div>';

            redirectHome($theMsg);
        }

        echo "</div>";


    } elseif($do == 'Delete') {  /////////////////////////////////// Delete Members Page

        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";

            // Check if Get request UserId is numeric & get its integer value 
            $userid = isset($_GET['userId']) && is_numeric($_GET['userId']) ? intval($_GET['userId']) : 0;   // intval => integer value

            // Select all data depend on this userID
            $check = checkItem('userid', 'users', $userid);
            
            // If thre is such userId, Show the Form
            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM users WHERE UserID = :muser ");

                $stmt->bindParam(":muser", $userid);

                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record DELETED </div>';
                redirectHome($theMsg, 'back');
            } else {
                $theMsg = "<div class='alert alert-danger'>No such ID</div>";
                redirectHome($theMsg);
            }

        echo "</div>";
  

    } elseif( $do == 'Activate') { ///////////////////////////////////////////// Activate Members Page   

        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";

            // Check if Get request UserId is numeric & get its integer value 
            $userid = isset($_GET['userId']) && is_numeric($_GET['userId']) ? intval($_GET['userId']) : 0;   // intval => integer value

            // Select all data depend on this userID
            $check = checkItem('userid', 'users', $userid);
            
            // If thre is such userId, Show the Form
            if ($check > 0) {

                $stmt = $con->prepare("UPDATE users SET RegisterStatus = 1 WHERE UserID = ?");

                $stmt->execute(array($userid));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Activated </div>';
                redirectHome($theMsg);
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

