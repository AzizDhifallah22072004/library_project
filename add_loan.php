<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Loan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style2.css">
    <script src="project.js"></script>
</head> 
<body>
  <?php 
    include 'header.phtml';
    session_start();

    if (!isset($_SESSION['full_name'])) { 
        header('Location: login.php');
        exit();
    } 

    include 'dbconnect.php';

    $members = $pdo->query("SELECT id, full_name, email FROM adhérents")->fetchAll();
    $books = $pdo->query("SELECT id, titre FROM livres WHERE disponibilite > 0")->fetchAll();
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = $_POST['memberEmail'];
      $bookId = $_POST['bookId'];
      $loanDate = $_POST['loanDate'];
      $returnDate = $_POST['returnDate'];
  
      // Vérification si l'email existe dans les adhérents
      $query = $pdo->prepare("SELECT id FROM adhérents WHERE email = ?");
      $query->execute([$email]);
      $member = $query->fetch();
  
      if ($member) {
        $memberId = $member['id'];
        $insertLoan = $pdo->prepare("INSERT INTO emprunts (membre_id, livre_id, date_emprunt, date_retour, statut) 
                                      VALUES (?, ?, ?, ?, 'En cours')");
        $insertLoan->execute([$memberId, $bookId, $loanDate, $returnDate]);

        $updateBook = $pdo->prepare("UPDATE livres SET disponibilite = disponibilite - 1 WHERE id = ?");
        $updateBook->execute([$bookId]);

        header('Location: manage_loan.php?success=1');
        exit();
      } else {
          echo "<div class='alert alert-danger'>The email does not exist in our records.</div>";
      }
    }
  
  ?>

  <div class="container mt-5">
      <h1 class="text-blak mb-4">Add a Loan</h1>
      <?php if ($error): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <form method="POST">
          <div class="mb-3">
              <label for="memberEmail" class="form-label">Member Email</label>
              <input type="email" name="memberEmail" id="memberEmail" class="form-control" required>
          </div>

          <div class="mb-3">
              <label for="bookSelect" class="form-label">Book</label>
              <select name="bookId" id="bookSelect" class="form-select" required>
                  <option value="" disabled selected>Select a book</option>
                  <?php foreach ($books as $book): ?>
                      <option value="<?= htmlspecialchars($book['id']) ?>"><?= htmlspecialchars($book['titre']) ?></option>
                  <?php endforeach; ?>
              </select>
          </div>

          <div class="mb-3">
              <label for="loanDate" class="form-label">Loan Date</label>
              <input type="date" name="loanDate" id="loanDate" class="form-control" required>
          </div>

          <div class="mb-3">
              <label for="returnDate" class="form-label">Return Date</label>
              <input type="date" name="returnDate" id="returnDate" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-success">Add Loan</button>
          <a href="manage_loan.php" class="btn btn-secondary">Cancel</a>
      </form>
  </div>

</body>
</html>
