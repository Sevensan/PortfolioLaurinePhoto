<?php
    require 'database.php';

    if(!empty($_GET['id']))
    {
        $id = checkInput($_GET['id']);
    }

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
    $statement = $db->prepare('SELECT formules.id, formules.name, formules.image, formules.price, formules.description FROM formules WHERE formules.id = ?');

    $statement->execute(array($id));
    $formules = $statement->fetch();
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
    <title>Admin voir une formule</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/fontawesome-free-5.14.0-web/css/all.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="text-logo"><span class="fas fa-utensils"></span>Administration formules <span class="fas fa-utensils"></span></h1>
    <div class="container admin">
        <div class="row">
            <div class="col-6">
                <h1><strong>Voir une formule</strong></h1>
                <br>
                <form>
                    <div class="form-group">
                        <label>Nom : </label> <?php echo ' ' . $formules['name']; ?>
                    </div>
                    <div class="form-group">
                        <label>Description : </label> <?php echo ' ' . $formules['description']; ?>
                    </div>
                    <div class="form-group">
                        <label>Prix : </label> <?php echo ' ' . $formules['price']; ?>
                    </div>
                    <div class="form-group">
                        <label>Image : </label> <?php echo $formules['image']; ?>
                    </div>
                </form>
                <div class="form-action">
                    <a href="admin.php" class="btn btn-primary"><span class="fas fa-arrow-left"></span> retour</a>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 site">
                        <div class="card">
                            <img src="<?php echo '../img/' . $formules['image']?>" class="card-img-top" alt="d3">
                            <div class="card-body text-left">
                                <h4 class="card-title"><?php echo $formules['name']; ?></h4>
                                <p class="card-text"><?php echo $formules['description'] ?></p>
                                <p class="card-text"><?php echo $formules['price'] ?>€</p>
                                <a href="#" class="button btn-order" role="button"><span class="fas fa-shopping-cart"></span> Réserver</a>
                            </div>
                        </div>
            </div>
        </div>
    </div>

</body>
</html>