<?php 
    session_start();
    $pageTitle  = 'Create New Item';
    include 'init.php';
    if(isset($_SESSION['user'])) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formErrors = array();

            $name      = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $desc      = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $price     = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country   = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
            $status    = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category  = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            $tags      = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

            // Check the length of the name of the item 
            if (strlen($name) < 3) {
                $formErrors[] = "Item name must be larger than 3 chars";
            } 

            // Check the length of the desc of the item 
            if (strlen($desc) < 10) {
                $formErrors[] = "Item description must be larger than 10 chars";
            } 
            
            // Check the length of the country of the item 
            if (strlen($country) < 3) {
                $formErrors[] = "Item country name must be larger than 3 chars";
            } 

            // Check the length of the price of the item 
            if (empty($price)) {
                $formErrors[] = "Item price can't be empty";
            } 

            // Check the length of the status of the item 
            if (empty($status)) {
                $formErrors[] = "Item status can't be empty";
            } 
            
            // Check the length of the category of the item 
            if (empty($category)) {
                $formErrors[] = "Item category can't be empty";
            }
            
            if (empty($formErrors)) {
                
                // Insert user informations to database
                $stmt = $con->prepare("INSERT INTO 
                        items(Name, Description, Price, Made_in, Status, Add_Date, Cat_ID, Member_ID, tags)
                        VALUES(:mname, :mdesc, :mprice, :mcountry, :mstatus, now(), :mcat, :mMember, :mtags)");

                $stmt->execute(array(
                    'mname'     => $name,
                    'mdesc'     => $desc,
                    'mprice'    => $price,
                    'mcountry'  => $country,
                    'mstatus'   => $status,
                    'mcat'      => $category,
                    'mMember'   => $_SESSION['user_id'],
                    'mtags'     => $tags

                ));

                // Echo success Message
                if ($stmt) {
                    $successMsg = "Item added successfully";
                }
            }

        }    
?>

<h1 class="text-center"><?php echo $pageTitle ?></h1>

<div class="create-ad block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading"><?php echo $pageTitle ?></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <!--start Name Field-->
                            <div class="form-group  form-group-lg">
                                <label class="col-sm-3 control-label">Item Name</label>
                                <div class="col-sm-10 col-md-9">
                                    <input 
                                        pattern=".{4,}"
                                        title="This field require at least 4 char"
                                        type="text" 
                                        name="name" 
                                        class="form-control live" 
                                        placeholder="Name of The Item"
                                        data-class=".live-title" 
                                        required/>
                                </div>
                            </div>
                            <!--End Name Field-->

                            <!--start Description Field-->
                            <div class="form-group  form-group-lg">
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-10 col-md-9">
                                    <input 
                                        type="text" 
                                        name="description" 
                                        class="form-control live" 
                                        placeholder="Description of The Item"
                                        data-class=".live-desc" 
                                        required/>
                                </div>
                            </div>
                            <!--End Description Field-->

                            <!--start Price Field-->
                            <div class="form-group  form-group-lg">
                                <label class="col-sm-3 control-label">Price</label>
                                <div class="col-sm-10 col-md-9">
                                    <input 
                                        type="text" 
                                        name="price" 
                                        class="form-control live" 
                                        placeholder="Price of The Item"
                                        data-class=".live-price" 
                                        required/>
                                </div>
                            </div>
                            <!--End Price Field-->

                            <!--start Country of The Item Field-->
                            <div class="form-group  form-group-lg">
                                <label class="col-sm-3 control-label">Country of Made</label>
                                <div class="col-sm-10 col-md-9">
                                    <input 
                                        type="text" 
                                        name="country" 
                                        class="form-control" 
                                        placeholder="Country of Made of The Item"
                                        required/>
                                </div>
                            </div>
                            <!--End Country of The Item Field-->

                            <!--start Status of The Item Field-->
                            <div class="form-group  form-group-lg">
                                <label class="col-sm-3 control-label">Status of Item</label>
                                <div class="col-sm-10 col-md-9">
                                    <select name="status" required>
                                        <option value="">...</option>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Used</option>
                                        <option value="4">Old</option>
                                    </select>
                                </div>
                            </div>
                            <!--End Status of The Item Field-->

                            <!--start Categories Field--> <!-- Categories Field : This is for the Category which the item is Added From -->
                            <div class="form-group  form-group-lg">
                                <label class="col-sm-3 control-label">Category</label>
                                <div class="col-sm-10 col-md-9">
                                    <select name="category" required>
                                        <option value="">...</option>
                                        <?php
                                            $allItems = getAllFrom('*', 'categories', '', '', 'ID', ''); 
                                            
                                            foreach ($allItems as $items) {
                                                echo "<option value='" . $items['ID'] . "'>" . $items['Name']. "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!--End Categories Field-->

                            <!--start Tags Field-->
                            <div class="form-group  form-group-lg">
                                <label class="col-sm-3 control-label">Tags</label>
                                <div class="col-sm-10 col-md-9">
                                    <input 
                                        type="text"
                                        name="tags" 
                                        class="form-control" 
                                        placeholder="Seperate tags with comma ( , )"/>
                                </div>
                            </div>
                            <!--End Tags Field-->

                            <!--start Submit Field-->
                            <div class="form-group  form-group-lg">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
                                </div>
                            </div>
                            <!--End Submit Field-->
                        </form>     
                    </div>
                    <div class="col-md-4">
                    <div class="thumbnail item-box live-preview">
                            <span class="price live-price">$0</span>
                            <img class="img-responsive" src="man.png" alt="item-image">
                            <div class="caption">
                                <h3 class="live-title">Name</h3>
                                <p class="live-desc">Description</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Loop through errors -->
                <?php 
                    if (! empty($formErrors)) {
                        foreach ($formErrors as $error) {
                            echo "<div class='alert alert-danger'>". $error . "</div>";
                        }
                    }

                    if (isset($successMsg)) {
                        echo "<div class='alert alert-success'>" . $successMsg . "</div>";
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