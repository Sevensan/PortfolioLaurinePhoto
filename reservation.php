<?php

require 'admin/Database.php';
$db = Database::connect();
$db->exec("SET CHARACTER SET utf8");
session_start();
if(!empty($_GET['id']))
{
    $id = $_GET['id'];
}
if(!empty($_POST))
{
    $email = $_POST['email'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $message = $_POST['message'];
    $formule = $_POST['formule'];
    $prepare = $db->prepare("INSERT INTO reservations (nom, prenom, email, telephone, message, formule) VALUES (?,?,?,?,?, ?)");
    $prepare->execute(array($nom, $prenom, $email, $telephone, $message, $formule));
    Database::disconnect();
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laurine photographie</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="fontawesome-free-5.15.0-web/css/all.css">
    <link rel="icon" type="image/x-icon" href="img/logolaurine.png"/>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img id="logo" src="img/logolaurinev3.png" alt="logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Accueil <span class="sr-only">current</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#formules">Formules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Galerie
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <?php
                        
                             $statement = $db->query("SELECT * FROM categorie");
                             $categories = $statement->fetchAll();

                             foreach($categories as $categorie)
                             {
                                 echo '<a class="dropdown-item" href="galerie.php">' . $categorie['name'] . '</a>';
                             }
                            

                        
                    echo '</div>
                </li>
            </ul>';
            ?>

                <li class="nav-item instagram">
                    <a href="https://www.instagram.com/l.tnl33/?hl=fr">
                        <img src="img/instagram.png" alt="instagram">
                    </a>
                </li>
                <li class="nav-item facebook">
                    <a href="https://www.facebook.com/LaurineTonnelet">
                        <img src="img/facebook.png" alt="facebook">
                    </a>
                </li>
                <li class="nav-item flickr">
                    <a href="https://www.flickr.com/photos/158334684@N06/">
                        <img src="img/flickr.png" alt="flickr">
                    </a>
                </li>

            <?php
       echo '</div>
    </nav>';
    ?>
    <div class="siteFormulaire">
        <img class="formulaireBG" src="img/tsuki.png" alt="background">
        <div class="container-fluid">
            <h1>Contactez moi</h1>
            <p class="alert-success"><?php
                if(!empty($_POST))
                {
                    echo 'Merci pour votre réservation ! Vous serez recontacté dans les plus bref délais';
                }            
            ?></p>
            <form class="form" action="reservation.php" method="POST" role="form">
                <div class="form-group">
                    <label for="email">Email : </label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="votre email">
                </div>
                <div class="form-group">
                    <label for="nom">Nom : </label>
                    <input type="text" name="nom" class="form-control" id="nom" placeholder="votre nom">
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom : </label>
                    <input type="text" name="prenom" class="form-control" id="prenom" placeholder="votre prénom">
                </div>
                <div class="form-group">
                    <label for="formule">Formule : </label>
                    <select name="formule" id="formule">
                    <?php
                                $db = Database::connect();
                                if($id)
                                {
                                    foreach($db->query('SELECT * FROM formules WHERE formules.id =' . " $id " .'') as $row)
                                    {
                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . "</option>";
                                    }
                                }
                                else{
                                    foreach($db->query('SELECT * FROM formules') as $row)
                                    {
                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . "</option>";
                                    }
                                }

                                Database::disconnect();
                            ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="telephone">téléphone : </label>
                    <input type="number" name="telephone" class="form-control" id="telephone" placeholder="06 00 00 00 00">
                </div>
                <div class="form-group">
                    <label for="message">Message : </label>
                    <textarea type="text" name="message" class="form-control" id="message" placeholder="votre message"></textarea>
                </div>
                <div class="form-actions">
                    <button class="btn btn-lg btn-block btn-success" type="submit">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
        <p>@2020</p>
        <div class="reseauxSociaux">
            <div class="instagram">
                <a href="https://www.instagram.com/l.tnl33/?hl=fr">
                    <img src="img/instagram.png" alt="instagram">
                </a>
            </div>
            <div class="facebook">
                <a href="https://www.facebook.com/LaurineTonnelet">
                    <img src="img/facebook" alt="facebook">
                </a>
            </div>
            <div class="flickr">
                <a href="https://www.flickr.com/photos/158334684@N06/">
                    <img src="img/flickr.png" alt="flickr">
                </a>
            </div>
        </div>
    </div>
    <div class="fleche_haut">
        <a href="#top" class="fas fa-arrow-circle-up"></a>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>