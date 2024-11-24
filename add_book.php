<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Book</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="style2.css">
  <script src="project.js"></script>
</head>
<body>
  <?php 
      include 'header.phtml';
  ?>
  
  <?php 
    session_start();

    if (!isset($_SESSION['full_name'])) {//si y a une session
        header('Location: login.php');
        exit();
    } 

    include 'dbconnect.php';

    if(!empty($_POST)) {
      $title = $_POST['Title'];
      $author = $_POST['Author'];
      $gender = $_POST['Gender'];
      $availability = $_POST['Availability'];
      $summary = $_POST['Summary'];

      $sql = "INSERT INTO livres(titre,auteur,genre,disponibilite,resume) VALUES (?,?,?,?,?)";
      $query = $pdo->prepare($sql);
      $query->execute([$title, $author, $gender, $availability, $summary]);

      header('Location: manage_books.php?success=1');
      exit();
    }
    
  ?>
 
  <div class="container py-4">
    <div class="col-md-8 mx-auto">
      <h1 style="text-align: center;">Add New Book</h1>
    </div>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">

      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="Title" required>
      </div>

      <div class="mb-3">
        <label for="author" class="form-label">Author</label>
        <input type="text" class="form-control" id="author" name="Author" required>
      </div>
      
      <div class="mb-3">
        <label for="author" class="form-label">Gender</label>
        <input type="text" class="form-control" id="gender" name="Gender" required>
      </div>
      

      <div class="mb-3">
        <label for="availability" class="form-label">Availability</label>
        <input type="number" class="form-control" id="availability" name="Availability" required>
      </div>

      <div class="mb-3">
        <label for="summary" class="form-label">Summary</label>
        <textarea class="form-control" name="Summary" id="summary" required></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Add</button>
      <a href="manage_books.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
  
</body>
</html>