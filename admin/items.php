<?php
//use UI\Control;

/*
============================================================
== Items Page
============================================================
*/
ob_start();

session_start();

$pageTitle = 'Items';

if (isset($_SESSION['Username'])) {
    
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage Page
    if ($do =='Manage') {  // Manage Members Page 

        $stmt = $con->prepare("SELECT 
                                    items.*, categories.Name AS category_name, users.UserName
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
                                ORDER BY
                                    item_ID DESC");
        // Execute the statement 
        $stmt->execute();
    
        // Assign this info to variable
        $items = $stmt->fetchAll();
        
        if (!empty($items)) {
        
        ?>
            
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-items text-center table table-bordered">
                    <tr>
                        <td>ID</td>
                        <td>Item Name</td>
                        <td>Avatar</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>UserName</td>
                        <td>Control</td>
                    </tr>

                    <?php 
                        foreach($items as $item) {
                            echo "<tr>";
                            echo "<td>" . $item['item_ID'] . "</td>";
                            echo "<td>" . $item['Name'] . "</td>";
                            echo "<td>";
                                if (empty ($item['avatar'])) {
                                    echo "<img class='user_avatar' src='uploads/avatars/man.png' alt='' />";
                                } else {
                                    echo "<img src='uploads/avatars/" . $item['avatar'] . "' alt='' />";
                                }
                            echo "</td>";                            
                            echo "<td>" . $item['Description'] . "</td>";
                            echo "<td>" . $item['Price'] . "</td>";
                            echo "<td>" . $item['Add_Date'] . "</td>";
                            echo "<td>" . $item['category_name'] . "</td>";
                            echo "<td>" . $item['UserName'] . "</td>";
                            echo "<td class='control-tr'>
                                    <a href='items.php?do=Edit&itemid=" . $item['item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                    <a href='items.php?do=Delete&itemid=" . $item['item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                    if ($item['Approve'] == 0) {
                                        echo "<a href='items.php?do=Approve&itemid=" .
                                        $item['item_ID'] . "' class='btn btn-info activate'><i class='fa fa-check'></i> Approve</a>";
                                    }
                            echo "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>
        </div>

        <?php  } else {
            echo "<div class='container'>";
                echo "<div class='empty-msg'>There is no items to show here ):</div>";
                echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Item</a>';
            echo "</div>";
        } ?>
        
    <?php

    } elseif ($do == 'Add') { //////////////////////////////////////// Add Item Page ?> 
        <h1 class="text-center">Add New Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!--start Name Field-->
                <div class="form-group  form-group-lg">
                    <label class="col-sm-2 control-label">Item Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Name of The Item"/>
                    </div>
                </div>
                <!--End Name Field-->

                <!--start Description Field-->
                <div class="form-group  form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="form-control" placeholder="Description of The Item"/>
                    </div>
                </div>
                <!--End Description Field-->

                <!--start Price Field-->
                <div class="form-group  form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="price" class="form-control" placeholder="Price of The Item"/>
                    </div>
                </div>
                <!--End Price Field-->

                <!--start Country of The Item Field-->
                <div class="form-group  form-group-lg">
                    <label class="col-sm-2 control-label">Country of Made</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="country" class="form-control" placeholder="Country of Made of The Item"/>
                    </div>
                </div>
                <!--End Country of The Item Field-->

                <!--start Status of The Item Field-->
                <div class="form-group  form-group-lg">
                    <label class="col-sm-2 control-label">Status of The Item</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Old</option>
                        </select>
                    </div>
                </div>
                <!--End Status of The Item Field-->

                <!--start Members Field--> <!-- Members Field : This is for the members who add the items in item list -->
                <div class="form-group  form-group-lg">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <option value="0">...</option>
                            <?php 
                                $allMembers = getAllFrom("*", "users", "", "", "UserID");
                                foreach ($allMembers as $user) {
                                    echo "<option value='" . $user['UserID'] . "'>" . $user['UserName']. "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!--End Members Field-->

                <!--start Categories Field--> <!-- Categories Field : This is for the Category which the item is Added From -->
                <div class="form-group  form-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <option value="0">...</option>
                            <?php
                                $allCats = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID"); 
                                foreach ($allCats as $cat) {
                                    echo "<option value='" . $cat['ID'] . "'>" . $cat['Name']. "</option>";
                                    $childCats = getAllFrom("*", "categories", "WHERE Parent = {$cat['ID']}", "", "ID");
                                    foreach ($childCats as $child) {
                                        echo "<option value='" . $child['ID'] . "'>- " . $child['Name']. "</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!--End Categories Field-->

                <!--start Tags Field-->
                <div class="form-group  form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input 
                            type="text"
                            name="tags" 
                            class="form-control" 
                            placeholder="Seperate tags with comma ( , )"/>
                    </div>
                </div>
                <!--End Tags Field-->

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
                        <input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
                    </div>
                </div>
                <!--End Submit Field-->
            </form>
        </div>
        <?php

    } elseif ($do == 'Insert') {  ////////////////////////////////////////////// Insert Member Page
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class='text-center'>Insert Item</h1>";
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
            $name     = $_POST['name'];
            $desc     = $_POST['description'];
            $price    = $_POST['price'];
            $country  = $_POST['country'];
            $status   = $_POST['status'];
            $member   = $_POST['member'];
            $cat      = $_POST['category'];
            $tags      = $_POST['tags'];
            
            // Validate the Form
            $formErrors = array();
            
            if (empty($name)) {

                $formErrors[] = 'Name of Item Can\'t Be <strong>Empty</strong>';
            }

            if (empty($desc)) {

                $formErrors[] = 'Description of Item Can\'t Be <strong>Empty</strong>';
            }

            if (empty($price)) {

                $formErrors[] = 'Price of Item Can\'t Be <strong>Empty</strong>';
            }

            if (empty($country)) {

                $formErrors[] = 'Country of Item Can\'t Be <strong>Empty</strong>';
            }

            if ($status == 0) {

                $formErrors[] = 'You Must Choose The <strong>Status</strong> Of Item';
            }
            
            if ($member == 0) {

                $formErrors[] = 'You Must Choose The <strong>Member</strong>';
            }

            if ($cat == 0) {

                $formErrors[] = 'You Must Choose The <strong>Category</strong> Of The Item';
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
                
                // Insert user informations to database
                $stmt = $con->prepare("INSERT INTO 
                                                items(Name, Description, Price, Made_in, Status, Add_Date, Cat_ID, Member_ID, tags, avatar)
                                                VALUES(:mname, :mdesc, :mprice, :mcountry, :mstatus, now(), :mcat, :mMember, :mtags, :mavatar)");

                $stmt->execute(array(
                    'mname'     => $name,
                    'mdesc'     => $desc,
                    'mprice'    => $price,
                    'mcountry'  => $country,
                    'mstatus'   => $status,
                    'mcat'      => $cat,
                    'mMember'   => $member,
                    'mtags'     => $tags,
                    'mavatar'   => $avatar

                ));

                // Echo success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record INSERTED</div>";
                redirectHome($theMsg, 'back');
            }

        } else {

            echo "<div class='container'>";

                $theMsg = '<div class="alert alert-danger">SORRY, YOU CANT EDIT THIS PAGE DIRECTLY</div>';
                redirectHome($theMsg);

            echo "</div>";
        }

        echo "</div>";

    } elseif ($do == 'Edit') {  ////////////////////////////////////////////////////// Edit Page 

        // Check if Get request itemid is numeric & get its integer value 
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;  // intval => integer value

        // Select all data in database that depend on this userId 
        $stmt = $con->prepare("SELECT * FROM items WHERE item_ID = ?");

        // Execute query
        $stmt->execute(array($itemid));

        // Fetch the data
        $item = $stmt->fetch();
        
        // The row count that prove that there is a record in database with this userId
        $count = $stmt->rowCount();
    
        // If thre is such userId, Show the Form
        if ($count > 0) { ?>

            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <div class="row">
                    <form class="col-md-8 form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
                    <!--start Name Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Item Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" placeholder="Name of The Item" 
                                value="<?php echo $item['Name'] ?>"/>
                        </div>
                    </div>
                    <!--End Name Field-->

                    <!--start Description Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control" placeholder="Description of The Item"
                                value="<?php echo $item['Description'] ?>"/>
                        </div>
                    </div>
                    <!--End Description Field-->

                    <!--start Price Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" class="form-control" placeholder="Price of The Item"
                                value="<?php echo $item['Price'] ?>"/>
                        </div>
                    </div>
                    <!--End Price Field-->

                    <!--start Country of The Item Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Country of Made</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="country" class="form-control" placeholder="Country of Made of The Item"
                                value="<?php echo $item['Made_in'] ?>"/>
                        </div>
                    </div>
                    <!--End Country of The Item Field-->

                    <!--start Status of The Item Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Status of The Item</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="status">
                                <option value="1" <?php if($item['Status'] == 1) { echo 'selected'; } ?>>New</option>
                                <option value="2" <?php if($item['Status'] == 2) { echo 'selected'; } ?>>Like New</option>
                                <option value="3" <?php if($item['Status'] == 3) { echo 'selected'; } ?>>Used</option>
                                <option value="4" <?php if($item['Status'] == 4) { echo 'selected'; } ?>>Old</option>
                            </select>
                        </div>
                    </div>
                    <!--End Status of The Item Field-->

                    <!--start Members Field--> <!-- Members Field : This is for the members who add the items in item list -->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="member">
                                <?php 
                                    $AllMembers = getAllFrom("*", "users", "", "", "UserID");
                                    foreach ($AllMembers as $user) {
                                        echo "<option value='" . $user['UserID'] . "'";
                                        if($item['Member_ID'] == $user['UserID']) { echo 'selected'; }
                                        echo ">" . $user['UserName'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--End Members Field-->

                    <!--start Categories Field--> <!-- Categories Field : This is for the Category which the item is Added From -->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="category">
                                <?php 
                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();
                                    foreach ($cats as $cat) {
                                        echo "<option value='" . $cat['ID'] . "'";
                                        if($item['Cat_ID'] == $cat['ID']) { echo 'selected'; }
                                        echo ">" . $cat['Name']. "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--End Categories Field-->
                    
                    <!--start Tags Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input 
                                type="text"
                                name="tags" 
                                class="form-control" 
                                placeholder="Seperate tags with comma ( , )"
                                value="<?php echo $item['tags'] ?>" />
                        </div>
                    </div>
                    <!--End Tags Field-->

                    <!--start Avatar Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Avatar</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="file" name="avatar" value="<?php echo $item['avatar'] ?>" class="form-control"/>
                        </div>
                    </div>
                    <!--End Avatar Field-->

                    <!--start Submit Field-->
                    <div class="form-group  form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save Item" class="btn btn-primary btn-sm add-item" />
                        </div>
                    </div>
                    <!--End Submit Field-->
                    </form>

                    <div class="col-md-4">
                        <?php 
                            if(! empty ($item['avatar'])) {
                                echo "<img class='user_avatar' src='uploads/avatars/" . $item['avatar'] . "' alt='' />";
                            } else {
                                echo "<img class='user_avatar' src='uploads/avatars/man.png' alt='' />";
                            }
                        
                        ?>
                    </div>

                </div>
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
                                        item_id = ?");
            // Execute the statement 
            $stmt->execute(array($itemid));
        
            // Assign this info to variable
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {
    
            ?>
                
            <h1 class="text-center">Manage <?php echo $item['Name'] ?> Comments</h1>
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>Comment</td>
                        <td>UserName</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>

                    <?php 
                        foreach($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['comment']       . "</td>";
                            echo "<td>" . $row['Member']       . "</td>";
                            echo "<td>" . $row['comment_date']  . "</td>";
                            echo "<td>
                                    <a href='comments.php?do=Edit&commid=" . $row['c_id'] . 
                                    "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                    <a href='comments.php?do=Delete&commid=" . $row['c_id'] . 
                                    "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                                    if ($row['status'] == 0) {
                                        echo "<a href='comments.php?do=Approve&commid=" .
                                        $row['c_id'] . "' class='btn btn-info activate'><i class='fa fa-check'></i>Approve</a>";
                                    }
                            
                            echo "</td>";
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
            <?php } ?>
        </div>
        
        <?php 

        } else {
            echo "<div class='container'>";

                $theMsg = '<div class="alert alert-danger">There is no such ID</div>';

                redirectHome($theMsg);

            echo "</div>";
        }
    } elseif ($do == 'Update') {  ////////////////////////////////////////// Update Page 

        echo "<h1 class='text-center'>Update Item</h1>";
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
            $id         = $_POST['itemid'];
            $name       = $_POST['name'];
            $desc       = $_POST['description'];
            $price      = $_POST['price'];
            $country    = $_POST['country'];
            $status     = $_POST['status'];
            $member     = $_POST['member'];
            $cat        = $_POST['category'];
            $tags       = $_POST['tags'];
            
            // Validate the Form
            $formErrors = array();
            
            if (empty($name)) {

                $formErrors[] = 'Name of Item Can\'t Be <strong>Empty</strong>';
            }

            if (empty($desc)) {

                $formErrors[] = 'Description of Item Can\'t Be <strong>Empty</strong>';
            }

            if (empty($price)) {

                $formErrors[] = 'Price of Item Can\'t Be <strong>Empty</strong>';
            }

            if (empty($country)) {

                $formErrors[] = 'Country of Item Can\'t Be <strong>Empty</strong>';
            }

            if ($status == 0) {

                $formErrors[] = 'You Must Choose The <strong>Status</strong> Of Item';
            }
            
            if ($member == 0) {

                $formErrors[] = 'You Must Choose The <strong>Member</strong>';
            }

            if ($cat == 0) {

                $formErrors[] = 'You Must Choose The <strong>Category</strong> Of The Item';
            }

            // Loop into errors array and Echo it
            foreach($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // Check if there is no error proceed the update operation      
            if (empty($formErrors)) {

                $avatar = rand(0, 10000) . '_' . $avatarName;

                move_uploaded_file($avatarTmp, "uploads/avatars/" . $avatar);

                // Update the database with this informations
                $stmt = $con->prepare("UPDATE
                                            items
                                        SET
                                            Name = ?,
                                            Description = ?,
                                            Price = ?,
                                            Made_in = ?,
                                            Status = ?,
                                            Cat_ID = ?,
                                            Member_ID = ?,
                                            tags =  ?,
                                            avatar = ?
                                        WHERE 
                                            item_ID = ?");
                $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $avatar, $id));

                // Echo success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record UPDATED</div>';
                redirectHome($theMsg, 'back');
            }

        } else {

            $theMsg = '<div class="alert alert-danger">SORRY, YOU CANT EDIT THIS PAGE DIRECTLY WITHOUT POST METHOD</div>';

            redirectHome($theMsg);
        }

        echo "</div>";

    } elseif($do == 'Delete') {  /////////////////////////////////// Delete Members Page

        echo "<h1 class='text-center'>Delete Item</h1>";
        echo "<div class='container'>";

            // Check if Get request UserId is numeric & get its integer value 
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;   // intval => integer value

            // Select all data depend on this userID
            $check = checkItem('item_ID', 'items', $itemid);
            
            // If thre is such userId, Show the Form
            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :mid ");

                $stmt->bindParam(":mid", $itemid);

                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record DELETED </div>';
                redirectHome($theMsg, 'back');
            } else {
                $theMsg = "<div class='alert alert-danger'>No such ID</div>";
                redirectHome($theMsg);
            }

        echo "</div>";

    } elseif($do == 'Approve') { ///////////////////////////////////////////// Activate Members Page   

        echo "<h1 class='text-center'>Approve Member</h1>";
        echo "<div class='container'>";

            // Check if Get request itemid is numeric & get its integer value 
            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;   // intval => integer value

            // Select all data depend on this userID
            $check = checkItem('item_ID', 'items', $itemid);
            
            // If thre is such userId, Show the Form
            if ($check > 0) {

                $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");

                $stmt->execute(array($itemid));

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Activated </div>';
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

ob_end_flush();