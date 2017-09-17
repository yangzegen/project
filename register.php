<?php
session_start();
include("includes/database.php");
$method = $_SERVER["REQUEST_METHOD"];
if($method == "POST"){
  $errors = array();
  $username = $_POST["username"];
  //check if username > 16 chrs
  if(strlen($username)>16 ){
    $errors["username"] = "username too long";
  }
  elseif(strlen($username)<8){
    $errors["username"] = "username too short (8 characters minimum)";
  }
  $email = $_POST["email"];
  //make user email is valid
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errors["email"] = "email address invalid"; 
  }
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $password1 = $_POST["password1"];
  $password2 = $_POST["password2"];
  if($password1 !== $password2){
    $errors["password"] = "passwords do not match";
  }
  
  $errorscount = count($errors);
  if($errorscount==0){
    //insert data into database
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password1"], PASSWORD_DEFAULT);
    //create query string
    $register_query = "INSERT INTO account 
    (username, email, firstname, lastname, password, level, created, lastlogin, active)
    VALUES
    ('$username','$email','$firstname','$lastname','$password', 3, NOW(), NOW(), 1)";
    
    //run query against database connection
    $result = $connection->query($register_query);
    
    if($result==false){
      echo "error creating account!";
      //there is an error store error code
      $error_code = mysqli_errno($connection);
      $error_msg = mysqli_error($connection);
      if($error_code == '1062' && stristr($error_msg,'username')){
        $errors["username"] = "username already taken";
      }
      elseif($error_code == '1062' && stristr($error_msg,'email')){
        $errors["email"] = "email already used";
      }
      echo mysqli_error($connection).", code=".$error_code;
    }
    else{
      echo "account created!";
      $_SESSION["id"] = $connection -> insert_id;
      $_SESSION["username"] = $username;
    }
  }
}
?>
<!doctype html>
<html>
  <?php include("includes/head.php"); ?>
  <body>
    <?php include("includes/navigation.php"); ?>
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
          <h2>Register For An Account</h2>
          <form id="register-form" action="<?php echo $currentpage; ?>" method="post">
            <?php
            if($errors["username"]){
              $usernameclass = "has-error";
            }
            ?>
            <div class="form-group <?php echo $usernameclass;?> ">
              <label for="username">User Name</label>
              <input type="text" name="username" class="form-control" id="username" placeholder="maximum 16 characters"
              value="<?php echo $username; ?>">
              <span class="help-block">
                <?php echo $errors["username"];?>
              </span>
            </div>
            <?php
            if($errors["email"]){
              $emailclass = "has-error";
            }
            ?>
            <div class="form-group <?php echo $emailclass; ?>">
              <label for="email">Email</label>
              <input type="email" name="email" class="form-control" id="email" placeholder="user@domain.com"
              value="<?php echo $email; ?>">
              <span class="help-block">
                <?php echo $errors["email"]; ?>
              </span>
            </div>
            <div class="form-group">
              <label for="firstname">First name</label>
              <input type="text" name="firstname" class="form-control" id="firstname" placeholder="First Name">
              <label for="lastname">Last name</label>
              <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Last Name">
              <label for="password1">Password</label>
              <input type="password" name="password1" class="form-control" id="password1" placeholder="minimum 8 characters">
              <label for="password2">Confirm Password</label>
              <input type="password" name="password2" class="form-control" id="password2" placeholder="retype your password">
              <span class="help-block">
                <?php echo $errors["password"]; ?>
              </span>
            </div>
            
            <div class="form-buttons text-center">
              <button type="submit" name="submit" value="register" class="btn btn-info">Register</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>