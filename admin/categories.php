<?php

/*
============================================================
== Category Page
============================================================
*/
    session_start();

    ob_start();   // Output Buffering Start

    $pageTitle = '';

    if (isset($_SESSION['Username'])) {
        
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        // Start Manage Page
        if ($do =='Manage') {  ////////////////////////////////////// Manage Categories Page 

            $sort = 'DESC';  // Ascending Order

            $sort_array = array('ASC', 'DESC');

            if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

                $sort = $_GET['sort'];
            }

            $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");
            $stmt2->execute();
            $categs = $stmt2->fetchAll(); ?>

            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-edit"></i> Manage Categories
                        <div class="options pull-right">
                            <i class="fa fa-sort"></i> Ordering: [
                            <a href="?sort=ASC" class="<?php if($sort == 'ASC') { echo 'active'; } ?>">ASC</a> |
                            <a href="?sort=DESC" class="<?php if($sort == 'DESC') { echo 'active'; } ?>">DESC</a> ]
                            <i class="fa fa-eye"></i> View: [
                                <span class="active" data-view="full">Full</span> |
                                <span data-view="classic">Classic</span> ]
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php 
                            foreach ($categs as $categ) {
                                echo "<div class='cat'>";
                                    echo "<div class='hidden-btns'>";
                                        echo "<a href='categories.php?do=Edit&catid=" .$categ['ID'] .  "'class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                        echo "<a href='categories.php?do=Delete&catid=" .$categ['ID'] ."'class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>";
                                    echo "</div>";
                                    echo '<h3>' . $categ['Name'] . '</h3>'; 
                                    echo "<div class='full-view'>";
                                        echo '<p>';
                                            if ($categ['Description'] == '') {
                                                echo "This Category Has No Description";
                                            } else {
                                                echo $categ['Description'];
                                            }
                                        echo '</p>'; 
                                        if ($categ['Visibility'] == 1) { echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>'; } 
                                        if ($categ['Allow_Comment'] == 1) { echo '<span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>'; } 
                                        if ($categ['Allow_Ads'] == 1) { echo '<span class="advertises"><i class="fa fa-close"></i> Advertises Disabled</span>'; } 
                                        // Get Child Categories / Sub-categories
                                        $childCats = getAllFrom("*", "categories", "WHERE parent = {$categ['ID']}", "", "ID", "ASC");
                                        if (! empty($childCats)) {
                                            echo '<h4 class="child-head">Child Category</h4>';
                                            echo '<ul class="list-unstyled child-cats">';
                                            foreach ($childCats as $cat) {
                                                echo  "<li class='child-cat'> 
                                                    <a href='categories.php?do=Edit&catid=" .$cat['ID'] .  "'>" . $cat['Name'] . "</a>
                                                    <a href='categories.php?do=Delete&catid=" .$cat['ID'] ."'class='show-delete confirm'>Delete</a>    
                                                </li>";
                                            }
                                            echo '</ul>';
                                        }

                                    echo "</div>";
                                echo "</div>";
                                echo "<hr>";

                                
                            }
                        ?>
                    </div>
                </div>
                <a class="add-cat btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> New Category</a>
            </div>

            <?php

        } elseif ($do == 'Add') { ////////////////////////////////// Add Member Page ?>
            <h1 class="text-center">Add New Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <!--start Name Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Category Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control" autocomplete="off"  placeholder="Name of The Category" required="required"/>
                        </div>
                    </div>
                    <!--End Name Field-->

                    <!--start Description Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control" placeholder="Descripe The Category"/>
                        </div>
                    </div>
                    <!--End Description Field-->

                    <!--start Ordering Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange/Order The Categories"/>
                        </div>
                    </div>
                    <!--End Ordering Field-->

                    <!--start Category Type Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Category Parent</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php 
                                    $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "DESC");
                                    foreach ($allCats as $cat) {
                                        echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--End Category Type Field-->

                    <!--start Visibility Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Visible</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1">
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--End Visibility Field-->

                    <!--start Comment Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="comm-yes" type="radio" name="commenting" value="0" checked>
                                <label for="comm-yes">Yes</label>
                            </div>
                            <div>
                                <input id="visible-no" type="radio" name="commenting" value="1">
                                <label for="visible-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--End Comment Field-->

                    <!--start Advertise Field-->
                    <div class="form-group  form-group-lg">
                        <label class="col-sm-2 control-label">Allow Advertising</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="ads-yes" type="radio" name="advertising" value="0" checked>
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="advertising" value="1">
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>
                    <!--End Advertise Field-->

                    <!--start Submit Field-->
                    <div class="form-group  form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!--End Submit Field-->
                </form>
            </div>
            <?php

        } elseif ($do == 'Insert') {  ////////////////////////////////////////////// Insert Category Page

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo "<h1 class='text-center'>Insert Category</h1>";
                echo "<div class='container'>";
                
                // Get variables from Form
                $name       = $_POST['name'];
                $desc       = $_POST['description'];
                $parent     = $_POST['parent'];
                $order      = $_POST['ordering'];
                $visible    = $_POST['visibility'];
                $comment    = $_POST['commenting'];
                $ads        = $_POST['advertising'];
    
                
            
                // Check if Category is exist in Database
                $check = checkItem("Name", "categories", $name);

                if ($check == 1) {

                    $theMsg = "<div class='alert alert-danger'>Sorry, This Category is already exist in database</div>";
                    redirectHome($theMsg, 'back');
                } else {
                    // Insert Category Informations To Database
                    $stmt = $con->prepare("INSERT INTO 
                        categories(Name, Description, parent, Ordering, Visibility, Allow_Comment, Allow_Ads)
                        VALUES(:mname, :mdesc,:mparent, :morder, :mvisible, :mcomment, :mads)");

                    $stmt->execute(array(
                        'mname'     => $name,
                        'mdesc'     => $desc,
                        'mparent'   => $parent,
                        'morder'    => $order,
                        'mvisible'  => $visible,
                        'mcomment'  => $comment,
                        'mads'      => $ads

                    ));

                    // Echo success Message
                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record INSERTED</div>";
                    redirectHome($theMsg, 'back');
                }
    
            } else {
    
                echo "<div class='container'>";
                    $theMsg = '<div class="alert alert-danger">SORRY, YOU CANT EDIT THIS PAGE DIRECTLY WITHOUT POST METHOD</div>';
                    redirectHome($theMsg, 'back');
                echo "</div>";
            }
    
            echo "</div>";

        } elseif ($do == 'Edit') {  ////////////////////////////////////////////////////// Edit Page 

                // Check if Get request Category ID is numeric & get its integer value 
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;  // intval => integer value

            // Select all data in database that depend on this userId 
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");

            // Execute query
            $stmt->execute(array($catid));
            // Fetch the data
            $cat = $stmt->fetch();
            // The row count that prove that there is a record in database with this userId
            $count = $stmt->rowCount();
        
            // If thre is such catId, Show the Form
            if ($count > 0) { ?>

                <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="catid" value="<?php echo $catid ?>">
                        <!--start Name Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">Category Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Name of The Category" required="required" value="<?php echo $cat['Name'] ?>"/>
                            </div>
                        </div>
                        <!--End Name Field-->

                        <!--start Description Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="description" class="form-control" placeholder="Descripe The Category" value="<?php echo $cat['Description'] ?>"/>
                            </div>
                        </div>
                        <!--End Description Field-->

                        <!--start Ordering Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="ordering" class="form-control" placeholder="Number To Arrange/Order The Categories" value="<?php echo $cat['Ordering'] ?>"/>
                            </div>
                        </div>
                        <!--End Ordering Field-->
                        <!--start Category Type Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">Category Parent</label>
                            <div class="col-sm-10 col-md-6">
                                <select name="parent">
                                    <option value="0">None</option>
                                    <?php 
                                        $allCats = getAllFrom("*", "categories", "WHERE parent = 0", "", "ID", "DESC");
                                        foreach ($allCats as $c) {
                                            echo "<option value='" . $c['ID'] . "'";
                                            if ($cat['Parent'] == $c['ID']) { echo 'selected'; }
                                            echo ">" . $c['Name'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!--End Category Type Field-->
                        <!--start Visibility Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">Visible</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) { echo 'checked'; } ?> >
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) { echo 'checked'; } ?> >
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!--End Visibility Field-->

                        <!--start Comment Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">Allow Commenting</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="comm-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) { echo 'checked'; } ?> >
                                    <label for="comm-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="visible-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) { echo 'checked'; } ?> >
                                    <label for="visible-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!--End Comment Field-->

                        <!--start Advertise Field-->
                        <div class="form-group  form-group-lg">
                            <label class="col-sm-2 control-label">Allow Advertising</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input id="ads-yes" type="radio" name="advertising" value="0" <?php if ($cat['Allow_Ads'] == 0) { echo 'checked'; } ?> >
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="advertising" value="1" <?php if ($cat['Allow_Ads'] == 1) { echo 'checked'; } ?> >
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!--End Advertise Field-->

                        <!--start Submit Field-->
                        <div class="form-group  form-group-lg">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="Save Updates" class="btn btn-primary btn-lg" />
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

            echo "<h1 class='text-center'>Update Category</h1>";
            echo "<div class='container'>";
    
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get variables from Form
                $id       = $_POST['catid'];
                $name     = $_POST['name'];
                $desc     = $_POST['description'];
                $order    = $_POST['ordering'];
                $parent   = $_POST['parent'];             
                $visible  = $_POST['visibility'];             
                $comment  = $_POST['commenting'];             
                $ads      = $_POST['advertising'];             
   
                // Update the database with this informations
                $stmt = $con->prepare("UPDATE 
                                            categories
                                        SET 
                                            Name = ?, 
                                            Description = ?, 
                                            Ordering = ?,
                                            Parent = ?, 
                                            Visibility = ?, 
                                            Allow_Comment = ?, 
                                            Allow_Ads = ? 
                                        WHERE 
                                            ID = ?");
                $stmt->execute(array($name, $desc, $order, $parent, $visible, $comment, $ads, $id));

                // Echo success Message
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record UPDATED</div>';
                
                redirectHome($theMsg, 'back');
                
    
            } else {
    
                $theMsg = '<div class="alert alert-danger">SORRY, YOU CANT EDIT THIS PAGE DIRECTLY WITHOUT POST METHOD</div>';
    
                redirectHome($theMsg);
            }
    
            echo "</div>";

        } elseif($do == 'Delete') {  /////////////////////////////////// Delete Members Page

            echo "<h1 class='text-center'>Delete Category</h1>";
            echo "<div class='container'>";

            // Check if Get request catid is numeric & get its integer value 
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;   // intval => integer value

            // Select all data depend on this userID
            $check = checkItem('ID', 'categories', $catid);
            
            // If thre is such userId, Show the Form
            if ($check > 0) {

                $stmt = $con->prepare("DELETE FROM categories WHERE ID = :mid ");

                $stmt->bindParam(":mid", $catid);

                $stmt->execute();

                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record DELETED </div>';
                redirectHome($theMsg, 'back');
            } else {
                $theMsg = "<div class='alert alert-danger'>No such ID</div>";
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

?>