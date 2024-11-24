<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Management</title>
    <!-- Bootstrap CSS -->
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

      $sql = "SELECT e.id, a.full_name AS member_name, l.titre AS book_title, e.date_emprunt, e.date_retour, e.statut 
              FROM emprunts e
              JOIN adhérents a ON e.membre_id = a.id
              JOIN livres l ON e.livre_id = l.id";
      $query = $pdo->prepare($sql);
      $query->execute();
      $loans = $query->fetchAll();
  ?>

  <div class="container mt-5">
    <h1 style="text-align: center;">Loans Management</h1>
    <?php if (isset($_GET['success'])): ?>
      <div id="message" class="alert alert-success" role="alert">
        Loan added successfully!
      </div>
    <?php endif ?>

    <?php if (isset($_GET['danger'])): ?>
      <div id="message" class="alert alert-danger" role="alert">
        Loan deleted successfully!
      </div>
    <?php endif ?>

    <?php if (isset($_GET['res'])): ?>
      <div id="message" class="alert alert-success" role="alert">
          Loan marked as returned successfully!
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <div id="message" class="alert alert-danger" role="alert">
          Failed to mark loan as returned. Please try again!
      </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-dark"><i class="bi bi-card-list"></i> List of Loans</h2>
      <a href="add_loan.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add a Loan</a>
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
                    <th>Actions</th>
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
                        <td>
                        <?php if ($loan['statut'] == 'En cours' || $loan['statut'] == 'Retard'): ?>
                          <a href="return_loan.php?id=<?= $loan['id'] ?>" class="btn btn-sm btn-warning">Return</a>
                        <?php else: ?>
                          <a href="return_loan.php?id=<?= $loan['id'] ?>" class="btn btn-sm btn-warning disabled" aria-disabled="true">Returned</a>
                        <?php endif; ?>

                            <a class="btn btn-danger" role="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?=$loan['id']?>"><i class="bi bi-trash"></i>Delete</a>
              
                            <!-- Modal for each book -->
                            <div class="modal fade" id="staticBackdrop<?=$loan['id']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel<?=$loan['id']?>" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel<?=$loan['id']?>">Delete <?=$loan['member_name']?>'s Loan</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    Are you sure you want to delete this Loan?
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-primary">
                                      <a class="btn btn-primary" href="delete_loan.php? id=<?=$loan['id']?>" role="button">Yes</a>
                                    </button>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
  </div>
</body>
</html>
