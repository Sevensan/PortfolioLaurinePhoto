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
    
    if(!empty($_GET['id']))
    {
        $id = checkInput($_GET['id']);
        var_dump($id);
    }

    $titreError = $categoryError = $imageError = $titre = $category = $image = "";

    if(!empty($_POST))
    {
        var_dump($_POST['titre']);
        $titre = checkInput($_POST["titre"]);
        $category = checkInput($_POST["category"]);
        $image = checkInput($_FILES["image"]['name']);
        $imagePath = '../img/' . basename($image);
        $imageExtension = pathInfo($imagePath, PATHINFO_EXTENSION);
                // on récupère le nom de l'image, son chemin, et son extension

        $isSuccess = true;
        $isUploadSUccess = false;

        if(empty($titre))
        {
            $nameError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($category))
        {
            $categoryError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($image))
        {
            $isImageUpdated = false;
        }
        else{
            $isImageUpdated = true;
            $isUploadSuccess = true;
            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif")
            {
                $isUploadSuccess = false;
                $imageError = "Les fichiers autorisés sont .jpg, .jpeg, .png et .gif";
            }

            if(file_exists($imagePath))
            {
                $imageError = "le fichier existe déjà";
                $isUploadSuccess = false;
            }

            // if($_FILES['image']['size'] > 500000)
            // {
            //     $isUploadSuccess = false;
            //     $imageError = "Le fichier de noit pas dépasser les 500KB";
            // }

            if($isUploadSuccess)
            {
                if(!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath))
                {
                    $imageError = "Il y a eu une erreur lors de l'upload";
                    $isUploadSuccess = false;
                }
            }
        }

        if($isSuccess && $isImageUpdated && $isUploadSuccess || $isSuccess && !$isImageUpdated)
        {
            $db = Database::connect();
            if($isImageUpdated)
            {
                var_dump("envoi du formulaire avec l'image updaté");
                $statement = $db->prepare("UPDATE publication SET titre=?,categorie=?,image=? WHERE id=?");
                $statement->execute(array($titre,$category,$image,$id));
            }
            else{
                var_dump('envoi du formulaire sans image');
                $statement = $db->prepare("UPDATE publication SET titre=?,categorie=? WHERE id=?");
                $statement->execute(array($titre,$category,$id));
                var_dump("Le nom : " . $titre . ", l'id de la catégorie : " . $category . " et l'id : " . $id );

            }
            Database::disconnect();
            header("location: admin.php");
        }
        else if($isImageUpdated && !$isUploadSuccess)
        {
            $db = Database::connect();
            $statement = $db->prepare("SELECT image FROM publication WHERE id = ?");
            $statement->execute(array($id));
            $publication = $statement->fetch();
    
            // on recupere toutes les infos de nos éléments en BDD pour pouvoir les afficher
            $image = $publication["image"];
            var_dump($image);
            
    
            Database::disconnect();
        }

    }

    else{
        $db = Database::connect();

        $statement = $db->prepare("SELECT * FROM publication WHERE id = ?");
        $statement->execute(array($id));
        $publication = $statement->fetch();

        // on recupere toutes les infos de nos éléments en BDD pour pouvoir les afficher
        $titre = $publication["titre"];
        $category = $publication["categorie"];
        $image = $publication["image"];
        

        Database::disconnect();
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Burger Code</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/fontawesome-free-5.14.0-web/css/all.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="text-logo"><span class="fas fa-utensils"></span>Administration <span class="fas fa-utensils"></span></h1>
    <div class="container admin">
        <div class="row">
            <div class="col-6">
                <h1><strong>Modifier un item</strong></h1>
                <br>
                <form class="form" action="<?php echo 'Updategalerie.php?id=' . $id; ?>" method="POST" role="form" enctype="multipart/form-data">    <!-- permet de joindre des fichiers -->
                    <div class="form-group">
                        <label for="titre">Titre : </label>
                        <input type="text" class="form-control" id="titre" name="titre" placeholder="<?php $titre?>" value="<?php echo $titre; ?>">
                        <span class="help-inline"><?php $titreError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="category">Catégorie : </label>
                        <select class="form-control" name="category" id="category">
                            <?php
                                $db = Database::connect();
                                foreach($db->query('SELECT * FROM categorie') as $row)
                                {
                                    if($row['id'] == $category)
                                    {
                                        echo '<option selected="selected" value="' . $row['id'] . '">' . $row['name'] . "</option>";
                                    }
                                    else{
                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . "</option>";
                                    }
                                }
                                Database::disconnect();
                            ?>
                        </select>
                        <span class="help-inline"><?php $categoryError; ?></span>
                    </div>
                    <div class="form-group">
                    <label for="">Image : </label>
                    <p><?php echo $image ?></p>
                        <label for="image">Sélectionner une image </label>
                        <input type="file" id="image" name="image">
                        <span class="help-inline"><?php $imageError; ?></span>
                    </div>
                    <div class="form-actions">
                    <button type="submit" class="btn btn-success
                    "><span class="fas fa-pencil"> Modifier</span></button>
                    <a href="admin.php" class="btn btn-primary"><span class="fas fa-arrow-left"> Retour</span></a>
                    </div>
                </form>
            </div>
            <div class="col-6">
            <div class="card">
                            <img src="<?php echo '../img/' . $image ?>" class="card-img-top" alt="<?php echo $titre ?>">
                            <div class="card-body text-left">
                                <h4 class="card-title"><?php echo $titre; ?></h4>
                                <a href="#" class="button btn-order" role="button"><span class="fas fa-shopping-cart"></span>Voir</a>
                            </div>
                        </div>
            </div>
        </div>
    </div>

</body>
</html>