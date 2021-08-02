<?php 
      ob_start();
      session_start();
      $pageTitle  = 'Login';

      if (isset($_SESSION['user'])) {
            header('Location: index.php');  // Redirect to index page
      }

      include "init.php";

       // Check if user is coming from http post request:
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedPassword = sha1($pass);

            // Check if user exist in database:
            $stmt = $con->prepare("SELECT
                                          UserName, Password 
                                    FROM 
                                          users 
                                    Where 
                                          UserName = ? 
                                    AND 
                                          Password = ?");
            $stmt->execute(array($user, $hashedPassword));
            $rowsCount = $stmt->rowCount();

            // If count > 0, this mean the database contain record about this user
            if($rowsCount > 0) {
                  $_SESSION['user'] = $user; // Register session name
                  header('Location: index.php');  // Redirect to dashboard page
                  exit();
            }
      }
?>
  <div class="container login-page">
    <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span></h1>
    <!-- Start Login Form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
          <input 
                class="form-control" 
                type="text" 
                name="username" 
                autocomplete="off"
                placeholder="Enter Username"
                required>
        </div>
        <div class="input-container">
        <input 
              class="form-control" 
              type="password" 
              name="password" 
              autocomplete="new-password"
              placeholder="Enter Password"
              required>
        </div>
        <input 
              class="btn btn-primary btn-block" 
              type="submit" 
              value="Login">
    </form>
    <!-- End Login Form -->

    <!-- Start SignUp Form -->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
    <div class="input-container">
        <input 
              class="form-control" 
              type="text" 
              name="username" 
              autocomplete="off"
              placeholder="Enter Username"
              required>
    </div>
    <div class="input-container">
        <input 
              class="form-control" 
              type="password" 
              name="password" 
              autocomplete="new-password"
              placeholder="Enter a Complex Password"
              required>
    </div>
    <div class="input-container">
        <input 
              class="form-control" 
              type="password" 
              name="re-password"  
              autocomplete="new-password"
              placeholder="Re-enter The Password"
              required>
    </div>
    <div class="input-container">
        <input 
              class="form-control" 
              type="email" 
              name="email" 
              placeholder="Enter valid Email"
              required>
    </div>
        <input 
              class="btn btn-success btn-block" 
              type="submit" 
              value="SignUp">
    </form>
    <!-- End SignUp Form -->
  </div>  



<?php 
    include $templates . "footer.php";
    ob_end_flush();
?>