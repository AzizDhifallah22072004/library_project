<?php
  include 'dbconnect.php';

  if (isset($_GET['id'])) {
      $bookId = $_GET['id'];

      // Récupérer les informations actuelles du livre
      $sql = "SELECT disponibilite FROM livres WHERE id = ?";
      $query = $pdo->prepare($sql);
      $query->execute([$bookId]);
      $book = $query->fetch();

      if ($book) {
          $disponibilite = $book['disponibilite'];

          if ($disponibilite > 0) {
              // Soustraire 1 au nombre d'exemplaires disponibles
              $newDisponibilite = $disponibilite - 1;
              $updateSql = "UPDATE livres SET disponibilite = ? WHERE id = ?";
              $updateQuery = $pdo->prepare($updateSql);
              $updateQuery->execute([$newDisponibilite, $bookId]);

              // Rediriger vers la page d'accueil
              header("Location: index.php");
              exit();
          } 
      } else {
          echo "<div class='alert alert-danger'>Livre introuvable.</div>";
      }
  } else {
      echo "<div class='alert alert-danger'>Aucun ID de livre fourni.</div>";
  }
?>



