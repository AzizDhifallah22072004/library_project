<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Information</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="style2.css">
</head>
<body>
  <?php 
    include 'header.phtml'; 
  ?>
  <?php
    session_start(); 

    if (!isset($_SESSION['full_name'])) {
      header('Location: login.php');
      exit();
    } 

    include 'dbconnect.php'; 

    if (isset($_GET['id'])) {
        $bookId = intval($_GET['id']); 
        $sql = "SELECT * FROM livres WHERE id = ?";
        $query = $pdo->prepare($sql);
        $query->execute([$bookId]);
        $book = $query->fetch();

        if (!$book) {
            echo "<div class='alert alert-danger'>Book not found.</div>";
            exit();
        }

        $isAvailable = $book['disponibilite'] > 0; //boolean
    } else {
        echo "<div class='alert alert-danger'>No book ID provided.</div>";
        exit();
    }
  ?>

  <div class="container py-4">
    <!-- Alert if the book is not available -->
    <?php if (!$isAvailable): ?>
      <div class="alert alert-danger">
        This book is not available.
      </div>
    <?php endif; ?>

    <div class="col-md-8 mx-auto">
        <h1 class="text-center">Book Information</h1>
    </div>
    
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h2 class="card-title"><?= htmlspecialchars($book['titre']) ?></h2>
        </div>
        <div class="card-body">
            <p><strong>Author:</strong> <?= htmlspecialchars($book['auteur']) ?></p>
            <p><strong>Genre:</strong> <?= htmlspecialchars($book['genre']) ?></p>
            <p><strong>Availability:</strong> <?= htmlspecialchars($book['disponibilite']) . ' copies' ?></p>
            <p><strong>Summary:</strong></p>
            <p><?= nl2br(htmlspecialchars($book['resume'])) ?></p>
        </div>
        <div class="card-footer text-center">
            <p class="list-group-item">
              <a class="btn btn-success" href="edit_book.php?id=<?= htmlspecialchars($book['id']) ?>" role="button"><i class="bi bi-pencil-square"></i> Modify</a>

              <a class="btn btn-danger" href="delete_book.php?id=<?= htmlspecialchars($book['id']) ?>" role="button"><i class="bi bi-trash"></i> Delete</a>
              
              <?php if ($isAvailable): ?>
                <a class="btn btn-warning" href="livre_emprunt.php?id=<?= htmlspecialchars($book['id']) ?>" role="button">
                  <i class="bi bi-book"></i> Borrow
                </a>
              <?php else: ?>
                <button class="btn btn-warning" disabled>
                  <i class="bi bi-book"></i> Borrow
                </button>
              <?php endif; ?>
            </p>
            <a href="manage_books.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to List</a>
        </div>
    </div>
  </div>
</body>
</html>
