<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Member Information</title>
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
        $memberId = intval($_GET['id']); 
        $sql = "SELECT * FROM adhérents WHERE id = ?";
        $query = $pdo->prepare($sql);
        $query->execute([$memberId]);
        $member = $query->fetch();

        if (!$member) {
            echo "<div class='alert alert-danger'>Member not found.</div>";
            exit();
        }
    } else {
        echo "<div class='alert alert-danger'>No member ID provided.</div>";
        exit();
    }
  ?>

  <div class="container mt-5">
    <div class="col-md-8 mx-auto">
        <h1 class="text-center">Member Information</h1>
    </div>
    
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h2 class="card-title"><?= htmlspecialchars($member['full_name']) ?></h2>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> <?= htmlspecialchars($member['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($member['telephone']) ?></p>
            <p><strong>Joined on:</strong> <?= htmlspecialchars($member['date_inscription']) ?></p>
        </div>
        <div class="card-footer text-center">
            <p class="list-group-item">
              <a class="btn btn-success" href="edit_member.php?id=<?= htmlspecialchars($member['id']) ?>" role="button"><i class="bi bi-pencil-square"></i> Modify</a>

              <a class="btn btn-danger" href="delete_member.php?id=<?= htmlspecialchars($member['id']) ?>" role="button"><i class="bi bi-trash"></i> Delete</a>
            </p>
            <a href="manage_members.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Members</a>
        </div>
    </div>
  </div>
</body>
</html>
