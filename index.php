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
    <nav id="top" class="navbar navbar-expand-lg navbar-light bg-light">
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
                    <a class="nav-link" href="#formules">Formules</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reservation.php">Réservation</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Galerie
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <?php
                            require 'admin/Database.php';
                        
                             $db = Database::connect();
                             $db->exec("SET CHARACTER SET utf8");
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
    </nav>

    <div class="site">';

        ?>

        <div class="slider">
            <div id="slide_haut">
                <img src="" alt="">
                <div class='text-image'>
                    <h1>Laurine Tonnelet</h1>
                    <p>photographe</p>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="categories">
                <div class="row">
            <?php
            foreach($categories as $categorie)
            {
                echo '<div class="categorie col-12 col-md-6 col-lg-4" href="#">
                        <img src="img/' . $categorie['image'] . '" alt="' . $categorie['name'] . '">
                        <p>' . $categorie['name'] . '</p>
                    </div>';
            }
            ?>
                </div>
            </div>

            <?php
                foreach($categories as $categorie)
                {
                    $statement = $db->prepare("SELECT * FROM publication WHERE publication.categorie = ?");
                    $statement->execute(array($categorie['id']));
        
                    while($publication = $statement->fetch())
                    {
                        echo '<img class="imageSlider" src="img/' . $publication['image'] . '" id="imageSlider' . $publication['id'] . '">';
                    }
                }
            ?>

            <div class="formules-container container-fluid">
                    <h2>Formules</h2>
                        <div id="formules" class="row formules">
                            <?php
                            $statement = $db->query("SELECT * FROM formules");
                            $formules = $statement->fetchAll();
                                foreach($formules as $formule)
                                {
                                    // while($formule = $statement->fetch())
                                    // {
                                        echo '
                                        <div class="card col-md-5 col-lg-3">
                                            <img src="img/' . $formule['image'] . '" alt="' . $formule['name'] . '" class="img-top">
                                            <div class="card-body">
                                                <p class="card-title">' . $formule['name'] .  '</p>
                                                <p class="card-text">' . $formule['description'] . '</p>
                                                <p class="price card-text">tarif : ' . $formule['price'] . '€ </p>
                                                <a href="reservation.php?id=' . $formule['id'] .  '" class="btn btn-lg btn-block btn-dark">Réserver</a>
                                            </div>
                                        </div>
                                        ';
                                   // }
                                }                            
                            ?>
                        </div>

                    </div>
            </div>
    </div>
    <div class="card-footer footer">
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