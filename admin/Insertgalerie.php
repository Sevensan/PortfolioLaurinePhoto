<?php
    require 'Database.php';
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
    $titreError = $categoryError = $imageError = $titre = $category = $image = "";
    if(!empty($_POST))
    {
        $titre = checkInput($_POST["titre"]);;
        $category = checkInput($_POST["category"]);
        $image = checkInput($_FILES["image"]['name']);
        $imagePath = '../img/' . basename($image);
        $imageExtension = pathInfo($imagePath, PATHINFO_EXTENSION);
                // on récupère le nom de l'image, son chemin, et son extension
        var_dump("le nom : " . $titre . ", l'id de la catégorie : " . $category . ", l'image : " . $image . ", son chemin : " . $imagePath . " et son extension : " . $imageExtension);

        $isSuccess = true;
        $isUploadSuccess = false;

        if(empty($titre))
        {
            var_dump("PAS OK");
            die();
            $titreError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($category))
        {
            var_dump("PAS OK");
            die();
            $categoryError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($image))
        {
            var_dump("PAS OK");
            die();
            $imageError = 'Vous devez choisir une image';
            $isSuccess = false;
        }
        else{
            $isUploadSuccess = true;
            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif" && $imageExtension != "JPG")
            {
                var_dump("PAS OK");
                die();
                $isUploadSuccess = false;
                $imageError = "Les fichiers autorisés sont .jpg, .jpeg, .png et .gif";
            }

            // if(file_exists($imagePath))
            // {
            //     var_dump("PAS OK, le fichier existe deja");
            //     die();
            //     $imageError = "le fichier existe déjà";
            //     $isUploadSuccess = false;
            // }

            // if($_FILES['image']['size'] > 500000)
            // {
            //     var_dump("PAS OK, le fichier est trop lourd");
            //     die();
            //     $isUploadSuccess = false;
            //     $imageError = "Le fichier de noit pas dépasser les 500KB";
            // }

            if($isUploadSuccess)
            {
                if(!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath))
                {
                    var_dump("PAS OK, erreur lors de l'upload");
                    die();
                    $imageError = "Il y a eu une erreur lors de l'upload";
                    $isUploadSuccess = false;
                }
            }
        }

        if($isSuccess && $isUploadSuccess)
        {
            var_dump("apparemment tout est OK");
            $db = Database::connect();
            $statement = $db->prepare("INSERT INTO publication (titre, image, categorie) VALUES (?, ?, ?)");
            $statement->execute(array($titre, $image, $category));
            Database::disconnect();
            header("location: admin.php");
        }

    }


    function checkInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Laurine portfolio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/fontawesome-free-5.14.0-web/css/all.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="text-logo"><span class="fas fa-utensils"></span>Administration <span class="fas fa-utensils"></span></h1>
    <div class="container admin">
        <div class="row">
            <div class="col-12">
                <h1><strong>Ajouter une publication</strong></h1>
                <br>
                <form class="form" action="Insertgalerie.php" method="post" role="form" enctype="multipart/form-data">    <!-- permet de joindre des fichiers -->
                    <div class="form-group">
                        <label for="name">Titre : </label>
                        <input type="text" class="form-control" id="titre" name="titre" placeholder="nom" value="<?php echo $titre; ?>">
                        <span class="help-inline"><?php $titreError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="category">Catégorie : </label>
                        <select class="form-control" name="category" id="category">
                            <?php
                                $db = Database::connect();
                                foreach($db->query('SELECT * FROM categorie') as $row)
                                {
                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . "</option>";
                                }
                                Database::disconnect();
                            ?>
                        </select>
                        <span class="help-inline"><?php $categoryError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="image">Sélectionner une image </label>
                        <input type="file" id="image" name="image">
                        <span class="help-inline"><?php $imageError; ?></span>
                    </div>
                    <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        <span class="fas fa-pencil"> Ajouter</span>
                    </button>
                    <a href="admin.php" class="btn btn-primary"><span class="fas fa-arrow-left"> Retour</span></a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>