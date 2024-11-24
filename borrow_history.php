<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan History</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style2.css">
    
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

      $sql = "SELECT e.id, a.full_name AS member_name, l.titre AS book_title, e.date_emprunt, e.date_retour, e.statut 
              FROM emprunts e
              JOIN adhérents a ON e.membre_id = a.id
              JOIN livres l ON e.livre_id = l.id
              ORDER BY e.date_emprunt DESC";
      $query = $pdo->prepare($sql);
      $query->execute();
      $loans = $query->fetchAll();
  ?>

  <div class="container mt-5">
    <h1 style="text-align: center;">Loan History</h1>
    
    <div class="mb-4">
      <input type="text" class="form-control" id="searchBar" placeholder="Search for a loan by book title or member name">
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Member Name</th>
                    <th>Book Title</th>
                    <th>Loan Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loans as $loan): ?>
                    <tr>
                        <td><?= htmlspecialchars($loan['id']) ?></td>
                        <td><?= htmlspecialchars($loan['member_name']) ?></td>
                        <td><?= htmlspecialchars($loan['book_title']) ?></td>
                        <td><?= htmlspecialchars($loan['date_emprunt']) ?></td>
                        <td><?= htmlspecialchars($loan['date_retour']) ?></td>
                        <td>
                          <span class="badge <?= $loan['statut'] == 'En cours' ? 'bg-success' : ($loan['statut'] == 'Retard' ? 'bg-danger' : 'bg-secondary') ?>">
                              <?= htmlspecialchars($loan['statut']) ?>
                          </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
  </div>
  <script >
    //Search Functionality 
    document.getElementById('searchBar').addEventListener('input', function () {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll('tbody tr');
      rows.forEach(row => {
          const columns = row.querySelectorAll('td');
          const text = Array.from(columns).map(col => col.textContent.toLowerCase()).join(' ');
          row.style.display = text.includes(filter) ? '' : 'none';
      });
    });
  </script>
</body>
</html>
