<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Book Information</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="style2.css">
</head>
<body>
  <?php 
    include 'header.phtml';
  ?>
  
  <?php
    if (isset($_SESSION['full_name'])) {//si y a une session
        header('Location: login.php');
        exit();
    } 
    include 'dbconnect.php';
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $bookId = $_GET['id'];
        $sql = "SELECT * FROM livres WHERE id = ?";
        $query = $pdo->prepare($sql);
        $query->execute([$bookId]);
        $book = $query->fetch();

        if (!$book) {
            echo "<div class='alert alert-danger'>Book not found.</div>";
            exit();
        }
    } else {
        echo "<div class='alert alert-danger'>No book ID provided.</div>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['Title'];
        $author = $_POST['Author'];
        $genre = $_POST['Genre'];
        $availability = $_POST['Availability'];
        $summary = $_POST['Summary'];

        $sql = "UPDATE livres SET titre = ?, auteur = ?, genre = ?, disponibilite = ?, resume = ? WHERE id = ?";
        $query = $pdo->prepare($sql);
        $query->execute([$title, $author, $genre, $availability, $summary, $bookId]);

        header('Location: manage_books.php');
        exit();
    }
  ?>

  <div class="container mt-5">
    
    <div class="col-md-8 mx-auto">
        <h1 style="text-align: center;">Edit Book Information</h1>
    </div>

    <div class="card shadow-lg">
        <div class="card-body">
            <form action="edit_book.php?id=<?= $bookId ?>" method="POST">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="Title" value="<?= $book['titre']?>" required>
                </div>
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" class="form-control" id="author" name="Author" value="<?= $book['auteur']?>">
                </div>
                <div class="mb-3">
                    <label for="genre" class="form-label">Genre</label>
                    <input type="text" class="form-control" id="genre" name="Genre" value="<?= $book['genre'] ?>">
                </div>
                <div class="mb-3">
                    <label for="availability" class="form-label">Availability</label>
                    <input type="number" class="form-control" id="availability" name="Availability" value="<?= $book['disponibilite'] ?>">
                </div>
                <div class="mb-3">
                    <label for="summary" class="form-label">Summary</label>
                    <textarea class="form-control" id="summary" name="Summary" rows="4" ><?= $book['resume'] ?></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="manage_books.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
  </div>

</body>
</html>
