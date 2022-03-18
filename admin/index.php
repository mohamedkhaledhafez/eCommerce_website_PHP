<?php 
    session_start();
    $noNavbar   = '';
    $pageTitle  = 'Login';
    if (isset($_SESSION['Username'])) {
        header('Location: dashboard.php');  // Redirect to dashboard page
    }
    // print_r($_SESSION);
    include 'init.php';
    
    // Check if user is coming from http post request:
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedPassword = sha1($password);

        // Check if user exist in database:
        $stmt = $con->prepare("SELECT
                                    UserID, UserName, Password 
                                FROM 
                                    users 
                                Where 
                                    UserName = ? 
                                AND 
                                    Password = ? 
                                AND 
                                    GroupID = 1
                                LIMIT 1");
        $stmt->execute(array($username, $hashedPassword));
        $row = $stmt->fetch();
        $rowsCount = $stmt->rowCount();

        // If count > 0, this mean the database contain record about this user
        if($rowsCount > 0) {
            $_SESSION['Username'] = $username; // Register session name
            $_SESSION['ID']       = $row['UserID']; // Register session ID 
            header('Location: dashboard.php');  // Redirect to dashboard page
            exit();
        }
    }

?>

    <!-- Welcome To Index Page  -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control form-control-lg" type="text" name="user" placeholder="Username" autocomplete="on">
        <input class="form-control form-control-lg" type="password" name="pass" placeholder="Password" autocomplete="new-password">
        <input class="btn btn-primary btn-block" type="submit" value="Login">
    </form>
    
<?php include $templates . 'footer.php'; ?>