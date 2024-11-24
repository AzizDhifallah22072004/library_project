<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="library-logo-container">
    <img src="logo.jpg" alt="Library Logo">
    <h1 class="library-name">Success Library</h1>
  </div>
  
  <div class="log-sign-container">
    <div class="login-img">
      <img src="container-img.avif" alt="Library Image">
    </div>

    <div class="container text-center">
      <h1>Create New Account</h1>
      <?php 
        session_start();
        //var_dump($_SESSION);
        if (isset($_SESSION['username'])){
          header('Location: dashboard.php');
          exit();
        }

        include 'dbconnect.php';

        if(!empty($_POST)) {
          $fullName = $_POST['fullname'];
          $email= $_POST['email'];
          $tel = $_POST['phone'];
          $password = $_POST['password'];
          $passwordRepeat = $_POST['repeat_password'];
          $errors = array();

          if(empty($fullName) or empty($email) or empty($tel) or empty($password)  or empty($passwordRepeat)) {
            array_push($errors, "All fields are required!");
          }

          if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid!");
          }

          if(strlen($password) < 8) {
            array_push($errors, "Password must be at least 8 characters long!");
          }

          if($password !== $passwordRepeat) {
            array_push($errors, "Password does not match!");
          }

          

          $sql = "SELECT * FROM adhérents WHERE email = ?";
          $query = $pdo->prepare($sql);
          $query->execute([$email]);
          $res = $query->fetch();
          if ($res) {
            array_push($errors, "This email is already registered!");
          }


          if(count($errors) > 0) {
            foreach($errors as $error) {
              echo "<div class='alert alert-danger'>$error</div>";
            }
          } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO adhérents(full_name, email,telephone, password) VALUES(?,?,?,?)";
            $query = $pdo->prepare($sql);
            
            if($query) {
              $query->execute([$fullName,$email,$tel,$hashedPassword]);
              echo "<div class='alert alert-success'>You are registred successfully</div>";
            } else{
              die("Something went wrong!");
            }
          } 
        }

        $template = 'sign_up';
        $pageTitle= 'sign up';
      ?>

      <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <div class="form-group">
          <input type="text" class="form-control" name="fullname" placeholder="Full Name">
          <i class="bi bi-person"></i>
        </div>
        <div class="form-group">
          <input type="email" class="form-control" name="email" placeholder="Email">
          <i class="bi bi-envelope"></i>
        </div>
        <div class="form-group">
          <input type="text" class="form-control" name="phone" placeholder="Phone">
          <i class="bi bi-telephone"></i>
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <i class="bi bi-lock"></i>
        </div>
        <div class="form-group">
          <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
          <i class="bi bi-lock"></i>
        </div>
        <div class="form-group">
          <input type="submit" name="submit" value="Register" class="btn btn-primary">
        </div>
        <div class="form-group">
          <a href="login.php">Already a member? Login</a>
        </div>
      </form>
    </div>
  </div>
</body>

</html>