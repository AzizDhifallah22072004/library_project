<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
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

    $sql = "SELECT * FROM livres";
    $query = $pdo->prepare($sql);
    $query->execute();
    $lvrs = $query->fetchAll();
    $template = 'index';
    $pageTitle = 'Acceuil';
    //include 'layout.phtml';
  ?>

  <div class="container mt-5">
    <h1 style="text-align: center;">Book Management</h1>

    <?php if (isset($_GET['success'])): ?>
      <div id="message" class="alert alert-success" role="alert">
        Book added successfully!
      </div>
    <?php endif ?>

    <?php if (isset($_GET['danger'])): ?>
      <div id="message" class="alert alert-danger" role="alert">
        Book deleted successfully!
      </div>
    <?php endif ?>

    <?php if (isset($_GET['success_all'])): ?>
      <div id="message" class="alert alert-success" role="alert">
        All books have been deleted successfully!
      </div>
    <?php endif ?>

    <?php if (isset($_GET['error_all'])): ?>
      <div id="message" class="alert alert-danger" role="alert">
        There was an error deleting the books.
      </div>
    <?php endif ?>

    <div class="row">
      <div class="col">
        <h2><i class="bi bi-card-list"></i> List of books</h2>
      </div>
      <div class="col">
        <a class="btn btn-primary" href="add_book.php" role="button"><i class="bi bi-person-add"> Add book</i></a>
      </div>
      <div class="col">
        
        <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
          <i class="bi bi-trash"> Delete All</i>
        </a>      
      
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                Are you sure you want to delete all books?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="delete_all_books.php" role="button">Yes</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <table class="table table-dark table-striped">
      <thead>
        <tr>
          <th scope="col">Id</th>
          <th scope="col">Title</th>
          <th scope="col">Author</th>
          <th scope="col">Genre</th>
          <th scope="col">Availability</th>
          <th>Options</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($lvrs as $lvr): ?>
          <tr>
            <th scope="row"><?=$lvr['id']?></th>
            <td><?=$lvr['titre']?></td>
            <td><?=$lvr['auteur']?></td>
            <td><?=$lvr['genre']?></td>
            <td><?=$lvr['disponibilite'].' copies'?></td>
            <td>
              <a class="btn btn-primary" href="book_info.php? id=<?=$lvr['id']?>" role="button"><i class="bi bi-info-square"></i></a>

              <a class="btn btn-success" href="edit_book.php? id=<?=$lvr['id']?>" role="button"><i class="bi bi-pencil-square"></i></a>
        
              <!-- Trigger Modal -->
              <a class="btn btn-danger" role="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop<?=$lvr['id']?>"><i class="bi bi-trash"></i></a>
              
              <!-- Modal for each book -->
              <div class="modal fade" id="staticBackdrop<?=$lvr['id']?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel<?=$lvr['id']?>" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel<?=$lvr['id']?>">Delete <?=$lvr['titre']?></h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      Are you sure you want to delete this book?
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-primary">
                        <a class="btn btn-primary" href="delete_book.php? id=<?=$lvr['id']?>" role="button">Yes</a>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>

    </table>
  </div>
  
</body>
</html>