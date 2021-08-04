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

                  if(isset($_POST['login'])) {

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
            } else {
                  $formsErrors = array();

                  $username = $_POST['username'];
                  $password = $_POST['password'];
                  $password2 = $_POST['re-password'];
                  $email    = $_POST['email'];

                  //Check for username
                  if (isset($username)) {
                        $filteredUser = filter_var($username, FILTER_SANITIZE_STRING);
                        
                        if (strlen($filteredUser) < 3) {
                              $formsErrors[] = "Username shouldn't be less than 3 charachters";
                        }
                  }

                  //Check for password
                  if (isset($password) && isset($password2)) {
                        //Check for empty password
                        if (empty($password)) {
                              $formsErrors[] = "Sorry, Password cat't be empty";
                        }

                       // Check if two passwords are identical
                       if (sha1($password) !== sha1($password2)) {
                             $formsErrors[] = "The two passwords are not the same";
                       }
                  }

                  //Check for Email
                  if (isset($email)) {
                  $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
                  
                        if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) {
                              $formsErrors[] = "This email is not valid";
                        }
                  }

                  // Check if there is no error proceed the user addition
            
                  if (empty($formErrors)) {
                        // Check if user is exist in Database
                        $check = checkItem("UserName", "users", $username);
      
                        if ($check == 1) {
      
                              $formsErrors[] = "This User Is Exist";

                        } else {
                        // Insert user informations to database
                        $stmt = $con->prepare("INSERT INTO 
                                          users(UserName, Password, Email, RegisterStatus, Date)
                                          VALUES(:muser, :mpass, :mmail, 0, now())");
      
                        $stmt->execute(array(
                              'muser' => $username,
                              'mpass' => sha1($password),
                              'mmail' => $email
      
                        ));
      
                        // Echo success Message
                        $successMsg = "Congrats, now you're successfully registered";
                       
                        }
                  }

            }
      }
?>
  <div class="container login-page">
    <h1 class="text-center"><span class="selected" data-class="login">Login</span> | 
    <span data-class="signup">SignUp</span></h1>
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
        <input class="btn btn-primary btn-block" name="login" type="submit" value="Login">
    </form>
    <!-- End Login Form -->

    <!-- Start SignUp Form -->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"action>
    <div class="input-container">
        <input 
              pattern=".{4,}"
              title="Username must be larger than 2 chars"
              class="form-control" 
              type="text" 
              name="username" 
              autocomplete="off"
              placeholder="Enter Username"
              required >
    </div>
    <div class="input-container">
        <input 
              minlength="4"
              class="form-control" 
              type="password" 
              name="password" 
              autocomplete="new-password"
              placeholder="Enter a Complex Password" 
              required >
    </div>
    <div class="input-container">
        <input 
              minlength="4"
              class="form-control" 
              type="password" 
              name="re-password"  
              autocomplete="new-password"
              placeholder="Re-enter The Password" 
              required >
    </div>
    <div class="input-container">
        <input 
              class="form-control" 
              type="email" 
              name="email" 
              placeholder="Enter valid Email" >
    </div>
        <input class="btn btn-success btn-block" name="signup" type="submit" value="SignUp">
    </form>
    <!-- End SignUp Form -->

    <!-- Error Message Box -->
    <div class="the-error text-center">
          <?php 
            if (!empty($formsErrors)) {
                  foreach ($formsErrors as $error) {
                        echo $error . '<br>';
                  }
            }

            if (isset($successMsg)) {
                  echo "<div class='msg success'>" . $successMsg . "</div>";
            }
          ?>
    </div>
  </div>  



<?php 
    include $templates . "footer.php";
    ob_end_flush();
?>