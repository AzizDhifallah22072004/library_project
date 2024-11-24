<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Member Information</title>
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

    // Check if ID is provided
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $memberId = $_GET['id'];
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

    // Update member details
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fullName = $_POST['FullName'];
        $email = $_POST['Email'];
        $telephone = $_POST['Telephone'];
        $registrationDate = $_POST['RegistrationDate'];

        $sql = "UPDATE adhérents SET full_name = ?, email = ?, telephone = ?, date_inscription = ? WHERE id = ?";
        $query = $pdo->prepare($sql);
        $query->execute([$fullName, $email, $telephone, $registrationDate, $memberId]);

        header('Location: manage_members.php');
        exit();
    }
  ?>

  <div class="container mt-5">
    <div class="col-md-8 mx-auto">
        <h1 style="text-align: center;">Edit Member Information</h1>
    </div>

    <div class="card shadow-lg">
        <div class="card-body">
            <form action="edit_member.php?id=<?= $memberId ?>" method="POST">
                <div class="mb-3">
                    <label for="fullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullName" name="FullName" value="<?= $member['full_name'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="Email" value="<?= $member['email'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="telephone" class="form-label">Telephone</label>
                    <input type="text" class="form-control" id="telephone" name="Telephone" value="<?= $member['telephone'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="registrationDate" class="form-label">Registration Date</label>
                    <input type="date" class="form-control" id="registrationDate" name="RegistrationDate" value="<?= $member['date_inscription'] ?>" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="manage_members.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
  </div>

</body>
</html>
