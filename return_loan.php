<?php
  include 'dbconnect.php';
  session_start();

  if (isset($_GET['id'])) {
      $loanId = intval($_GET['id']); // ID de l'emprunt à mettre à jour

      // Requête pour changer l'état de l'emprunt à "Terminé"
      $sql = "UPDATE emprunts SET statut = 'Terminé' WHERE id = ?";
      $query = $pdo->prepare($sql);

      if ($query->execute([$loanId])) {
          // Récupérer l'ID du livre associé à l'emprunt
          $sql = "SELECT livre_id FROM emprunts WHERE id = ?";
          $query = $pdo->prepare($sql);
          $query->execute([$loanId]);
          $loan = $query->fetch();

          if ($loan) {
            $bookId = $loan['livre_id'];

            // Mettre à jour les copies disponibles pour le livre
            $sql = "UPDATE livres SET disponibilite = disponibilite  + 1 WHERE id = ?";
            $query = $pdo->prepare($sql);
            $query->execute([$bookId]);
          }
          // Rediriger avec un message de succès
          header('Location: manage_loan.php?res=return');
      } else {
          // Rediriger avec un message d'erreur
          header('Location: manage_loan.php?error=return');
      }
  } else {
      // Si l'ID n'est pas fourni, redirigez vers la gestion des emprunts
      header('Location: manage_loan.php');
  }
  exit();
?>
