<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
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
      <h1>My Account</h1>
      <?php
        include 'dbconnect.php';
        session_start();
        if (isset($_SESSION['full_name'])) {//si y a une session
          header('Location: dashboard.php');
          exit();
        } 
 
        if (!empty($_POST)) {
            $email = htmlspecialchars($_POST['email']);
            $pwd = $_POST['password'];
            $sql = "SELECT * FROM adhérents WHERE email = ?";
            $query = $pdo->prepare($sql);
            $query->execute([$email]);
            $user = $query->fetch();

            if ($user === false || !password_verify($pwd, $user['password'])) {
                echo "<div class='alert alert-danger'>Invalid email or password!</div>";
            } else {
                $_SESSION['id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                header('Location: dashboard.php');
                
            }
        }
      ?>

      <form action="<?=$_SERVER['PHP_SELF']?>" method="post">

        <div class="form-group">
          <input type="email" class="form-control" name="email" placeholder="Email:">
          <i class="bi bi-envelope"></i>
        </div>

        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Password:">
          <i class="bi bi-lock"></i>
        </div>

        <div class="form-group">
          <input type="submit" name="submit" value="Login" class="btn btn-primary">
        </div>

        <div class="form-group">
          <a href="sign_up.php">Don't have account? <b>Register</b></a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>