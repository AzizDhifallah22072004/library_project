<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Member</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="style2.css">
  <script src="project.js"></script>
</head>
<body>
  <?php include 'header.phtml'; ?>
  
  <div class="container py-4">
    <div class="col-md-8 mx-auto">
      <h1 style="text-align: center;">Add New Member</h1>
      <?php 
        session_start();

        if (!isset($_SESSION['full_name'])) {
            header('Location: login.php');
            exit();
        } 

        include 'dbconnect.php';

        if(!empty($_POST)) {
          $fullName = $_POST['fullname'];
          $email = $_POST['email'];
          $password = $_POST['password'];
          $passwordRepeat = $_POST['repeat_password'];
          $telephone = $_POST['telephone'];
          $dateInscription = $_POST['date_inscription'];
          $errors = array();

          // Validation des champs
          if(empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat) || empty($telephone) || empty($dateInscription)) {
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

          // Vérifier si l'email existe déjà
          $sql = "SELECT * FROM adhérents WHERE email = ?";
          $query = $pdo->prepare($sql);
          $query->execute([$email]);
          $res = $query->fetch();
          if ($res) {
            array_push($errors, "This email is already registered!");
          }

          // Afficher les erreurs
          if(count($errors) > 0) {
            foreach($errors as $error) {
              echo "<div class='alert alert-danger'>$error</div>";
            }
          } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Insertion dans la base de données
            $sql = "INSERT INTO adhérents(full_name, email, password, telephone, date_inscription) VALUES(?, ?, ?, ?, ?)";
            $query = $pdo->prepare($sql);
            
            if($query) {
              $query->execute([$fullName, $email, $hashedPassword, $telephone, $dateInscription]);
              header('Location: manage_members.php?success=1');
              exit();
            } else {
              die("Something went wrong!");
            }
          } 
        }
      ?>
    </div>
    
    <!-- Formulaire -->
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
      <div class="mb-3">
        <label for="fullname" class="form-label">Full Name</label>
        <input type="text" class="form-control" name="fullname" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
      </div>

      <div class="mb-3">
        <label for="repeat_password" class="form-label">Repeat Password</label>
        <input type="password" class="form-control" name="repeat_password" required>
      </div>

      <div class="mb-3">
        <label for="telephone" class="form-label">Telephone</label>
        <input type="text" class="form-control" name="telephone"  required>
      </div>

      <div class="mb-3">
        <label for="date_inscription" class="form-label">Date of Registration</label>
        <input type="date" class="form-control" name="date_inscription" required>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">Add</button>
        <a href="manage_members.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
  
</body>
</html>
