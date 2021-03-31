<?php
    require 'database.php';
    $db = Database::connect();
    $db->exec("SET CHARACTER SET utf8");
    session_start();
    
    foreach($db->query("SELECT * FROM utilisateurs") as $row)
    {
       var_dump($row['email'] . " + " . $row['password'] . " et maintenant les données de session, le login : " . $_SESSION['login'] . ", et le mot de passe : " . $_SESSION['password']);
       if($_SESSION['login'] == $row['email'] && $_SESSION['password'] == $row['password'])
       {
           var_dump("OK");
       }
       else{
        header("location: login.php");
       }
    }
    if(!empty($_GET['id']))
    {
        $id = checkInput($_GET['id']);
    }

    $db = Database::connect();
    $statement = $db->prepare('SELECT publication.id, publication.titre, publication.image, categorie.name AS category FROM publication LEFT JOIN categorie ON publication.categorie = categorie.id WHERE publication.id = ?');

    $statement->execute(array($id));
    $publication = $statement->fetch();
    Database::disconnect();

    function checkInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin voir une publication</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/fontawesome-free-5.14.0-web/css/all.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="text-logo"><span class="fas fa-utensils"></span>Administration galerie<span class="fas fa-utensils"></span></h1>
    <div class="container admin">
        <div class="row">
            <div class="col-6">
                <h1><strong>Voir un publication</strong></h1>
                <br>
                <form>
                    <div class="form-group">
                        <label>Nom : </label> <?php echo ' ' . $publication['titre']; ?>
                    </div>
                    <div class="form-group">
                        <label>Catégorie : </label> <?php echo ' ' . $publication['category']; ?>
                    </div>
                    <div class="form-group">
                        <label>Image : </label> <?php echo $publication['image']; ?>
                    </div>
                </form>
                <div class="form-action">
                    <a href="admin.php" class="btn btn-primary"><span class="fas fa-arrow-left"></span> retour</a>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 site">
                        <div class="card">
                            <img src="<?php echo '../img/' . $publication['image']?>" class="card-img-top" alt="d3">
                            <div class="card-body text-left">
                                <h4 class="card-title"><?php echo $publication['titre']; ?></h4>
                                <a href="#" class="button btn-order" role="button"><span class="fas fa-shopping-cart"></span> Commander</a>
                            </div>
                        </div>
            </div>
        </div>
    </div>

</body>
</html>